<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
     /**
     * Model attached to the table
     */
    protected $table = 'films';
    
    /**
     * Get all films that use a specific user
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'film_id');
    }

}
