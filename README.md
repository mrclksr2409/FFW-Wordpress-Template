# FFW Theme

Modernes WordPress-Theme für Feuerwehrwebseiten mit hellem Design und roten Akzenten. Entwickelt für die **Feuerwehr Bad Salzig** — kompatibel mit den Plugins **Einsatzverwaltung**, **The Events Calendar** und **Elementor**.

---

## Anforderungen

| Komponente | Version |
|---|---|
| WordPress | ≥ 6.0 |
| PHP | ≥ 8.0 |
| Browser | Aktuelle Version (Chrome, Firefox, Edge, Safari) |

---

## Funktionen

- **Responsives Layout** — optimiert für Desktop, Tablet und Smartphone
- **Helles Design** mit Feuerwehr-Rot (`#E30613`) als Akzentfarbe
- **Customizer-Integration** — alle Farben, Hero-Texte, Statistiken und Kontaktdaten live anpassbar
- **Bedingte Asset-Einbindung** — plugin-spezifische CSS-Dateien werden nur auf relevanten Seiten geladen
- **Elementor Pro kompatibel** — Theme Builder ersetzt Header, Footer und Templates, PHP-Fallbacks greifen ohne Elementor
- **Automatische Statistiken** — Fahrzeug- und Einsatzanzahl werden direkt aus der Datenbank gelesen (Einsatzverwaltung)
- **Übersetzungsbereit** (`ffw-theme` Text-Domain, `.pot`-Datei in `/languages/`)

---

## Installation

1. Repository als ZIP herunterladen oder klonen:
   ```bash
   git clone https://github.com/mrclksr2409/FFW-Wordpress-Template.git
   ```
2. Den Ordner in das WordPress-Theme-Verzeichnis kopieren:
   ```
   wp-content/themes/FFW-Wordpress-Template/
   ```
