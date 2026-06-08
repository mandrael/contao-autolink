<?php

declare(strict_types=1);

/*
 * This file is part of mandrael/contao-autolink.
 *
 * (c) Michael Gasperl
 *
 * @license LGPL-3.0-or-later
 */

namespace Mandrael\ContaoAutolinkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MandraelContaoAutolinkBundle extends Bundle
{
    /**
     * The bundle class lives in src/, but the Contao resources (DCA, languages,
     * config) live in the top-level contao/ folder. Symfony's AbstractBundle would
     * return the bundle root automatically, but it only exists since Symfony 6.1 and
     * we still target Contao 4.13 (Symfony 5.4). So we reproduce that behaviour here.
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
