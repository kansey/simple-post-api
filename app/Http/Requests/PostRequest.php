<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Validator;

/**
 * Class PostRequest
 * @package App\Http\Requests
 */
class PostRequest implements RequestApiInterface
{
    /**
     * Message for response errors and empty data
     */
    const EMPTY_DATA_MESSAGE = 'Request returned empty data';

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
            'title' => 'required|string|min:1|max:255',
            'content' => 'required|string|min:1',
            'author_ip' => 'required|ipv4'
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
