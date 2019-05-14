<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * Table name
     * @var string $table
     */
    protected $table = 'rating';

    /**
     * Off should be timestamped
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['post_id', 'rating'];
}
