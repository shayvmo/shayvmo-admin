<?php


namespace App\Http\Requests;

use App\Constants\SystemConstant;

/**
 * 分页请求
 * Class PagePost
 * @package App\Http\Requests
 */
class PagePost extends BaseRequest
{
    public function rules()
    {
        return [
            'page'=>[
                'sometimes',
                'integer',
                'between:1,1000',
            ],
            'page_size'=>[
                'sometimes',
                'integer',
                'between:2,50',
            ],
        ];
    }

    public function fillData()
    {
        return [
            'page' => (int)$this->get('page', SystemConstant::DEFAULT_PAGE),
            'page_size' => (int)$this->get('page_size', SystemConstant::DEFAULT_PAGE_SIZE),
        ];
    }
}
