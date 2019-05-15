<?php
/**
 * Created by PhpStorm.
 * User: kansey
 * Date: 15.05.19
 * Time: 17:22
 */

namespace App\Http\Response;

/**
 * Class ResponseCode
 * @package App\Http\Response
 */
class ResponseCode
{
    /**
     * @var int $ok
     */
    public $ok = 200;

    /**
     * @var int $unprocessableEntity
     */
    public $unprocessableEntity = 422;
}
