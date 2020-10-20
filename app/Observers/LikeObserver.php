<?php

namespace App\Observers;

use App\YourEdu\Like;

class LikeObserver
{
    /**
     * Handle the like "created" event.
     *
     * @param  \App\YourEdu\Like  $like
     * @return void
     */
    public function created(Like $like)
    {
        //
    }

    /**
     * Handle the like "updated" event.
     *
     * @param  \App\YourEdu\Like  $like
     * @return void
     */
    public function updated(Like $like)
    {
        //
    }

    /**
     * Handle the like "deleted" event.
     *
     * @param  \App\YourEdu\Like  $like
     * @return void
     */
    public function deleted(Like $like)
    {
        //
    }

    /**
     * Handle the like "restored" event.
     *
     * @param  \App\YourEdu\Like  $like
     * @return void
     */
    public function restored(Like $like)
    {
        //
    }

    /**
     * Handle the like "force deleted" event.
     *
     * @param  \App\YourEdu\Like  $like
     * @return void
     */
    public function forceDeleted(Like $like)
    {
        //
    }
}
