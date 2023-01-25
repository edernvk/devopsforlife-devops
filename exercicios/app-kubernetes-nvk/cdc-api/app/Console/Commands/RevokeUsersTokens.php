<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

// copied from new laravel 6.0 passport version
use Laravel\Passport\AuthCode;
use Laravel\Passport\Token;
//use Laravel\Passport\RefreshToken;

class RevokeUsersTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:tokens:revokeAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::with('tokens')->get()->each(function ($user) {
            $user->tokens->each(function ($token, $key) use ($user) {
                if (!$token->revoked) {
                    if (!$token->revoke()) {
                        $this->info('Error revoking token! | userId: ' . $user->id . ' | token: ' . $token->id);
                    } else {
                        $this->info('userId: '.$user->id . ' | token revoked: ' . $token->id);
                    }
                }
            });
        });

        // copied from new laravel 6.0 passport version
        $now = Carbon::now();
        Token::where('revoked', 1)->orWhereDate('expires_at', '<', $now)->delete();
        AuthCode::where('revoked', 1)->orWhereDate('expires_at', '<', $now)->delete();
//        RefreshToken::where('revoked', 1)->orWhereDate('expires_at', '<', $now)->delete();

        $this->info('Purged revoked/expired tokens');


        return true;
    }
}
