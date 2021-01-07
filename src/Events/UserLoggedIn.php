<?php

namespace Laravel\Ui\Events;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

class UserLoggedIn extends Event
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param  Authenticatable  $user
     * @return void
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }
}
