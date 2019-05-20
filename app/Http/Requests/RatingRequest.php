<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Validator;

/**
 * Class RatingRequest
 * @package App\Http\Requests
 */
class RatingRequest implements RequestApiInterface
{
    const MESSAGE = 'Failed to update data';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'post_id' => 'required|integer|exists:post,id',
            'rating' => 'required|integer|between:1,5',
        ];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function validate(Request $request)
    {
        $validate =  Validator::make($request->all(), $this->rules());

        return $validate;
    }
}
