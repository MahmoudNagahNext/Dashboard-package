<?php

namespace nextdev\nextdashboard\Models;

use Illuminate\Database\Eloquent\Model;
use nextdev\nextdashboard\MediaLibrary\PathGenerators\TicketPathGenerator;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ticket extends Model implements HasMedia
{
    use InteractsWithMedia;
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

    //  public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('attachments')
    //         ->useDisk('public')
    //         ->usePathGenerator(new TicketPathGenerator());
    // }


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
