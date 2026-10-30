# Emerald Abyss Theme for Phlix

A deep oceanic UI theme plugin for Phlix with teal emerald accents.

## Theme Identity

| Field | Value | Description |
|-------|-------|-------------|
| **ID** | `emerald-abyss` | Theme identifier |
| **Name** | Emerald Abyss | Display name |
| **Extends** | `midnight` | Base theme |
| **Accent** | `#2dd4a8` | Primary teal accent |
| **Dark** | `true` | Dark theme |

### Color Palette

#### Background & Surfaces
- **Background**: `#061009` - Deepest dark (kept clear of the OLED/TV black-crush floor)
- **Surface**: `#0b1913` - Primary surface
- **Surface 2**: `#12251e` - Elevated surface
- **Surface 3**: `#142d25` - Highest elevation
- **Surface Glass**: `rgba(11, 25, 19, 0.65)` - Translucent surface (tracks Surface)
- **Surface Glass Strong**: `rgba(6, 16, 9, 0.85)` - Backdrop variant (tracks Background)

#### Text
- **Text**: `#e8f5f0` - Primary text (bright mint white)
- **Text Muted**: `#8fbcaa` - Secondary text
- **Text Subtle**: `#679a86` - Tertiary text (>= 4.5:1 on bg/surface/surface-2 per R6.5b)
- **Text Faint**: `#3a574a` - Disabled/hint text (placeholder tier, house-exempt from AA)
- **Text On Accent**: `#030807` - Ink for text and icons sitting ON accent fills

#### Borders
- **Border**: `#1a3329` - Default border
- **Border Subtle**: `#0f211b` - Subtle dividers
- **Border Strong**: `#2a4d3e` - Emphasized borders

#### Atmosphere Effects
- **Ambient**: `rgba(45, 212, 168, 0.18)` - Teal ambient glow
- **Vignette**: `rgba(0, 0, 0, 0.65)` - Edge darkening
- **Grain Opacity**: `0.035` - Film grain intensity

### Accent Ramp

| Token | Value |
|-------|-------|
| `--accent` | `#2dd4a8` |
| `--accent-hover` | `#5eead4` |
| `--accent-active` | `#14b8a6` |
| `--accent-soft` | `rgba(45, 212, 168, 0.15)` |
| `--accent-ring` | `rgba(45, 212, 168, 0.50)` |
| `--accent-text` | `#2dd4a8` |

### Legacy Aliases

The five `--color-*` names from `phlix-tokens` `src/css/colors.css` this
theme explicitly overrides, each mirroring its modern token byte-exactly —
a tested law (`tests/TokenContrastTest.php`). The remaining `--color-*`
names the SPA reads (`--color-error`, `--color-primary`, `--color-success`,
`--color-warning`, `--color-info`) stay intentionally unset here and arrive
inherited through `extends: midnight`.

| Token | Mirrors | Value |
|-------|---------|-------|
| `--color-bg` | `--bg` | `#061009` |
| `--color-surface` | `--surface` | `#0b1913` |
| `--color-text` | `--text` | `#e8f5f0` |
| `--color-text-muted` | `--text-muted` | `#8fbcaa` |
| `--color-border` | `--border` | `#1a3329` |

## Installation

```bash
composer require detain/phlix-plugin-emerald-abyss-theme
```

## Requirements

- PHP 8.3+
- Phlix Server 0.44.0+

## Development

Install dependencies and run the PHPUnit suite (`phpunit.xml`, bootstrapped by `tests/bootstrap.php`):

```bash
composer install
vendor/bin/phpunit --colors=always
```

Static analysis at level 9 — pass `-c phpstan.neon` so `scanDirectories` picks up the host-only interface stubs in `dev-stubs/`:

```bash
composer phpstan
```

PSR-12 coding standard over `src/`, `tests/`, and `dev-stubs/`:

```bash
composer phpcs
```

CI (`.github/workflows/test.yml`) runs all three checks on PHP `8.3` and `8.4`, and uploads coverage to Codacy from the `8.3` leg.

## License

MIT License - see [LICENSE](LICENSE) for details.

## Links

- [GitHub Repository](https://github.com/detain/phlix-plugin-emerald-abyss-theme)
- [Phlix](https://github.com/detain/phlix-server)
