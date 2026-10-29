<?php

declare(strict_types=1);

namespace Phlix\EmeraldAbyss\Tests;

use Phlix\EmeraldAbyss\EmeraldAbyssPlugin;
use Phlix\Shared\Plugin\LifecycleInterface;
use Phlix\Theming\ThemeSourceInterface;
use PHPUnit\Framework\TestCase;

final class EmeraldAbyssPluginTest extends TestCase
{
    private EmeraldAbyssPlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new EmeraldAbyssPlugin();
    }

    public function testImplementsLifecycleInterface(): void
    {
        $this->assertInstanceOf(LifecycleInterface::class, $this->plugin);
    }

    public function testImplementsThemeSourceInterface(): void
    {
        $this->assertInstanceOf(ThemeSourceInterface::class, $this->plugin);
    }

    public function testThemeSourceNameReturnsCorrectValue(): void
    {
        $this->assertSame('emerald-abyss', $this->plugin->themeSourceName());
    }

    public function testProvidedThemesReturnsExpectedStructure(): void
    {
        $themes = $this->plugin->providedThemes();

        // No assertIsArray here: providedThemes() is declared `: array`, so the
        // language itself enforces arrayness and PHPStan level 9 flagged the
        // assertion as one that can never fail. Count + shape are the real checks.
        $this->assertCount(1, $themes);

        $theme = $themes[0];
        $this->assertArrayHasKey('id', $theme);
        $this->assertArrayHasKey('name', $theme);
        $this->assertArrayHasKey('dark', $theme);
        $this->assertArrayHasKey('extends', $theme);
        $this->assertArrayHasKey('tokens', $theme);

        $this->assertSame('emerald-abyss', $theme['id']);
        $this->assertSame('Emerald Abyss', $theme['name']);
        $this->assertTrue($theme['dark']);
        $this->assertSame('midnight', $theme['extends']);
    }

    public function testAllTokensAreValidCssValues(): void
    {
        $themes = $this->plugin->providedThemes();
        $tokens = $themes[0]['tokens'];

        $this->assertIsArray($tokens);

        foreach ($tokens as $tokenName => $tokenValue) {
            $this->assertIsString($tokenName, "Token name must be string");
            $this->assertIsString($tokenValue, "Token value must be string");

            // Token names must start with '--'
            $this->assertStringStartsWith('--', $tokenName, "Token name must start with '--'");

            // Token values must NOT contain var() references (self-referential)
            $this->assertStringNotContainsString('var(', $tokenValue, "Token value must not contain var() references");
        }
    }

    public function testOnEnableDoesNotThrow(): void
    {
        // Official PHPUnit contract for "running this must not throw": the
        // prior body ended in assertTrue(true), an assertion that can never
        // fail (flagged by PHPStan level 9 on the tests corpus).
        $this->expectNotToPerformAssertions();
        $container = $this->createMock(\Psr\Container\ContainerInterface::class);

        $this->plugin->onEnable($container);
    }

    public function testOnDisableDoesNotThrow(): void
    {
        // See testOnEnableDoesNotThrow for why this replaces assertTrue(true).
        $this->expectNotToPerformAssertions();

        $this->plugin->onDisable();
    }

    public function testSubscribedEventsReturnsEmptyArray(): void
    {
        $events = $this->plugin->subscribedEvents();
        // No assertIsArray here: subscribedEvents() is declared `: array`;
        // assertEmpty on the typed return is the meaningful check.
        $this->assertEmpty($events);
    }
}
