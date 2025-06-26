<?php

namespace nextdev\nextdashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TicketReply extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = ['ticket_id', 'admin_id', 'body'];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
