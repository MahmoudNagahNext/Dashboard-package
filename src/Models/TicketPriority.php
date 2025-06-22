<?php

namespace nextdev\nextdashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class TicketPriority extends Model
{
    use HasTranslations;
    protected $fillable = ['name'];
    public array $translatable = ['name'];
}
