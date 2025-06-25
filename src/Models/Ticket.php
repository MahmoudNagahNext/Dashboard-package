<?php

namespace nextdev\nextdashboard\Models;

use Illuminate\Database\Eloquent\Model;
use nextdev\nextdashboard\Enums\TicketPriorityEnum;
use nextdev\nextdashboard\Enums\TicketStatusEnum;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'category_id',
        'creator_type',
        'creator_id',
        'assignee_type',
        'assignee_id'
    ];

    protected $casts = [
        'status'   => TicketStatusEnum::class,
        'priority' => TicketPriorityEnum::class,
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
    
    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
