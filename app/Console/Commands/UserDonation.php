<?php

namespace App\Console\Commands;

use App\Models\UserDonation as ModelsUserDonation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UserDonation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:donation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove pending donations older than every 1 hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thresholdDate = Carbon::now()->subHours(1);
        $deleted = ModelsUserDonation::where('status', 'pending')
            ->where('created_at', '<', $thresholdDate)
            ->delete();

        $this->info("$deleted pending donations removed.");
    }
}
