<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\RenewalNotification;
use Illuminate\Console\Command;

class RenewalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:renewal-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**`
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $users = User::query()
                ->where('email','!=', NULL)
                ->withoutRole('client')
                ->get();

            foreach ($users as $key => $user) {
                $notification = new RenewalNotification($user);
                $user->notify($notification);
            }

        } catch (\Throwable $th) {
            // dd($th);
            throw $th;
        }
    }
}
