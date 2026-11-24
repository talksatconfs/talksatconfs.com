<?php

declare(strict_types=1);

namespace App\Mail;

/**
 * Free-tier transactional mail providers, all used over SMTP so no extra
 * packages are needed. A provider is active when MAIL_<NAME>_USERNAME and
 * MAIL_<NAME>_PASSWORD are set; the `failover` mailer then tries the active
 * providers in order and moves on when one fails (e.g. its quota is used up).
 *
 * Optional per provider: MAIL_<NAME>_HOST / MAIL_<NAME>_PORT (e.g. EU region).
 * Order: MAIL_FAILOVER_ORDER, a comma separated list of the names below.
 */
class SmtpProviders
{
    public const PROVIDERS = [
        'brevo' => ['host' => 'smtp-relay.brevo.com', 'port' => 587],
        'mailgun' => ['host' => 'smtp.mailgun.org', 'port' => 587],
        'resend' => ['host' => 'smtp.resend.com', 'port' => 587],
        'mailjet' => ['host' => 'in-v3.mailjet.com', 'port' => 587],
        'smtp2go' => ['host' => 'mail.smtp2go.com', 'port' => 2525],
    ];

    /**
     * Mailer definitions for every provider that has credentials, keyed by
     * mailer name (smtp-<provider>), in failover order.
     *
     * @param  callable(string): mixed|null  $env
     * @return array<string, array<string, mixed>>
     */
    public static function mailers(?callable $env = null): array
    {
        $env ??= fn (string $key) => env($key);
        $mailers = [];

        foreach (self::order($env) as $name) {
            $prefix = 'MAIL_' . strtoupper($name) . '_';
            $username = $env($prefix . 'USERNAME');
            $password = $env($prefix . 'PASSWORD');

            if (blank($username) || blank($password)) {
                continue;
            }

            $defaults = self::PROVIDERS[$name];

            $mailers['smtp-' . $name] = [
                'transport' => 'smtp',
                'host' => $env($prefix . 'HOST') ?: $defaults['host'],
                'port' => (int) ($env($prefix . 'PORT') ?: $defaults['port']),
                'encryption' => 'tls',
                'username' => $username,
                'password' => $password,
                'timeout' => 10,
            ];
        }

        return $mailers;
    }

    /**
     * Mailer names for the failover chain.
     *
     * @param  callable(string): mixed|null  $env
     * @return list<string>
     */
    public static function failoverMailers(?callable $env = null): array
    {
        return array_keys(self::mailers($env));
    }

    /**
     * @param  callable(string): mixed  $env
     * @return list<string>
     */
    private static function order(callable $env): array
    {
        $configured = array_filter(array_map(
            fn ($name) => strtolower(trim($name)),
            explode(',', (string) $env('MAIL_FAILOVER_ORDER')),
        ));

        $known = array_values(array_unique(array_filter(
            $configured,
            fn ($name) => array_key_exists($name, self::PROVIDERS),
        )));

        // Providers not mentioned in MAIL_FAILOVER_ORDER go last, in default order.
        return array_values(array_unique([...$known, ...array_keys(self::PROVIDERS)]));
    }
}
