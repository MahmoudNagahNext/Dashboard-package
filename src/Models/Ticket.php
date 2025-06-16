<?php

namespace nextdev\nextdashboard\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status_id',
        'priority_id',
        'category_id',
        'creator_type',
        'creator_id',
        'assignee_type',
        'assignee_id'
    ];

    public function creator()      
    {
        return $this->morphTo();
    }

    public function assignee()
    {
        return $this->morphTo();
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class);
    }
}
