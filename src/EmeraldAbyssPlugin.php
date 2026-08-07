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
                    '--accent-text' => '#042f2e',

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
                    '--text-subtle' => '#5a8574',
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

                    // Legacy `--color-*` aliases — only the ones the shipped SPA still reads.
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
