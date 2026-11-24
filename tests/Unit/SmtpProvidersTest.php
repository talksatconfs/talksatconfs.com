<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Mail\SmtpProviders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SmtpProvidersTest extends TestCase
{
    private function env(array $values): callable
    {
        return fn (string $key) => $values[$key] ?? null;
    }

    #[Test]
    public function only_providers_with_credentials_are_active()
    {
        $env = $this->env([
            'MAIL_BREVO_USERNAME' => 'user',
            'MAIL_BREVO_PASSWORD' => 'secret',
            'MAIL_MAILGUN_USERNAME' => 'user-only',
            'MAIL_RESEND_USERNAME' => '',
            'MAIL_RESEND_PASSWORD' => '',
        ]);

        $this->assertSame(['smtp-brevo'], SmtpProviders::failoverMailers($env));
    }

    #[Test]
    public function nothing_is_active_without_credentials()
    {
        $this->assertSame([], SmtpProviders::failoverMailers($this->env([])));
    }

    #[Test]
    public function the_failover_order_is_configurable_and_unmentioned_providers_go_last()
    {
        $creds = [];
        foreach (['BREVO', 'MAILGUN', 'RESEND', 'MAILJET', 'SMTP2GO'] as $name) {
            $creds["MAIL_{$name}_USERNAME"] = 'u';
            $creds["MAIL_{$name}_PASSWORD"] = 'p';
        }

        $env = $this->env($creds + ['MAIL_FAILOVER_ORDER' => ' Resend , mailgun,unknown,resend']);

        $this->assertSame(
            ['smtp-resend', 'smtp-mailgun', 'smtp-brevo', 'smtp-mailjet', 'smtp-smtp2go'],
            SmtpProviders::failoverMailers($env),
        );
    }

    #[Test]
    public function mailer_definitions_use_provider_defaults_and_allow_overrides()
    {
        $mailers = SmtpProviders::mailers($this->env([
            'MAIL_MAILGUN_USERNAME' => 'postmaster@mg.example.com',
            'MAIL_MAILGUN_PASSWORD' => 'secret',
            'MAIL_MAILGUN_HOST' => 'smtp.eu.mailgun.org',
            'MAIL_SMTP2GO_USERNAME' => 'u',
            'MAIL_SMTP2GO_PASSWORD' => 'p',
        ]));

        $this->assertSame('smtp', $mailers['smtp-mailgun']['transport']);
        $this->assertSame('smtp.eu.mailgun.org', $mailers['smtp-mailgun']['host']);
        $this->assertSame(587, $mailers['smtp-mailgun']['port']);
        $this->assertSame('postmaster@mg.example.com', $mailers['smtp-mailgun']['username']);
        $this->assertSame('mail.smtp2go.com', $mailers['smtp-smtp2go']['host']);
        $this->assertSame(2525, $mailers['smtp-smtp2go']['port']);
    }
}
