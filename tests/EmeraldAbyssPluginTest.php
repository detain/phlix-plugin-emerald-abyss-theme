<?php

declare(strict_types=1);

namespace Phlix\EmeraldAbyss\Tests;

use Phlix\EmeraldAbyss\EmeraldAbyssPlugin;
use Phlix\Shared\Plugin\LifecycleInterface;
use Phlix\Theming\ThemeSourceInterface;
use PHPUnit\Framework\TestCase;

final class EmeraldAbyssPluginTest extends TestCase
{
    // Verbatim mirrors of phlix-server src/Theming/ThemeTokenValidator's
    // accepted token-value shapes (server coords at its current master:
    // MAX_VALUE_LENGTH :101, HEX_PATTERN :106-107, NUMBER_PATTERN :114,
    // FUNCTION_PATTERN :122-124, ARGUMENT :129, KEYWORDS :136, isSafeValue
    // :238-253). Copied so this test refuses exactly what the server would
    // refuse at enable time; re-check against the server if they move.
    private const SERVER_MAX_VALUE_LENGTH = 128;
    private const SERVER_HEX_PATTERN =
        '/^#(?:[0-9A-Fa-f]{3}|[0-9A-Fa-f]{4}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})\z/';
    private const SERVER_NUMBER_PATTERN = '/^[+-]?(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+)\z/';
    private const SERVER_ARGUMENT = '[+-]?(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+)(?:%|(?i:deg))?';
    private const SERVER_FUNCTION_PATTERN =
        '/^(?i:rgba?|hsla?)\((?:[ ]*)' . self::SERVER_ARGUMENT
        . '(?:(?:[ ]*)[,\/](?:[ ]*)' . self::SERVER_ARGUMENT . '){2,3}(?:[ ]*)\)\z/';
    /**
     * @var list<string>
     */
    private const SERVER_KEYWORDS = ['transparent', 'currentcolor'];

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

            // Token values must pass the server's enable-time value grammar
            // (mirrored below). This replaces the former not-contains 'var('
            // check, which let '#nothex' and 'url(evil)' sail through (srphp
            // review m2). var()/url() need no dedicated rejection: neither
            // matches any of the three accepted grammars — and contrary to
            // the old "(self-referential)" comment, the server refuses ALL
            // off-grammar values, not just self-referencing var() ones.
            $this->assertTrue(
                self::valuePassesServerGrammar($tokenValue),
                "Token {$tokenName} value '{$tokenValue}' fails the server's isSafeValue() grammar"
            );
        }
    }

    /**
     * Byte-faithful port of the server's ThemeTokenValidator::isSafeValue()
     * (trim, length cap, lowercase keyword, hex/function/number grammars).
     */
    private static function valuePassesServerGrammar(string $value): bool
    {
        $trimmed = trim($value);

        if ($trimmed === '' || strlen($trimmed) > self::SERVER_MAX_VALUE_LENGTH) {
            return false;
        }

        if (in_array(strtolower($trimmed), self::SERVER_KEYWORDS, true)) {
            return true;
        }

        return preg_match(self::SERVER_HEX_PATTERN, $trimmed) === 1
            || preg_match(self::SERVER_FUNCTION_PATTERN, $trimmed) === 1
            || preg_match(self::SERVER_NUMBER_PATTERN, $trimmed) === 1;
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
