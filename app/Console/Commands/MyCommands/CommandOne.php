<?php

namespace App\Console\Commands\MyCommands;

use App\Models\User;
use Illuminate\Console\Command;

class CommandOne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:command-one';

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
        $user = User::all();
        dd($user);
    }
}
