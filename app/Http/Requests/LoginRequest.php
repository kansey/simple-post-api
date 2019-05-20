<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

/**
 * Class LoginRequest
 * @package App\Http\Requests
 */
class LoginRequest implements RequestApiInterface
{
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
            'login' => 'required||string|min:5|max:20'
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
