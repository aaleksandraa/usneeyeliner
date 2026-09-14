<?php

namespace App\Services;

class VimeoUrlParser
{
    public function normalizeInput(?string $input): ?string
    {
        if (! $input) {
            return null;
        }

        $input = trim($input);
        if (preg_match('/<iframe\b[^>]*\bsrc=["\']([^"\']+)["\']/i', $input, $matches)) {
            $input = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return filter_var($input, FILTER_VALIDATE_URL) ? $input : null;
    }

    public function extractId(?string $url): ?string
    {
        $url = $this->normalizeInput($url);
        if (! $url) {
            return null;
        }
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        if (! in_array($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)) {
            return null;
        }
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        $segments = array_values(array_filter(explode('/', $path), fn (string $segment) => $segment !== ''));

        if ($host === 'player.vimeo.com') {
            $videoIndex = array_search('video', $segments, true);

            return $videoIndex !== false && isset($segments[$videoIndex + 1]) && ctype_digit($segments[$videoIndex + 1])
                ? $segments[$videoIndex + 1]
                : null;
        }

        for ($index = count($segments) - 1; $index >= 0; $index--) {
            if (ctype_digit($segments[$index])) {
                return $segments[$index];
            }
        }

        return null;
    }

    public function extractPrivacyHash(?string $url): ?string
    {
        $url = $this->normalizeInput($url);
        $videoId = $this->extractId($url);
        if (! $videoId || ! $url) {
            return null;
        }

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        if (isset($query['h']) && is_string($query['h']) && preg_match('/^[a-zA-Z0-9]+$/', $query['h'])) {
            return $query['h'];
        }

        $segments = array_values(array_filter(explode('/', trim((string) parse_url($url, PHP_URL_PATH), '/'))));
        $videoIndex = array_search($videoId, $segments, true);
        $candidate = $videoIndex !== false ? ($segments[$videoIndex + 1] ?? null) : null;

        return is_string($candidate) && preg_match('/^[a-zA-Z0-9]{6,}$/', $candidate) ? $candidate : null;
    }

    public function embedUrl(?string $url): ?string
    {
        $videoId = $this->extractId($url);
        if (! $videoId) {
            return null;
        }

        $embedUrl = 'https://player.vimeo.com/video/'.$videoId;
        $privacyHash = $this->extractPrivacyHash($url);

        return $privacyHash ? $embedUrl.'?h='.rawurlencode($privacyHash) : $embedUrl;
    }
}
