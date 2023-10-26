<?php

namespace App\Console\Commands;

use Domain\TalksAtConfs\Models\Conference;
use Illuminate\Console\Command;

class ConferenceChannelCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tac:conference-channel-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Conference::whereNotNull('channel')->get()->each(function (Conference $conference) {
            if (str($conference->channel)->startsWith('youtube:')) {
                $conference->channel = 'https://www.youtube.com/channel/' . explode(':', $conference->channel)[1];
                $conference->save();
            }

            if (str($conference->channel)->startsWith('vimeo:')) {
                $conference->channel = 'https://vimeo.com/channels/' . explode(':', $conference->channel)[1];
                $conference->save();
            }

            if (str($conference->channel)->startsWith('UC')) {
                $conference->channel = 'https://www.youtube.com/channel/' . $conference->channel;
                $conference->save();
            }
        });
    }
}
