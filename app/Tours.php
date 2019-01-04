<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tours extends Model
{


	/**
     * Get the images for the tour.
     */
    public function images()
    {
        return $this->hasMany('App\Images', 'tour_id', 'id')->get();
    }

}
