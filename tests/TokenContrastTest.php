<?php

/**
 * Contrast contract law for the shipped Emerald Abyss token palette.
 *
 * @copyright 2026 Joe Huss <detain@interserver.net>
 * @license   MIT
 */

declare(strict_types=1);

namespace Phlix\EmeraldAbyss\Tests;

use Phlix\EmeraldAbyss\EmeraldAbyssPlugin;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Contrast contract law for the shipped token palette.
 *
 * The token contract (phlix-tokens src/css/colors.css, consumed by the shipped
 * SPA through phlix-ui applyThemeTokens) assigns each role a WCAG floor,
 * measured on the grounds it is actually painted on:
 *
 *  - `--accent-text` is the accent color AS foreground text/icon — it must
 *    reach AA (>= 4.5:1) on every background and surface. The ui sites
 *    consuming it (`color: var(--accent-text)`) sit on the elevation stack.
 *    The ink-on-fill role is `--text-on-accent`, pinned against `--accent`.
 *  - `--text-subtle` carries form placeholders and hints (ui Input.vue
 *    `::placeholder`, Combobox, CommandPalette, labels/metadata) — body-text
 *    tier, so it must reach AA (>= 4.5:1) on every ground too, per the R6.5b
 *    binding this palette was cured to on 2026-10-29.
 *  - `--accent-ring` is the desktop focus cue (the ui paints it as a
 *    `box-shadow` after `outline: none`), so the COMPOSITED result over each
 *    ground is the visible boundary and must reach WCAG 1.4.11 (>= 3:1)
 *    against that ground — the state-change basis this repo's review ledger
 *    ratified (the arctic cross-adjacency variant is registered as a cohort
 *    residual, not this law's bar; see the class docblock below).
 *  - the five legacy `--color-*` aliases must mirror their modern sources
 *    byte-exactly: the shipped SPA still reads each one (measured per-token
 *    `var()` counts on 2026-10-29), so any half-drift repaints real UI.
 *
 * Values are parsed from providedThemes() so the law follows the palette;
 * it is not a duplicated constant table. The WCAG 1.4.3 math is recomputed
 * here from the hexes themselves — no external fixture to drift. Ported from
 * the family reference cohort (arctic-jade/volcanic-ember shape) with the
 * L9-safe `(int) hexdec` cast; laws and grounds fitted to what THIS repo's
 * reviews actually set — the faint tier is a family-wide INFO, and the
 * high-contrast tier lives only in sample-theme's separate payload, so
 * neither is asserted here.
 */
#[CoversClass(EmeraldAbyssPlugin::class)]
final class TokenContrastTest extends TestCase
{
    /** WCAG 1.4.3 AA floor for normal-size text. */
    private const AA_TEXT_RATIO = 4.5;

    /** WCAG 1.4.11 floor for the focus-indicator boundary against its ground. */
    private const AA_UI_COMPONENT_RATIO = 3.0;

    /**
     * The legacy alias → modern token pairs this theme ships, and the only
     * `--color-*` names the shipped SPA reads (phlix-tokens src/css/colors.css
     * canonical set, cross-checked against `var(--color-` hits in phlix-ui
     * src components/pages/composables/styles/utils, 2026-10-29).
     *
     * @var array<string, string>
     */
    private const MIRRORED_ALIASES = [
        '--color-bg' => '--bg',
        '--color-surface' => '--surface',
        '--color-text' => '--text',
        '--color-text-muted' => '--text-muted',
        '--color-border' => '--border',
    ];

    private EmeraldAbyssPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->plugin = new EmeraldAbyssPlugin();
    }

    /**
     * @return array<string, string>
     */
    private function tokens(): array
    {
        $themes = $this->plugin->providedThemes();
        /** @var array<string, string> $tokens */
        $tokens = $themes[0]['tokens'];

        return $tokens;
    }

    /**
     * The opaque elevation stack every foreground text role is painted on.
     *
     * @return array<string, string>
     */
    private function grounds(): array
    {
        $tokens = $this->tokens();
        $grounds = [];
        foreach (['--bg', '--surface', '--surface-2', '--surface-3'] as $key) {
            $grounds[$key] = $tokens[$key];
        }

        return $grounds;
    }

    #[Test]
    public function testAccentTextMeetsAaAsForegroundOnEveryGround(): void
    {
        $ink = $this->tokens()['--accent-text'];

        foreach ($this->grounds() as $name => $ground) {
            $ratio = $this->contrastRatio($ink, $ground);
            $this->assertGreaterThanOrEqual(
                self::AA_TEXT_RATIO,
                $ratio,
                sprintf(
                    '--accent-text %s on %s (%s) is %.2f:1 — below the 4.5:1 AA foreground floor',
                    $ink,
                    $name,
                    $ground,
                    $ratio
                )
            );
        }
    }

    #[Test]
    public function testTextSubtleMeetsAaAsBodyTierOnEveryGround(): void
    {
        $ink = $this->tokens()['--text-subtle'];

        foreach ($this->grounds() as $name => $ground) {
            $ratio = $this->contrastRatio($ink, $ground);
            $this->assertGreaterThanOrEqual(
                self::AA_TEXT_RATIO,
                $ratio,
                sprintf(
                    '--text-subtle %s on %s (%s) is %.2f:1 — below the 4.5:1 AA floor',
                    $ink,
                    $name,
                    $ground,
                    $ratio
                )
            );
        }
    }

    #[Test]
    public function testTextOnAccentMeetsAaAsInkOnTheAccentFill(): void
    {
        $tokens = $this->tokens();
        $ratio = $this->contrastRatio($tokens['--text-on-accent'], $tokens['--accent']);

        $this->assertGreaterThanOrEqual(
            self::AA_TEXT_RATIO,
            $ratio,
            sprintf(
                '--text-on-accent %s on --accent %s is %.2f:1 — below the 4.5:1 AA floor',
                $tokens['--text-on-accent'],
                $tokens['--accent'],
                $ratio
            )
        );
    }

    #[Test]
    public function testAccentRingCompositesAboveTheFocusIndicatorFloorOnEveryGround(): void
    {
        $tokens = $this->tokens();
        [$rgb, $alpha] = $this->parseRgba($tokens['--accent-ring']);

        foreach ($this->grounds() as $name => $groundHex) {
            $ground = $this->parseHex($groundHex);
            $composited = $this->compositeOver([$rgb[0], $rgb[1], $rgb[2], $alpha], $ground);
            $ratio = $this->contrastRatioRgb($composited, $ground);
            $this->assertGreaterThanOrEqual(
                self::AA_UI_COMPONENT_RATIO,
                $ratio,
                sprintf(
                    '--accent-ring %s composited on %s (%s) is %.2f:1 — below the 3:1 WCAG 1.4.11 '
                    . 'floor for the focus indicator, the ui\'s primary keyboard affordance',
                    $tokens['--accent-ring'],
                    $name,
                    $groundHex,
                    $ratio
                )
            );
        }
    }

    #[Test]
    public function testTheLegacyColorAliasesMirrorTheirModernTokens(): void
    {
        $tokens = $this->tokens();

        foreach (self::MIRRORED_ALIASES as $alias => $modern) {
            $this->assertArrayHasKey($alias, $tokens);
            $this->assertSame(
                $tokens[$modern],
                $tokens[$alias],
                sprintf(
                    '%s (%s) no longer mirrors %s (%s) — the shipped SPA reads this alias '
                    . '(phlix-ui var() consumers), so it is a half-overridden state',
                    $alias,
                    $tokens[$alias],
                    $modern,
                    $tokens[$modern]
                )
            );
        }
    }

    /**
     * WCAG 2.x relative luminance of a #rrggbb hex.
     *
     * @param array{int, int, int} $rgb
     */
    private function relativeLuminance(array $rgb): float
    {
        $channels = [];
        foreach ($rgb as $channel) {
            $srgb = $channel / 255;
            $channels[] = $srgb <= 0.04045
                ? $srgb / 12.92
                : ((($srgb + 0.055) / 1.055) ** 2.4);
        }

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    }

    private function contrastRatio(string $hexA, string $hexB): float
    {
        return $this->contrastRatioRgb($this->parseHex($hexA), $this->parseHex($hexB));
    }

    /**
     * @param array{int, int, int} $rgbA
     * @param array{int, int, int} $rgbB
     */
    private function contrastRatioRgb(array $rgbA, array $rgbB): float
    {
        $luminanceA = $this->relativeLuminance($rgbA);
        $luminanceB = $this->relativeLuminance($rgbB);
        $brighter = max($luminanceA, $luminanceB);
        $darker = min($luminanceA, $luminanceB);

        return ($brighter + 0.05) / ($darker + 0.05);
    }

    /**
     * @return array{int, int, int}
     */
    private function parseHex(string $hex): array
    {
        $matches = [];
        if (preg_match('/^#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/', $hex, $matches) !== 1) {
            throw new RuntimeException("Contrast law expects literal #rrggbb, got '$hex'");
        }

        // (int) cast required: PHPStan models hexdec() as int|float (level 9),
        // the family-discovered pattern (volcanic-ember).
        return [
            (int) hexdec($matches[1]),
            (int) hexdec($matches[2]),
            (int) hexdec($matches[3]),
        ];
    }

    /**
     * @param array{int, int, int, float} $rgba
     * @param array{int, int, int} $ground
     *
     * @return array{int, int, int}
     */
    private function compositeOver(array $rgba, array $ground): array
    {
        [$r, $g, $b, $alpha] = $rgba;

        return [
            (int) round($alpha * $r + (1 - $alpha) * $ground[0]),
            (int) round($alpha * $g + (1 - $alpha) * $ground[1]),
            (int) round($alpha * $b + (1 - $alpha) * $ground[2]),
        ];
    }

    /**
     * @return array{array{int, int, int}, float}  [rgb, alpha]
     */
    private function parseRgba(string $value): array
    {
        $matches = [];
        if (
            preg_match('/^rgba\((\d{1,3}), (\d{1,3}), (\d{1,3}), (0?\.\d+|1|0)\)$/', $value, $matches)
                !== 1
        ) {
            throw new RuntimeException("Contrast law expects literal rgba(r, g, b, a), got '$value'");
        }

        return [
            [(int) $matches[1], (int) $matches[2], (int) $matches[3]],
            (float) $matches[4],
        ];
    }
}
