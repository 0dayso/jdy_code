<?php

namespace App\Jobs;

class OrderNoticeJob extends Job
{
    protected $data = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        var_dump('abc', $this->data);
    }
}
