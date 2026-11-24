<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MailFailoverTest extends TestCase
{
    #[Test]
    public function the_failover_mailer_moves_on_when_the_first_provider_fails()
    {
        config([
            'mail.mailers.dead' => [
                'transport' => 'smtp',
                'host' => '127.0.0.1',
                'port' => 1, // nothing listens here: connection refused
                'timeout' => 2,
            ],
            'mail.mailers.working' => ['transport' => 'array'],
            'mail.mailers.chain' => [
                'transport' => 'failover',
                'mailers' => ['dead', 'working'],
            ],
        ]);
        Mail::purge();

        Mail::mailer('chain')->raw('hello', fn ($message) => $message->to('a@example.com')->subject('Hi'));

        // The failover chain builds its own copy of each transport, so read the
        // messages from the array transport inside the chain.
        $chain = Mail::mailer('chain')->getSymfonyTransport();
        $property = new \ReflectionProperty(\Symfony\Component\Mailer\Transport\RoundRobinTransport::class, 'transports');
        $transports = $property->getValue($chain);
        $array = collect($transports instanceof \SplObjectStorage ? iterator_to_array($transports) : $transports)
            ->first(fn ($transport) => $transport instanceof \Illuminate\Mail\Transport\ArrayTransport);

        $this->assertNotNull($array);
        $this->assertCount(1, $array->messages());
        $this->assertSame('Hi', $array->messages()[0]->getOriginalMessage()->getSubject());
    }

    #[Test]
    public function the_default_failover_chain_is_the_log_mailer_without_provider_credentials()
    {
        $this->assertSame(['log'], config('mail.mailers.failover.mailers'));
    }
}
