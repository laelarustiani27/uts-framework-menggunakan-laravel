<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_is_accessible_and_admin_pages_require_authentication(): void
    {
        $this->get(route('admin.login'))->assertOk();

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_and_log_out(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'correct-password',
            'is_admin' => true,
        ]);

        $this->post(route('admin.authenticate'), [
            'email' => 'admin@example.com',
            'password' => 'correct-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->get(route('admin.dashboard'))->assertOk();

        $this->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_invalid_credentials_do_not_authenticate(): void
    {
        $this->post(route('admin.authenticate'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_regular_users_cannot_access_admin_pages(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_every_admin_page_loads_the_admin_stylesheet_and_account_controls(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        foreach (['dashboard', 'calendar', 'datatable', 'forms', 'modals'] as $page) {
            $response = $this->actingAs($admin)
                ->get(route("admin.{$page}"))
                ->assertOk()
                ->assertSee('backend/assets/css/admin-custom.css')
                ->assertSee("Halo, {$admin->name}")
                ->assertSee(route('admin.logout'));

            if ($page === 'dashboard') {
                $response->assertSee("{$admin->name}!");
            }
        }
    }

    public function test_admin_can_be_created_with_the_artisan_command(): void
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Nama admin', 'Test Admin')
            ->expectsQuestion('Email admin', 'admin@example.com')
            ->expectsQuestion(
                'Password admin (minimal 12 karakter)',
                'a-secure-password',
            )
            ->expectsQuestion('Ulangi password admin', 'a-secure-password')
            ->expectsOutput('Akun admin admin@example.com berhasil dibuat atau diperbarui.')
            ->assertExitCode(0);

        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $this->assertTrue($admin->is_admin);
        $this->assertTrue(password_verify('a-secure-password', $admin->password));
    }
}
