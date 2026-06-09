<img align="right" width="100" src="logo.svg" alt="Autolink logo">

[Deutsch](README.md) | **English**

# Autolink – Automatic link creation for Contao

Autolink provides a back end interface to define keywords that are automatically
turned into links in the front end. It searches the rendered page output for the
configured terms and creates internal links (to a Contao page), external links
(to a URL) or a plain styled `<span>`. The search can be restricted to the pages
you need.

This is the modernised version: a Composer bundle that installs via the
**Contao Manager** or `composer require` — no more copying folders into
`system/modules`.

* Compatibility: a single codebase for the Contao LTS releases **4.13, 5.3 and 5.7**, **PHP 8.2+**
* Service-based `outputFrontendTemplate` hook (`#[AsHook]`)
* Database: **InnoDB / utf8mb4**; schema defined in the DCA (no manual
  `database.sql` import) and unchanged from the legacy table
* **SVG** icon instead of the old GIF

## Installation

### Contao Manager

Search for `mandrael/contao-autolink` in the Contao Manager, add it and run the
updates. The Manager runs the database migration and installs the bundle assets
automatically.

### Composer

```bash
composer require mandrael/contao-autolink
```

Afterwards apply the database migration (`contao:migrate` / Contao Manager) so
the `tl_autolink` table is created, and install the bundle assets
(`contao:install` runs on Manager updates).

## Usage

Open **Content → Autolink** in the back end, create an entry, enter the search
term, choose the link type (internal / external / none) and target, and publish
it. The term is then linked automatically in the front end.

Matching is always **whole-word**: a term only links as a complete word/phrase,
never inside a larger word (so “Salzburg” is not linked inside “Salzburger”).

When terms overlap (e.g. “Salzburg” and “Pfadfinderhaus Salzburg”), the **longer
phrase wins**: it is linked as a whole and the shorter term it contains is not
linked again inside it — regardless of the sort order.

Per entry you can configure: case sensitivity, regular expressions, a CSS selector
to restrict the search area, a language (`lang` attribute), a tooltip (rendered as
a native `title` attribute), page restrictions (incl. subpages), a custom CSS
id/class, a popup link, self-linking and a publish time window.

## Upgrading from the legacy extension

The `tl_autolink` table schema is unchanged, so existing entries keep working —
just remove the old `system/modules/autolink` folder and install via Composer /
Contao Manager.

**Note:** the old MooTools-based tooltips (`Tips`) no longer exist in modern
Contao. The tooltip text is now rendered as a native `title` attribute, so
existing entries still show their text on hover, just without the MooTools
styling.

## Screenshots

Backend

![Backend](docs/images/autolink-backend.png)

Create links

![Create links](docs/images/autolink-link-creation.png)

New icon

![New icon](docs/images/autolink-new-icon.png)

## Based on Andreas Schempp's Autolink — what changed

This bundle continues the **Autolink** extension originally written by
**[Andreas Schempp](https://github.com/aschempp/contao-autolink)** for TYPOlight /
early Contao, carried forward through Contao 3 and modernised here for current
Contao. The original repository is archived and no longer runs on current Contao,
so it is referenced for attribution and history rather than used as a dependency.

The back end feature set and the `tl_autolink` schema are **inherited from
Schempp's original**, which was already comprehensive: search term →
internal / external / no link, whole-word matching, case sensitivity, regular
expressions, CSS-selector-restricted search, `lang` attribute, tooltip,
page/subpage restrictions, custom CSS id/class, popup links, self-linking and a
publish time window. What changed is the platform, the plumbing, the packaging —
plus runtime improvements and one deliberate simplification:

**Platform & installation**
- Runs from a single codebase on the Contao LTS releases **4.13, 5.3 and 5.7**;
  originally TYPOlight / Contao 2–3.
- Installs as a **Composer bundle via the Contao Manager** instead of
  copying a folder into `system/modules`.
- **PHP 8.2+** (was PHP 5).

**Architecture**
- Front end linking is a service-based **`#[AsHook('outputFrontendTemplate')]`**
  listener instead of a classic global hook.
- The back end language dropdown is an **`#[AsCallback]`** backed by the
  **`contao.intl.locales`** service (the removed `System::getLanguages()` is gone).
- Database access through **Doctrine DBAL** instead of the legacy `Database`
  singleton.
- The schema is generated from the **DCA `sql` keys** (no manual `database.sql`);
  the schema itself is unchanged, so existing data migrates with no diff.

**Performance & robustness**
- **Large pages are now linked reliably:** the parser's input cap was raised from
  the 600 KB upstream default to 4 MB. Above the old cap, autolinking was silently
  skipped for the whole page.
- **Faster on keyword-heavy pages:** keywords that do not occur on a page are
  skipped before the expensive HTML parse (a Unicode-aware `mb_stripos` pre-filter).
  On a sample page with 60 configured-but-absent keywords this dropped the listener
  cost from ~175 ms to ~3 ms — relevant for pages with many entries (e.g. course
  pages).

**Data & assets**
- Table converted to **InnoDB / utf8mb4** (was MyISAM / utf8).
- **SVG** icon instead of the old GIF.
- **French** translation added (the original shipped only German and English).
- Bundled HTML parser raised to **simplehtmldom 1.9.1** — the last maintained
  1.x release, which fixes the `text` selector the linker relies on — kept
  byte-identical to the upstream release for auditability.

**Behaviour**
- Tooltips render as a native **`title` attribute**; the old MooTools "Tips"
  assets (`tips.css`, `bubble.png`) are gone.
- **The “match partial words” mode was removed; matching is always whole-word.**
  The old substring mode over-linked (it linked e.g. “Salzburg” inside
  “Salzburger”) and is not needed for phrase-based linking. Regular-expression
  entries are unaffected (they are matched exactly as written).
  **Schema note:** the now-unused `tl_autolink.words` column is dropped — this is
  the one intentional schema change; the rest of the table is unchanged.

**Quality**
- Test suite + GitHub Actions CI and PHPStan static analysis; verified on
  Contao 4.13, 5.3 and 5.7 (back end module, migration, front end linking).

## Credits

Original idea and matching logic: **[Andreas Schempp](https://github.com/aschempp/contao-autolink)**.
Contao itself is © Leo Feyer. The modernised bundle is maintained by
**[mandrael](https://github.com/mandrael)** (Michael Gasperl).

## License

GNU Lesser General Public License v3.0 or later (`LGPL-3.0-or-later`) — see
[LICENSE](LICENSE). This matches the license of the original work this bundle is
derived from. The bundled HTML parser
(`src/Resources/contrib/simple_html_dom.php`) is under the MIT License — see
[src/Resources/contrib/LICENSE](src/Resources/contrib/LICENSE).

## The bundled HTML parser

Front end matching uses
[simple_html_dom 1.9.1](https://sourceforge.net/projects/simplehtmldom/files/simplehtmldom/)
(MIT), bundled byte-identical to the upstream release.

An interesting detail on why it just keeps working: it stays compatible from
PHP 5.6 through 8.4+ precisely because it is deliberately minimal — no type
declarations (nothing for newer PHP versions to deprecate), magic `__get`/`__set`
instead of dynamic properties (so it never trips the PHP 8.2 deprecation), and only
the language's stable core (arrays, strings, PCRE), avoiding every construct
PHP has since removed. That same conservatism is also why it is robust rather than
fast.
