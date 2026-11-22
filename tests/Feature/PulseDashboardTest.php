<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class PulseDashboardTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function only_listed_emails_pass_the_view_pulse_gate()
    {
        config(['pulse.admin_emails' => ['admin@example.com']]);

        $this->assertTrue(Gate::forUser(User::factory()->make(['email' => 'admin@example.com']))->allows('viewPulse'));
        $this->assertFalse(Gate::forUser(User::factory()->make(['email' => 'someone@example.com']))->allows('viewPulse'));
    }

    #[Test]
    public function the_gate_denies_everyone_when_no_admin_emails_are_configured()
    {
        config(['pulse.admin_emails' => []]);

        $this->assertFalse(Gate::forUser(User::factory()->make(['email' => 'admin@example.com']))->allows('viewPulse'));
    }

    #[Test]
    public function guests_cannot_open_the_dashboard()
    {
        $this->get('/pulse')->assertForbidden();
    }

    #[Test]
    public function non_admin_users_cannot_open_the_dashboard()
    {
        config(['pulse.admin_emails' => ['admin@example.com']]);

        $this->actingAs(User::factory()->create(['email' => 'someone@example.com']))
            ->get('/pulse')
            ->assertForbidden();
    }

    #[Test]
    public function admin_users_can_open_the_dashboard()
    {
        config(['pulse.admin_emails' => ['admin@example.com']]);

        $this->actingAs(User::factory()->create(['email' => 'admin@example.com']))
            ->get('/pulse')
            ->assertOk();
    }
}
