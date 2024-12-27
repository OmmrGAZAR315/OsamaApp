<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:reset';
    protected $description = 'Reset subscriptions if subscription_date is older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::where("subscription_date", "<=", Carbon::now()->subDay(30))
            ->where('subscription', 1)
            ->update([
                'subscription' => 0
            ]);
        $this->info('Expired subscriptions have been reset!');
    }
}
