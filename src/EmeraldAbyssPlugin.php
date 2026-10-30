<?php

/**
 * Emerald Abyss — a deep oceanic ui-theme plugin for Phlix with teal accents.
 *
 * @copyright 2026 Joe Huss <detain@interserver.net>
 * @license   MIT
 */

declare(strict_types=1);

namespace Phlix\EmeraldAbyss;

use Phlix\Shared\Plugin\LifecycleInterface;
use Phlix\Theming\ThemeSourceInterface;
use Psr\Container\ContainerInterface;

/**
 * Emerald Abyss theme plugin for Phlix.
 *
 * This theme implements a deep oceanic aesthetic with teal accents, extending
 * the built-in midnight base theme. The palette evokes the feeling of deep
 * ocean waters with bioluminescent accents.
 *
 * @since 1.0.0
 */
final class EmeraldAbyssPlugin implements LifecycleInterface, ThemeSourceInterface
{
    /**
     * Canonical provenance key for this source.
     */
    public const SOURCE_NAME = 'emerald-abyss';

    /**
     * Nothing to do — the host registers the themes off the `instanceof`.
     *
     * @param ContainerInterface $container The host container (unused).
     */
    public function onEnable(ContainerInterface $container): void
    {
    }

    /**
     * Nothing to do — the host deregisters this source by name on disable.
     */
    public function onDisable(): void
    {
    }

