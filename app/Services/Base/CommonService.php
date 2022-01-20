<?php


namespace App\Services\Base;


use App\Exceptions\ValidateException;
use App\Models\Config;
use App\Models\RequestLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class CommonService
{
    /**
     * 校验
     * @param array $params
     * @param array $rules
     * @return bool
     * @throws ValidateException
     */
    public static function validate(array $params, array $rules): bool
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
        $validator = Validator::make($params, $rules);
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

    /**
     * 请求日志
     * @param array $params
     */
    public static function addRequestLog(array $params): void
    {
        $demo_params = [
            'admin_id',
            'id_address',
            'url',
            'route_name',
            'user_agent',
            'param',
            'method',
        ];
        RequestLog::query()->create($params);
    }
}
