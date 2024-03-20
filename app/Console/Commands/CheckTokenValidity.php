<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Auth\User;

class CheckTokenValidity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:check-token-validity';
    protected $signature = 'token:check {token}';
    

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Check the validity of a token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $token = $this->argument('token');
        $user = User::where('api_token', $token)->first();

        if ($user) {
            $this->info('Token is valid');
        } else {
            $this->error('Token is invalid');
        }
    }
}
