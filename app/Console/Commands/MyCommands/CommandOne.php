<?php

namespace App\Console\Commands\MyCommands;

use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

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
    public function handle(Request $request)
    {

        $user = $request->user();
        dd($user);
        dd($user);
        $foo = Survey::where('user_id', $user)->count();
        dd($foo);

        return;
    }
}
