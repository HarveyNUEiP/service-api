<?php

namespace App\Console\Commands;

use App\Services\HrmSyncServiceManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SyncUsers extends Command
{
    public function __construct(
        protected HrmSyncServiceManager $hrmSyncServiceManager
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Annaul synchronize users from HRM.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Do you want to synchronize users from HRM? (yes/no)')) {
            try {
                $this->alert('Start synchronizing users from HRM...');
                Log::info('Start synchronizing users from HRM');

                $this->hrmSyncServiceManager->syncAccount();

                $this->comment('Synchronization completed');
                Log::info('Synchronization completed');
            } catch (\Exception $e) {
                $this->error('Synchronization failed.');
            }
        } else {
            $this->line('Synchronization is cancelled');
        }
    }
}
