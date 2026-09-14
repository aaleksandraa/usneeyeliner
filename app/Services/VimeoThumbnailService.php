<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Throwable;

class VimeoThumbnailService
{
    public function __construct(private readonly VimeoUrlParser $parser) {}

    public function fetch(?string $vimeoUrl): ?string
    {
        $videoId = $this->parser->extractId($vimeoUrl);
        if (! $videoId) {
            return null;
        }

        $privacyHash = $this->parser->extractPrivacyHash($vimeoUrl);
        $canonicalUrl = 'https://vimeo.com/'.$videoId.($privacyHash ? '/'.$privacyHash : '');

        try {
            $response = Http::acceptJson()
                ->withHeaders(['Referer' => (string) config('app.url')])
                ->connectTimeout(3)
                ->timeout(6)
                ->retry(2, 150, fn (Throwable $exception) => $exception instanceof ConnectionException)
                ->get('https://vimeo.com/api/oembed.json', [
                    'url' => $canonicalUrl,
                    'width' => 1280,
                ]);

            if (! $response->successful()) {
                return null;
            }

            $thumbnailUrl = $response->json('thumbnail_url');

            return $this->isTrustedThumbnailUrl($thumbnailUrl) ? $thumbnailUrl : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function isTrustedThumbnailUrl(mixed $url): bool
    {
        if (! is_string($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return parse_url($url, PHP_URL_SCHEME) === 'https'
            && ($host === 'vimeocdn.com' || str_ends_with($host, '.vimeocdn.com'));
    }
}
