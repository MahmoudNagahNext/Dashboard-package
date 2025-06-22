<?php

namespace nextdev\nextdashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TicketStatus extends Model
{
    use HasTranslations;
    protected $fillable = ['name'];
    public array $translatable = ['name'];
}
