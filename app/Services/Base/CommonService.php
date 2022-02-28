<?php


namespace App\Services\Base;


use App\Exceptions\ValidateException;
use App\Models\RequestLog;
use Illuminate\Support\Facades\Validator;

class CommonService
{
    /**
     * 校验
     * @param array $params 待校验参数
     * @param array $rules 规则
     * @param array $message 提示消息
     * @return bool
     * @throws ValidateException
     */
    public static function validate(array $params, array $rules, array $message = []): bool
    {
        $rules_demo = [
            'username' => [
                'required',
                'alpha_num',
                'chinese',
                'between:5,18',
                'unique:admins,username',
            ],
        ];
        $message_demo = [
            'username.required' => '用户名必填'
        ];
        $validator = Validator::make($params, $rules, $message);
        if ($validator->fails()) {
            throw new ValidateException($validator->errors()->first());
        }
        return true;
    }

    /**
     * @Description 转换分页数据到指定格式
     * @Author shayvmo
     * @Date 2020/12/1 12:28
     * @param array $page_data
     * @return array
     */
    public static function changePageDataFormat(array $page_data): array
    {
        return [
            "total_page" => $page_data['last_page'],
            "current_page" => $page_data['current_page'],
            "per_page" => $page_data['per_page'],
            "total" => $page_data['total'],
            "data" => $page_data['data'],
        ];
    }
}
