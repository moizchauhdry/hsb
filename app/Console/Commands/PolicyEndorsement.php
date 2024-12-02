<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PolicyEndorsement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:policy-endorsement';

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
        DB::statement("UPDATE policies SET days_diff = DATEDIFF(policy_period_end, date_of_issuance)");

        DB::statement("
            UPDATE policies
            SET policy_type = 'endorsement'
            WHERE policy_no LIKE '%E%'
            AND DATEDIFF(policy_period_end, date_of_issuance) < 365;
        ");

        dump('POLICY ENDORSEMENT COMMAND => SUCCESS');
    }
}
