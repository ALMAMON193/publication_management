<?php

namespace App\Console\Commands;

use App\Models\UserMembership as ModelsUserMembership;
use Illuminate\Console\Command;

class UserMembership extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:membership';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove pending request and membership end date expired date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ModelsUserMembership::where('end_date', '<=', now())->delete();
        ModelsUserMembership::where('end_date', '<=', now()->addSeconds(1))->update(['status' => 'pending']);
        $this->info('Pending request and membership end date expired date removed.');
    }
}
