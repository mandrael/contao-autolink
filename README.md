# Autolink – Automatic link creation for Contao

Autolink provides a backend interface to define keywords that are automatically
turned into links in the frontend. It searches the rendered page output for the
configured terms and creates internal links (to a Contao page), external links
(to a URL) or a plain styled `<span>`. The matching can be restricted to certain
pages, limited by CSS selector, time window and more.

This is the modernised version: a proper Composer bundle that installs via the
**Contao Manager** or `composer require` — no more copying folders into
`system/modules`.

* Compatibility: **Contao 4.13, 5.3 and 5.7**, **PHP 8.2+**
* Registered as a service-based `outputFrontendTemplate` hook (`#[AsHook]`)
* Database schema is defined in the DCA (no manual `database.sql` import)

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

Open **Content → Autolink** in the backend, create an entry, enter the search
term, choose the link type (internal / external / none) and target, and publish
it. The term will be linked automatically in the frontend.

Per entry you can configure: full-word vs. partial matching, case sensitivity,
regular expressions, a CSS selector to restrict the search area, a language
(`lang` attribute), a tooltip (rendered as a native `title` attribute), page
restrictions (incl. subpages), a custom CSS id/class and a publish time window.

## Upgrading from the legacy extension

The `tl_autolink` table schema is unchanged, so existing entries keep working —
just remove the old `system/modules/autolink` folder and install via Composer /
Contao Manager.

**Note:** the old MooTools-based tooltips (`Tips`) no longer exist in modern
Contao. The tooltip text is now rendered as a native `title` attribute, so
existing "Tool-Tip" entries still show their text on hover, just without the
MooTools styling.

## Screenshots

Backend
![Backend](https://github.com/mandrael/contao-autolink/blob/main/docs/images/autolink-backend.png)

Create links
![Create links](https://github.com/mandrael/contao-autolink/blob/main/docs/images/autolink-link-creation.png)

## Credits

This bundle is based on the original **Autolink** extension for Contao by
**[Andreas Schempp](https://github.com/aschempp/contao-autolink)** — his idea and
matching logic are the foundation of this modernised version. The original
repository is archived and no longer functional on current Contao versions, so it
is referenced here for attribution rather than linked as a dependency. The
modernised bundle is maintained by [mandrael](https://github.com/mandrael).

## License

GNU Lesser General Public License v3.0 or later (`LGPL-3.0-or-later`) — see
[LICENSE](LICENSE). This matches the license of the original work this bundle is
derived from. The bundled HTML parser
(`src/Resources/contrib/simple_html_dom.php`) is licensed under the MIT License —
see [src/Resources/contrib/LICENSE](src/Resources/contrib/LICENSE).
