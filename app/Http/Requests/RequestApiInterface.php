<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

/**
 * Interface RequestApiInterface
 * @package App\Services\RequestApiInterface
 */
interface RequestApiInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize();

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules();

    /**
     * @param Request $request
     * @return mixed
     */
    public function validate(Request $request);
}
