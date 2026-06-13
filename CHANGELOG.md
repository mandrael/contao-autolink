# Changelog

Alle nennenswerten Änderungen an diesem Projekt werden hier dokumentiert.
Das Format orientiert sich an [Keep a Changelog](https://keepachangelog.com/de/1.1.0/),
die Versionierung folgt [Semantic Versioning](https://semver.org/lang/de/).

## [0.5.0] – 2026-06-08

### Hinzugefügt
- Sichtbarkeits-Toggle in der Listenansicht: Einträge lassen sich direkt über das
  Auge-Symbol ein- und ausblenden (Contao-Standard, funktioniert mit Contao 4.13
  und 5.x in einer Codebasis). Neue Einträge sind standardmäßig eingeblendet.

### Geändert
- Vollständiger Umbau von einer Legacy-Modulerweiterung (`system/modules/autolink`,
  Contao 2/3-Stil) zu einem modernen, Contao-Manager-installierbaren Composer-Bundle.
- Frontend-Hook `outputFrontendTemplate` als service-basierter `#[AsHook]`-Listener.
- DCA-Callbacks/Optionen modernisiert; das DB-Schema von `tl_autolink` bleibt
  — bis auf die entfernte Spalte `words` (siehe „Entfernt") — unverändert.
- HTML-Parser von simplehtmldom 1.11 (2008) auf 1.9.1 (2019) angehoben — PHP-8-fest
  und mit korrigierten `text`-Selektoren; byte-identisch zum Original-Release.
- Parser-Größenlimit (`MAX_FILE_SIZE`) aus dem Listener auf 4 MB angehoben — große
  Seiten werden zuverlässig verlinkt (der Standardwert von 600 KB übersprang sie still).
- Performance: Keywords, die auf einer Seite gar nicht vorkommen, werden vor dem
  rechenintensiven HTML-Parsing übersprungen (Unicode-`mb_stripos`-Vorfilter). Bei vielen
  konfigurierten Begriffen pro Seite (z. B. Kursseiten) deutlich schneller
  (Beispiel: 60 nicht vorkommende Begriffe ~175 ms → ~3 ms).
- Überlappende Begriffe werden deterministisch aufgelöst: der längere Begriff
  gewinnt (längster zuerst verarbeitet), **unabhängig von der manuellen Sortierung**.
  So wird z. B. „Pfadfinderhaus Salzburg" bzw. „Salzburg, Hotel Momentum" als ganze
  Phrase verlinkt, ohne dass das enthaltene „Salzburg" darin erneut nachverlinkt.

### Entfernt
- MooTools-basierte Tooltips (`Tips`); Tooltip-Texte werden nun als natives
  `title`-Attribut gerendert.
- **Teilstring-Modus („Vollständige Wörter" / `words`) inklusive der gleichnamigen
  Tabellenspalte.** Begründung: Der abschaltbare Wortgrenzen-Modus erlaubte
  Teiltreffer *innerhalb* anderer Wörter (z. B. „Salzburg" in „Salzburger") — eine
  Überverlinkungs-Gefahr, die für phrasenbasierte Verlinkung nie gebraucht wird.
  Gesucht wird jetzt **immer wortgenau**; Regex-Einträge bleiben unberührt (werden
  exakt wie geschrieben gematcht). Da das Feld ohne Checkbox ohnehin nicht mehr
  bearbeitbar wäre, wird auch die Spalte `tl_autolink.words` entfernt. Die Migration
  schlägt dafür `ALTER TABLE tl_autolink DROP words` vor; da es eine destruktive
  Änderung ist, wird sie erst nach Bestätigung im **Contao Manager** bzw. mit
  **`contao:migrate --with-deletes`** ausgeführt (einfaches `contao:migrate` lässt
  sie aus). Das ist die einzige bewusste Schema-Änderung; alle übrigen Spalten und
  Daten bleiben unverändert.

### Kompatibilität
- Contao-LTS-Versionen 4.13, 5.3 und 5.7 — PHP ≥ 8.2.
- Verifiziert auf je einer DDEV-Instanz Contao 4.13/PHP 8.2, 5.3/PHP 8.3 und 5.7/PHP 8.4
  (Bundle lädt, Migration entfernt nur `words`, Hook + Callback registriert, PHPUnit + PHPStan grün).
