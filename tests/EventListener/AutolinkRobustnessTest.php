<?php

declare(strict_types=1);

/*
 * This file is part of mandrael/contao-autolink.
 *
 * (c) Michael Gasperl
 *
 * @license LGPL-3.0-or-later
 */

namespace Mandrael\ContaoAutolinkBundle\Tests\EventListener;

use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Mandrael\ContaoAutolinkBundle\EventListener\AutolinkListener;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AutolinkRobustnessTest extends TestCase
{
    private const BUFFER = '<html><body><p>Ein Acliwort hier.</p><p>Noch mehr Text ohne Ende, aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</p></body></html>';

    /**
     * @return iterable<string, array{string}>
     */
    public static function brokenRegexProvider(): iterable
    {
        // Compile error: preg_replace() returned null and blanked every text node.
        yield 'invalid' => ['Acli(wort'];
        // Runtime error ("Backtrack limit exhausted"), same effect.
        yield 'catastrophic' => ['((\w|[a-z])*)*[!?]'];
    }

    #[DataProvider('brokenRegexProvider')]
    public function testBrokenRegexKeepsTheText(string $tag): void
    {
        $backtrackLimit = ini_set('pcre.backtrack_limit', '1000');
        $jit = ini_set('pcre.jit', '0');

        // One warning per entry, not one per text node (544 log lines before).
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('warning');

        try {
            $output = $this->createListener($tag, $logger)(self::BUFFER, 'fe_page');
        } finally {
            ini_set('pcre.backtrack_limit', (string) $backtrackLimit);
            ini_set('pcre.jit', (string) $jit);
        }

        self::assertStringContainsString('Ein Acliwort hier.', $output);
        self::assertStringContainsString('Noch mehr Text ohne Ende', $output);
    }

    public function testMissingTableRendersWithoutAutolinks(): void
    {
        $connection = $this->createStub(Connection::class);
        $connection->method('fetchAllAssociative')->willThrowException($this->createStub(TableNotFoundException::class));

        $listener = new AutolinkListener($connection, $this->createStub(ContaoFramework::class));

        self::assertSame(self::BUFFER, $listener(self::BUFFER, 'fe_page'));
    }

    private function createListener(string $tag, LoggerInterface $logger): AutolinkListener
    {
        $connection = $this->createStub(Connection::class);
        $connection->method('fetchAllAssociative')->willReturn([[
            'id' => 1, 'tag' => $tag, 'regex' => '1', 'type' => 'none', 'start' => '', 'stop' => '',
            'limitPages' => '', 'cssFilter' => '', 'casesensitive' => '', 'cssID' => '', 'addLanguage' => '',
            'language' => '', 'addTip' => '', 'title' => '', 'text' => '', 'selflink' => '', 'popup' => '',
        ]]);

        $framework = $this->createStub(ContaoFramework::class);
        $framework->method('getAdapter')->willReturnCallback(static fn (string $class) => new Adapter($class));

        return new AutolinkListener($connection, $framework, null, $logger);
    }
}
