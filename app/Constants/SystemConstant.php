<?php


namespace App\Constants;

class SystemConstant
{
    public const SYSTEM_ERROR = '系统内部错误，请联系管理员！';

    public const MODEL_NOT_FOUND_MSG = '该数据不存在！';

    public const LOGIN_EXPIRE_OR_NOT_LOGIN = '未登录或登录已过期';

    public const SYSTEM_MAINTAINING_MSG = '系统维护中';

    public const BAN_LOGIN = '当前账号禁止登录！';

    public const ERROR_ACCOUNT = '账号或密码错误！';

    public const LOGOUT_SUCCESS = '退出成功！';

    public const CANT_EDIT_SYSTEM_ITEM = '禁止操作系统保留账号';

    public const DELETE_FAILED= '删除失败';

    public const DEFAULT_PAGE = 1;

    public const DEFAULT_PAGE_SIZE = 10;

    public const WRONG_PASSWORD = '原密码错误！';
}
