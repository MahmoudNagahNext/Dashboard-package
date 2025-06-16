<?php

namespace nextdev\nextdashboard\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    protected $hidden = [
        'password',
    ];

    public function createdTickets()
    {
        return $this->morphMany(Ticket::class,'creator_type');
    }

    public function assigneeTickets()
    {
        return $this->morphMany(Ticket::class,'assignee_type');
    }
}
