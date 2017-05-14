<?php

namespace App\Listeners\Auth;

use DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Passport\Events\RefreshTokenCreated;

class PruneOldTokens
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RefreshTokenCreated  $event
     * @return void
     */
    public function handle(RefreshTokenCreated $event)
    {
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', '!=', $event->accessTokenId)
            ->where('revoked', true)->delete();
    }
}