    /**
     * A theme plugin subscribes to no events.
     *
     * The contract allows either a method name or a PHP callable per event
     * (`dev-stubs/LifecycleInterface.php:35` and the published
     * `vendor/detain/phlix-shared/src/Plugin/LifecycleInterface.php:95`), so
     * the phpdoc mirrors the full value type even though this map is always
     * empty.
     *
     * @return array<class-string, string|callable> Always empty.
     */
    public function subscribedEvents(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function themeSourceName(): string
    {
        return self::SOURCE_NAME;
    }

    /**
     * @inheritDoc
     *
     * @return list<array<array-key, mixed>>
     */
    public function providedThemes(): array
    {
        return [
            [
                'id' => 'emerald-abyss',
                'name' => 'Emerald Abyss',
                'dark' => true,
                // A BUILT-IN base: only the SPA can resolve this one.
                'extends' => 'midnight',
                'tokens' => [
                    // Accent ramp — teal/emerald tones.
                    '--accent' => '#2dd4a8',
                    '--accent-hover' => '#5eead4',
                    '--accent-active' => '#14b8a6',
                    '--accent-soft' => 'rgba(45, 212, 168, 0.15)',
                    '--accent-ring' => 'rgba(45, 212, 168, 0.50)',
                    // Role: the accent used AS FOREGROUND text/icon on the
                    // surfaces (every spinner, the active tab's label + underline,
                    // selected chips). House contract: >= 4.5:1 on bg/surface/
                    // surface-2. It is NOT the ink for text sitting ON accent
                    // fills — that role is --text-on-accent below. The previous
                    // '#042f2e' (an ink) computed 1.39/1.30/1.18:1 and rendered
                    // spinners and active tabs invisible. '#2dd4a8' (= --accent,
                    // the midnight built-in's own pattern) computes 10.22/9.54/
                    // 8.47/7.75:1 on the 2026-10-30 lifted stack — re-verified
                    // WCAG 2.1 from the hexes.
                    '--accent-text' => '#2dd4a8',

                    // Background + elevation stack. One notch off the crush
                    // (2026-10-30, webux review m2): the pre-lift floor
                    // (#030807/#071410/#0d1f18/#132a23) sat so near black
                    // that OLED panels clipped --bg to #000 and card
                    // separation at TV distance leaned entirely on these
                    // tiny steps. --surface-3 lands at its AA ceiling: the
                    // reviewer's extrapolated lighter ground (#1a352c) fails
                    // the 4.5:1 --text-subtle and 3:1 --accent-ring floors
                    // on it (4.12:1 / 2.90:1), so the top of this ladder is
                    // law-bounded, not taste-bounded — see below.
                    '--bg' => '#061009',
                    '--surface' => '#0b1913',
                    '--surface-2' => '#12251e',
                    '--surface-3' => '#142d25',
                    '--surface-glass' => 'rgba(11, 25, 19, 0.65)',
                    '--surface-glass-strong' => 'rgba(6, 16, 9, 0.85)',

                    // Text ramp.
                    '--text' => '#e8f5f0',
                    '--text-muted' => '#8fbcaa',
                    // R6.5b (phlix-ui/src/tokens/contrast.test.ts) binds --text-subtle
                    // to >= 4.5:1 on bg, surface AND surface-2 — surface-2 is a
                    // resting content surface (MediaCard meta + admin captions).
                    // The previous '#5a8574' managed only 4.11:1 on surface-2:
                    // midnight's subtle does not transfer to this darker stack.
                    // '#679a86' keeps the sea-green ramp and computes 6.03/5.63/
                    // 5.00/4.57:1 (bg/surf/s2/s3) on the 2026-10-30 lifted stack —
                    // re-verified WCAG 2.1 2026-10-30.
                    '--text-subtle' => '#679a86',
                    // '#3a574a' lifts the placeholder/disabled tier from
                    // 2.19:1 (its old '#334d42' on the old bg) to 2.43:1 on
                    // the raised bg — above midnight's own faint (#463d33,
                    // 1.98:1 on #000). House-exempt from AA (phlix-ui/
                    // src/tokens/contrast.test.ts), so no law pins it;
                    // raised because abyss "doubled down" on the family's
                    // weakest dark-tier.
                    '--text-faint' => '#3a574a',
                    // The darkest ink. Deliberately NOT tracked to the lifted
                    // floor: it sits ON accent fills (10.65:1 on --accent),
                    // where absolute depth is the point.
                    '--text-on-accent' => '#030807',

                    // Borders. Untouched by the elevation lift on purpose:
                    // --border's role is a 1.4x perceptual seam, and raising
                    // the grounds moved border-on-bg from 1.49:1 to 1.43:1
                    // (re-verified from the hexes 2026-10-30) — the same
                    // tier midnight ships, not an AA-pinned role.
                    '--border' => '#1a3329',
                    '--border-subtle' => '#0f211b',
                    '--border-strong' => '#2a4d3e',

                    // Atmosphere. --ambient 0.12 -> 0.18 (2026-10-30): the
                    // old alpha was HALF the midnight base (0.24; sample
                    // 0.22, arctic 0.18), leaving the "glow in the deep"
                    // atmosphere the README advertises effectively absent
                    // at TV distance. 0.18 = the family's ratified floor.
                    '--grain-opacity' => '0.035',
                    '--vignette' => 'rgba(0, 0, 0, 0.65)',
                    '--ambient' => 'rgba(45, 212, 168, 0.18)',

                    // Legacy `--color-*` aliases (phlix-tokens src/css/
                    // colors.css names). colors.css declares every one of
                    // these as `var(--modern-token)` inside every theme
                    // block, so an alias this plugin leaves unset still
                    // follows the overrides above through the cascade
                    // (the host re-roots `extends: midnight` on this
                    // payload) — the shipped SPA does read more than the
                    // five below: a 2026-10-30 census at phlix-ui@be521ff5
                    // counts live `var()` readers for --color-error (3
                    // occurrences), --color-primary/--color-success/
                    // --color-warning/--color-info (1 each); those arrive
                    // inherited, not shipped. The five mirrored here are
                    // the highest-traffic aliases (bg 5, surface 12, text
                    // 2, text-muted 7, border 14 occurrences, same census);
                    // each is set anyway — mirroring its modern token
                    // byte-exactly — because that is one fewer cascade
                    // assumption between a plugin author and a correct
                    // render. The mirror parity is a law:
                    // tests/TokenContrastTest.php.
                    '--color-bg' => '#061009',
                    '--color-surface' => '#0b1913',
                    '--color-text' => '#e8f5f0',
                    '--color-text-muted' => '#8fbcaa',
                    '--color-border' => '#1a3329',
                ],
            ],
        ];
    }
}
