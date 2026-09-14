<?php

namespace Tests\Unit;

use App\Services\VimeoUrlParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class VimeoUrlParserTest extends TestCase
{
    #[DataProvider('validUrls')]
    public function test_it_extracts_video_ids_from_supported_urls(string $url, string $expectedId): void
    {
        $this->assertSame($expectedId, (new VimeoUrlParser)->extractId($url));
    }

    public static function validUrls(): array
    {
        return [
            'standard' => ['https://vimeo.com/123456789', '123456789'],
            'player' => ['https://player.vimeo.com/video/123456789', '123456789'],
            'channel' => ['https://vimeo.com/channels/staffpicks/123456789', '123456789'],
            'group' => ['https://vimeo.com/groups/shortfilms/videos/123456789', '123456789'],
            'showcase' => ['https://vimeo.com/showcase/987654/video/123456789', '123456789'],
            'unlisted' => ['https://vimeo.com/123456789/a1b2c3d4', '123456789'],
        ];
    }

    public function test_it_preserves_an_unlisted_privacy_hash_in_embed_url(): void
    {
        $parser = new VimeoUrlParser;

        $this->assertSame('https://player.vimeo.com/video/123456789?h=a1b2c3d4', $parser->embedUrl('https://vimeo.com/123456789/a1b2c3d4'));
        $this->assertSame('https://player.vimeo.com/video/123456789?h=a1b2c3d4', $parser->embedUrl('https://player.vimeo.com/video/123456789?h=a1b2c3d4'));
    }

    public function test_it_accepts_and_safely_normalizes_a_vimeo_iframe_embed(): void
    {
        $parser = new VimeoUrlParser;
        $iframe = '<iframe src="https://player.vimeo.com/video/123456789?h=a1b2c3d4&amp;badge=0" allowfullscreen></iframe>';

        $this->assertSame('https://player.vimeo.com/video/123456789?h=a1b2c3d4&badge=0', $parser->normalizeInput($iframe));
        $this->assertSame('123456789', $parser->extractId($iframe));
        $this->assertSame('https://player.vimeo.com/video/123456789?h=a1b2c3d4', $parser->embedUrl($iframe));
    }

    public function test_it_rejects_non_vimeo_and_malformed_urls(): void
    {
        $parser = new VimeoUrlParser;

        $this->assertNull($parser->extractId('https://example.com/123456789'));
        $this->assertNull($parser->extractId('not-a-url'));
        $this->assertNull($parser->extractId('https://vimeo.com/no-video-id'));
    }
}
