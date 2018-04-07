<?php

namespace App\Listeners;

use App\Events\JobCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogNewJob
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
     * @param  JobCreated  $event
     * @return void
     */
    public function handle(JobCreated $event)
    {
        info('createJob.mutation', $event->job->toArray());
    }
}
