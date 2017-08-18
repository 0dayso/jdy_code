<?php

namespace App\Http\Requests\Admin\Credit;

use App\Http\Requests\Request;

/**
 * 创建债权基类
 * Class CreateRequest
 * @package App\Http\Requests\Admin\Credit
 */
abstract class CreateRequest extends Request
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
}