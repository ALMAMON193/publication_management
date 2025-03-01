<?php

namespace App\Console\Commands;

use App\Models\Payment as ModelsPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Payment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove pending payments older than 15 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thresholdDate = Carbon::now()->subMinutes(15);
        $deleted = ModelsPayment::where('status', 'pending')
            ->where('created_at', '<', $thresholdDate)
            ->delete();

        $this->info("$deleted pending payments removed.");
    }
}