3. Theme in **WordPress → Design → Themes** aktivieren.
4. Empfohlene Plugins installieren (siehe [Plugin-Integrationen](#plugin-integrationen)).

---

## Customizer

Unter **Design → Customizer → FFW Theme Optionen** sind folgende Bereiche verfügbar:

### Farben
Alle Designfarben können als Hex-Werte angepasst werden — Änderungen werden live in der Vorschau angezeigt.

| Einstellung | Standard | Beschreibung |
|---|---|---|
| Akzentfarbe | `#E30613` | Hauptfarbe für Buttons, Links, Badges |
| Akzentfarbe (Dunkel) | `#b0040f` | Hover-Zustand |
| Akzentfarbe (Hell) | `#ff3040` | Aktive Elemente |
| Seitenhintergrund | `#f5f7f9` | Globale Hintergrundfarbe |
| Header/Footer | `#eef0f3` | Hintergrund für Header und Footer |
| Karten-Hintergrund | `#ffffff` | Hintergrund für Cards und Boxen |
| Primärtext | `#1a1a1a` | Überschriften und Haupttext |
| Sekundärtext | `#444444` | Fließtext |
| Gedimmter Text | `#777777` | Labels, Meta-Infos |
| Rahmenfarbe | `#dde1e7` | Trennlinien und Rahmen |

### Hero / Startseite
- Überschrift und Untertitel des Hero-Bereichs
- Hintergrundbild (Upload)
- Call-to-Action-Button: Text und Ziel-URL

### Statistiken
Die Werte für **Fahrzeuge** und **Einsätze/Jahr** werden automatisch aus dem Einsatzverwaltung-Plugin gezählt, wenn es aktiv ist. Die Felder dienen dann nur als Fallback. Die **Mitgliederzahl** muss manuell gepflegt werden.

| Einstellung | Standard |
|---|---|
| Mitglieder | `60+` |
| Fahrzeuge (Fallback) | `5` |
| Einsätze/Jahr (Fallback) | `100+` |

### Kontakt
Adresse, Telefonnummer und E-Mail-Adresse für den Footer.

### Social Media
URLs für Facebook, Instagram, YouTube und X/Twitter.

---

## Plugin-Integrationen

### Einsatzverwaltung

Das Theme nutzt die WordPress-Template-Hierarchie für den Custom Post Type `einsatz`:

| Datei | Beschreibung |
|---|---|
| `single-einsatz.php` | Einzelner Einsatzbericht (Beitragsbild → Meta-Box → Inhalt) |
| `archive-einsatz.php` | Einsatz-Archiv mit Monats-Headings und Karten |
| `template-parts/einsatz/einsatz-meta.php` | Meta-Box: Datum, Alarmzeit, Ort, Einsatzart, Fahrzeuge, Einheiten, Weitere Kräfte |
| `template-parts/einsatz/einsatz-card.php` | Karten-Layout für Archiv-Listings |
| `page-templates/template-fahrzeuge.php` | Fahrzeuge & Ausrüstung (Taxonomy `fahrzeug`) |
| `assets/css/einsatz.css` | Alle Stile für Einsatz-Seiten (wird nur dort geladen) |

**Ausgelesene Meta-Felder:**

| Meta-Key | Beschreibung |
|---|---|
| `einsatz_alarmzeit` | Datum und Alarmzeit |
| `einsatz_einsatzende` | Ende des Einsatzes |
| `einsatz_einsatzort` | Einsatzort |
| `einsatz_einsatzleiter` | Einsatzleiter |
| `einsatz_mannschaft` | Mannschaftsstärke |
| `einsatz_fehlalarm` | Fehlalarm-Flag |
| `einsatz_lrn` | Laufende Nummer |

**Taxonomien:** `einsatzart`, `fahrzeug`, `alarmierungsart`, `exteinsatzmittel`, `evw_unit`

Die plugin-eigene zweite Meta-Box (automatisch über `the_content`-Filter eingefügt) wird unterdrückt, da das Theme eine eigene, gestaltete Meta-Box liefert.

---

### The Events Calendar

| Datei | Beschreibung |
|---|---|
| `tribe/events/v2/default-template.php` | Bindet den Kalender in Theme-Header und Footer ein |
| `tribe/events/tribe-events.css` | Überschreibt alle TEC-CSS-Variablen (`--tec-color-*`) mit dem Theme-Design |

Die Datei `tribe-events.css` wird vom Plugin automatisch erkannt und geladen — kein manuelles Enqueue nötig.

---

### Elementor

Das Theme registriert alle vier Elementor-Theme-Locations:

| Location | Beschreibung |
|---|---|
| `header` | Ersetzt `header.php` |
| `footer` | Ersetzt `footer.php` |
| `single` | Ersetzt `single.php` und `single-einsatz.php` |
| `archive` | Ersetzt `archive.php` und `archive-einsatz.php` |

Das Theme funktioniert vollständig **mit und ohne** Elementor Pro.

---

## Shortcodes

### `[ffw_posts]`

Zeigt eine Liste der letzten Beiträge an — einsetzbar auf jeder Seite oder in Widgets.

**Parameter:**

| Parameter | Standard | Beschreibung |
|---|---|---|
| `limit` | `3` | Anzahl der angezeigten Beiträge |
| `category` | *(alle)* | Slug einer Kategorie zum Filtern |
| `more_text` | *(leer)* | Beschriftung des „Alle anzeigen"-Buttons |
| `more_url` | *(leer)* | Ziel-URL des Buttons |

**Beispiel:**
```
[ffw_posts limit="4" category="news" more_text="Alle Neuigkeiten" more_url="/neuigkeiten/"]
```

---

## Seiten-Templates

| Template | Beschreibung |
|---|---|
| Standard | Seiteninhalt mit optionaler Sidebar |
| **Vollbreite (ohne Sidebar)** | Seiteninhalt über die volle Breite |
| **Fahrzeuge & Ausrüstung** | Automatisches Fahrzeug-Grid aus der `fahrzeug`-Taxonomy |

---

## Verzeichnisstruktur

```
FFW-Wordpress-Template/
├── style.css                        Theme-Header + Design-System (CSS-Variablen)
├── functions.php                    Bootstrap — lädt alle inc/-Dateien
├── index.php                        WordPress-Pflichtdatei (Fallback)
├── header.php                       Site-Header
├── footer.php                       Site-Footer (3 Spalten)
├── front-page.php                   Startseite (Hero, Statistiken, News, Termine)
├── page.php                         Standard-Seite
├── single.php                       Einzelner Beitrag
├── archive.php                      Archiv-Übersicht
├── search.php                       Suchergebnisse
├── 404.php                          Fehlerseite
├── single-einsatz.php               Einzelner Einsatzbericht
├── archive-einsatz.php              Einsatz-Archiv
│
├── inc/
│   ├── theme-setup.php              Menüs, Bildgrößen, Theme-Supports
│   ├── enqueue.php                  Scripts und Styles (bedingte Einbindung)
│   ├── elementor.php                Elementor Theme Locations
│   ├── widgets.php                  Widget-Bereiche
│   ├── customizer.php               Customizer-Optionen und Live-Preview
│   ├── template-functions.php       Hilfsfunktionen
│   ├── shortcodes.php               [ffw_posts]-Shortcode
│   └── update-checker.php           Automatische Theme-Updates via GitHub
│
├── template-parts/
│   ├── header/
│   │   ├── site-branding.php        Logo und Seitenname
│   │   └── navigation.php           Hauptmenü mit Hamburger
│   ├── footer/
│   │   ├── footer-contact.php       Kontaktdaten
│   │   ├── footer-links.php         Schnelllinks
│   │   └── footer-social.php        Social-Media-Icons
│   ├── einsatz/
│   │   ├── einsatz-meta.php         Meta-Box für Einsatzberichte
│   │   └── einsatz-card.php         Einsatz-Karte für Archiv-Listings
│   ├── content.php
│   ├── content-single.php
│   ├── content-excerpt.php
│   └── content-none.php
│
├── page-templates/
│   ├── template-full-width.php      Vollbreite (ohne Sidebar)
│   └── template-fahrzeuge.php       Fahrzeuge & Ausrüstung
│
├── tribe/
│   └── events/
│       ├── v2/
│       │   ├── default-template.php TEC in Theme-Chrome einbetten
│       │   └── list/event.php       Einzelnes Event in der Listenansicht
│       └── tribe-events.css         TEC-Styling (Auto-Discovery)
│
└── assets/
    ├── css/
    │   ├── navigation.css           Header, Navigation, Hamburger-Menü
    │   ├── home.css                 Startseite: Hero, Statistiken, Sektionen
    │   └── einsatz.css              Einsatz-Seiten: Cards, Meta-Box, Tabellen
    ├── js/
    │   ├── navigation.js            Hamburger-Menü + Sticky-Header
    │   ├── main.js                  Allgemeines JavaScript
    │   └── customize-preview.js     Customizer Live-Preview
    └── images/
        ├── logo.svg                 Platzhalter-Logo
        └── hero-default.jpg         Standard-Hero-Hintergrundbild
```

---

## Design-System

Alle Designwerte sind als CSS Custom Properties in `style.css` definiert und können im Customizer überschrieben werden.

**Farben:**

| Variable | Standard | Verwendung |
|---|---|---|
| `--ffw-color-primary` | `#E30613` | Buttons, Links, Akzente |
| `--ffw-color-primary-dark` | `#b0040f` | Hover |
| `--ffw-color-primary-light` | `#ff3040` | Aktive Elemente |
| `--ffw-bg-base` | `#f5f7f9` | Seitenhintergrund |
| `--ffw-bg-card` | `#ffffff` | Karten |
| `--ffw-text-primary` | `#1a1a1a` | Haupttext |
| `--ffw-text-muted` | `#777777` | Labels, Meta |
| `--ffw-border-color` | `#dde1e7` | Rahmen |

**Typografie:**
- Überschriften: **Oswald** (Google Fonts)
- Fließtext: **Inter** (Google Fonts)

**Bildgrößen:**

| Name | Maße | Crop |
|---|---|---|
| `ffw-hero` | 1920 × 600 px | ja |
| `ffw-card` | 600 × 400 px | ja |
| `ffw-thumb` | 300 × 200 px | ja |
| `ffw-vehicle` | 800 × 533 px | ja |
| `ffw-square` | 400 × 400 px | ja |

---

## Lizenz

GNU General Public License v2 oder neuer — siehe [http://www.gnu.org/licenses/gpl-2.0.html](http://www.gnu.org/licenses/gpl-2.0.html)
