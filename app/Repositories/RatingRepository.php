<?php

namespace App\Repositories;

use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class RatingRepository
 * @package App\Repositories
 */
class RatingRepository
{
    /**
     * @var Rating $rating
     */
    protected $rating;

    /**
     * RatingRepository constructor.
     * @param Rating $rating
     */
    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveRating(Request $request)
    {
        return  DB::transaction(function () use ($request) {
           return $this->rating->create([
                'post_id' => $request->post_id,
                'rating' => $request->rating
            ]);
        });
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function findRatingByPostId(Request $request)
    {
        return DB::selectOne('select  to_char(AVG (rating), \'9.9\') AS rating 
            from rating where post_id = :post_id', ['post_id' => $request->post_id]
        );
    }
}
