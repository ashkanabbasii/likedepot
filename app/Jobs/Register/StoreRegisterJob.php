<?php

namespace App\Jobs\Register;

use App\Library\Helper\Helper;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StoreRegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = new User();

        foreach ($this->data as $key => $value) {
            $user[$key] = $value;
        }

        $user->type = "User";
        $user->wallet = 0;
        $user->status = 1;
        $user->save();

        return $user;
    }




}
