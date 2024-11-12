<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    
    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;
    
    protected $fillable = ['filename', 'order'];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
    


}