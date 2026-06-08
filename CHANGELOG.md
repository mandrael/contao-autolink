# Changelog

Alle nennenswerten Änderungen an diesem Projekt werden hier dokumentiert.
Das Format orientiert sich an [Keep a Changelog](https://keepachangelog.com/de/1.1.0/),
die Versionierung folgt [Semantic Versioning](https://semver.org/lang/de/).

## [0.5.0] – 2026-06-08

### Geändert
- Vollständiger Umbau von einer Legacy-Modulerweiterung (`system/modules/autolink`,
  Contao 2/3-Stil) zu einem modernen, Contao-Manager-installierbaren Composer-Bundle.
- Frontend-Hook `outputFrontendTemplate` als service-basierter `#[AsHook]`-Listener.
- DCA-Callbacks/Optionen modernisiert; das DB-Schema von `tl_autolink` bleibt
  unverändert (kein Migrations-Diff für bestehende Installationen).
- HTML-Parser von simplehtmldom 1.11 (2008) auf 1.9.1 (2019) angehoben — PHP-8-fest
  und mit korrigierten `text`-Selektoren; byte-identisch zum Upstream-Release.
- Parser-Größenlimit (`MAX_FILE_SIZE`) aus dem Listener auf 4 MB angehoben — große
  Seiten werden zuverlässig verlinkt (der Upstream-Default von 600 KB übersprang sie still).

### Entfernt
- MooTools-basierte Tooltips (`Tips`); Tooltip-Texte werden nun als natives
  `title`-Attribut gerendert.

### Kompatibilität
- Contao 4.13 LTS, 5.3 und 5.7 — PHP ≥ 8.2.
- Verifiziert auf je einer DDEV-Instanz Contao 4.13/PHP 8.2, 5.3/PHP 8.3 und 5.7/PHP 8.4
  (Bundle lädt, Migration ohne Schema-Diff, Hook + Callback registriert, PHPUnit + PHPStan grün).
