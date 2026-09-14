<?php

namespace Tests\Unit;

use App\Services\UserAgentPresenter;
use PHPUnit\Framework\TestCase;

class UserAgentPresenterTest extends TestCase
{
    public function test_it_describes_common_browser_and_device_combinations(): void
    {
        $presenter = new UserAgentPresenter;

        $this->assertSame('Google Chrome · Windows računar', $presenter->describe('Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 Chrome/124.0 Safari/537.36'));
        $this->assertSame('Safari · iPhone', $presenter->describe('Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) AppleWebKit/605.1 Version/17.0 Mobile Safari/604.1'));
        $this->assertSame('Nepoznat browser · Nepoznat uređaj', $presenter->describe(null));
    }
}
