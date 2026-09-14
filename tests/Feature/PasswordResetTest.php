<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_is_available(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Zaboravili ste lozinku?');
    }

    public function test_active_user_can_request_a_password_reset_link(): void
    {
        Notification::fake();
        $user = User::factory()->create(['status' => 'active']);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_unknown_and_inactive_accounts_receive_the_same_neutral_response(): void
    {
        Notification::fake();
        $inactiveUser = User::factory()->create(['status' => 'inactive']);

        $inactiveResponse = $this->post(route('password.email'), ['email' => $inactiveUser->email]);
        $unknownResponse = $this->post(route('password.email'), ['email' => 'unknown@example.com']);

        $this->assertSame($inactiveResponse->getSession()->get('status'), $unknownResponse->getSession()->get('status'));
        Notification::assertNothingSent();
    }

    public function test_user_can_reset_password_with_a_valid_token(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NovaLozinka123',
            'password_confirmation' => 'NovaLozinka123',
        ])->assertRedirect(route('login'))->assertSessionHas('status');

        $this->assertTrue(Hash::check('NovaLozinka123', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_invalid_reset_token_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->from(route('password.reset', ['token' => 'invalid', 'email' => $user->email]))
            ->post(route('password.update'), [
                'token' => 'invalid',
                'email' => $user->email,
                'password' => 'NovaLozinka123',
                'password_confirmation' => 'NovaLozinka123',
            ])->assertSessionHasErrors('email');

        $this->assertFalse(Hash::check('NovaLozinka123', $user->fresh()->password));
    }
}
