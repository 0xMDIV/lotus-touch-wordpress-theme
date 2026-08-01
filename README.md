# Lotus Touch

Ein warmes, feminines WordPress-Theme für Wellness- und Massage-Studios.

Lila Farbpalette, GSAP-Animationen, Custom-Post-Types für Massage-Angebote und Gutscheine, vollständig anpassbar über den WordPress-Customizer (kein Plugin nötig).

## Credits

Dieses Theme wurde in Zusammenarbeit mit **MiniMax M3** (KI-Assistent von MiniMax) entwickelt. Die Idee, das Design, die Anpassungen und die Tests wurden gemeinsam iteriert.

## Features

- **Vollständig anpassbar** via WordPress Customizer
  - 9 Farbpicker (Lila, Rosa, Pfirsich, Creme)
  - 16 Google Fonts für Headlines und Body
  - Hero-Hintergrund (Bild ODER Live-Video)
  - Studio-Icon / Logo
  - Kontaktdaten im Footer
  - Social-Media-Links
- **Custom-Post-Type "Massage"** mit:
  - Beitragsbild
  - Kurztext (Auszug) und Langtext (Editor)
  - **Mehrere Preis-Optionen** pro Massage (z. B. 30/60/90 Min)
  - **Ermäßigt**-Preise mit optionalem Vorher-Preis (z. B. "statt 75 EUR -> 65 EUR")
  - "Beliebt"-Badge
- **Custom-Post-Type "Gutschein"**
- **Eigene Suchseite** (`/suche/`) mit Live-Suche über AJAX
- **Responsive** (Mobile-First, 3 Breakpoints)
- **GSAP-Animationen** (Fade-up, Scroll-Trigger)
- **Hero-Modal** mit Preis-Tabelle (Regulär / Ermäßigt nebeneinander)
- **AJAX-Kontaktformular** (mit Spam-Schutz)
- **Schnell**: Lazy-Loading, optimierte CSS, CDN-loaded GSAP

## Installation

1. Theme-Ordner nach `/wp-content/themes/lotus-touch/` kopieren
2. Theme in **Design -> Themes** aktivieren
3. (Optional) Auf "**Beispieldaten einfügen**" klicken (erscheint als Admin-Notice)
4. Customizer öffnen und alles anpassen

## Dateistruktur

```
lotus-touch/
|-- style.css                  # Theme-Header + komplettes CSS
|-- functions.php              # Theme-Setup, CPT, Customizer, Demo-Daten
|-- header.php                 # Header mit Suche und Menü
|-- footer.php                 # Footer + Massage-Modal
|-- front-page.php             # Landingpage-Template
|-- page.php                   # Standard-Seiten
|-- index.php                  # Blog-Fallback
|-- archive-massage.php        # Massage-Archiv
|-- search.php                 # Eigene Suchseite
|-- README.md                  # Diese Datei
|-- .gitignore
`-- assets/
    |-- css/
    |   `-- customizer.css     # Customizer-UI Tweaks
    `-- js/
        |-- theme.js           # Frontend-JS (Modal, Suche, GSAP)
        `-- admin-meta.js      # Admin-JS (Preis-Add/Remove, Live-Preview)
```

## Beispieldaten

Nach der Theme-Aktivierung erscheint eine **Admin-Notice** mit einem Button "**Beispieldaten einfügen**". Ein Klick darauf installiert:

- 6 Demo-Massagen (Klassisch, Aroma, Hot Stone, Schwangerschaft, Rücken, Paar)
- Impressum und Datenschutz Seiten
- Standard-Customizer-Werte für Studio-Name, Kontaktdaten etc.

Du kannst die Demo jederzeit wieder löschen und durch deine eigenen Inhalte ersetzen.

## Customizer-Sektionen

| Bereich | Optionen |
|---|---|
| Farben | 9 Farbpicker (Primär, Akzent, Text, BG) |
| Schriften | Display-Font, Body-Font, Größe, Border-Radius |
| Hero | Studio-Name, Tagline, Beschreibung, Bild, Video, Icon, Overlay-Stärke, CTA-Buttons |
| Über uns | Eyebrow, Titel, Text, Bild, 4 Stichpunkte |
| Angebot | Eyebrow, Titel, Untertitel |
| Gutschein | Eyebrow, Titel, Text, Button |
| Kontakt | Telefon, E-Mail, Adresse, Öffnungszeiten |
| Social | Instagram, Facebook, Pinterest |

## Shortcodes / Blöcke

Keine -- das Theme nutzt nur WordPress-Standards (Custom Fields, Meta-Boxen, Customizer).

## Browser-Support

Letzte 2 Versionen von Chrome, Firefox, Safari, Edge. iOS Safari 14+, Android Chrome 90+.

## Lizenz

GNU General Public License v2 oder höher. Komplett Open Source, keine versteckten Kosten.

Entwickelt mit MiniMax M3.
