<?php

/**
 * Emerald Abyss — a deep oceanic ui-theme plugin for Phlix.
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
 * @package Phlix\EmeraldAbyss
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
     * @return array<class-string, string> Always empty.
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
                    // the midnight built-in's own pattern) computes 10.65/9.94/
                    // 9.05:1 — verified WCAG 2.1 from the hexes 2026-10-29.
                    '--accent-text' => '#2dd4a8',

                    // Background + elevation stack.
                    '--bg' => '#030807',
                    '--surface' => '#071410',
                    '--surface-2' => '#0d1f18',
                    '--surface-3' => '#132a23',
                    '--surface-glass' => 'rgba(7, 20, 16, 0.65)',
                    '--surface-glass-strong' => 'rgba(3, 8, 7, 0.85)',

                    // Text ramp.
                    '--text' => '#e8f5f0',
                    '--text-muted' => '#8fbcaa',
                    // R6.5b (phlix-ui/src/tokens/contrast.test.ts) binds --text-subtle
                    // to >= 4.5:1 on bg, surface AND surface-2 — surface-2 is a
                    // resting content surface (MediaCard meta + admin captions).
                    // The previous '#5a8574' managed only 4.11:1 on surface-2:
                    // midnight's subtle does not transfer to this darker stack.
                    // '#679a86' keeps the sea-green ramp and computes 6.28/5.86/
                    // 5.34/4.73:1 (bg/surf/s2/s3) — verified WCAG 2.1 2026-10-29.
                    '--text-subtle' => '#679a86',
                    '--text-faint' => '#334d42',
                    '--text-on-accent' => '#030807',

                    // Borders.
                    '--border' => '#1a3329',
                    '--border-subtle' => '#0f211b',
                    '--border-strong' => '#2a4d3e',

                    // Atmosphere.
                    '--grain-opacity' => '0.035',
                    '--vignette' => 'rgba(0, 0, 0, 0.65)',
                    '--ambient' => 'rgba(45, 212, 168, 0.12)',

                    // Legacy `--color-*` aliases (phlix-tokens src/css/colors.css names): exactly the
                    // five the shipped SPA still var()-reads — 2026-10-29 census: bg 4, surface 11,
                    // text 2, text-muted 7, border 13 consumers in phlix-ui src; every other
                    // canonical alias measures zero. Each mirrors its modern token byte-exactly
                    // above — that parity is a law: tests/TokenContrastTest.php.
                    '--color-bg' => '#030807',
                    '--color-surface' => '#071410',
                    '--color-text' => '#e8f5f0',
                    '--color-text-muted' => '#8fbcaa',
                    '--color-border' => '#1a3329',
                ],
            ],
        ];
    }
}
