<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    use HasFactory;
 /**
  *      * The attributes that are mass assignable.
  *           *
  *                * @var array
  *                     */
        protected $fillable = [
		        'type',
			        'title',
				        'price',
					        'spare',
						    ];
}
