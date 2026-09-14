<?php

namespace App\Services;

class UserAgentPresenter
{
    public function describe(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Nepoznat browser · Nepoznat uređaj';
        }

        return $this->browser($userAgent).' · '.$this->device($userAgent);
    }

    private function browser(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Edg/'), str_contains($userAgent, 'EdgA/'), str_contains($userAgent, 'EdgiOS/') => 'Microsoft Edge',
            str_contains($userAgent, 'OPR/'), str_contains($userAgent, 'Opera/') => 'Opera',
            str_contains($userAgent, 'Chrome/'), str_contains($userAgent, 'CriOS/') => 'Google Chrome',
            str_contains($userAgent, 'Firefox/'), str_contains($userAgent, 'FxiOS/') => 'Mozilla Firefox',
            str_contains($userAgent, 'Safari/') => 'Safari',
            default => 'Drugi browser',
        };
    }

    private function device(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'iPad') => 'iPad',
            str_contains($userAgent, 'iPhone') => 'iPhone',
            str_contains($userAgent, 'Android') => str_contains($userAgent, 'Mobile') ? 'Android telefon' : 'Android uređaj',
            str_contains($userAgent, 'Windows') => 'Windows računar',
            str_contains($userAgent, 'Macintosh'), str_contains($userAgent, 'Mac OS X') => 'Mac',
            str_contains($userAgent, 'Linux') => 'Linux računar',
            default => 'Nepoznat uređaj',
        };
    }
}
