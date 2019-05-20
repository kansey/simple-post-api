<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'id', 'post_id');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveRating(Request $request)
    {
        $data = [
            'post_id' => $request->post_id,
            'rating' => $request->rating
        ];

        return  \DB::transaction(function () use ($data) {
           $this->create($data);
        });
    }

    /**
     * @param $id
     * @return array
     */
    public function getPostRating($id)
    {
        return DB::selectOne('select  to_char(AVG (rating), \'9.9\') AS rating 
                from rating where post_id = :post_id', ['post_id' => $id]
        );
    }
}
