<?php
if (!function_exists('GetIP')) {
    /**
     * 获取访问用户ip
     */
    function GetIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

/**
 * 字符串两次md5加密
 * @param string $str 要加密的字符串
 * @return string
 */
function double_md5(string $str)
{

    return md5(md5(trim($str)));
}

/**
 * 返回可读性更好的文件尺寸
 * @param string $bytes 原字符串
 * @param int $decimals 保留长度
 * @return string
 */
function human_filesize(string $bytes, $decimals = 2)
{
    $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

/**
 * 时间戳转换
 * @param $time
 * @return string
 */
function timeToBefore(int $time)
{
    $t = time() - $time;
    $f = array('31536000' => '年', '2592000' => '个月', '604800' => '星期', '86400' => '天', '3600' => '小时', '60' => '分钟', '1' => '秒');
    foreach ($f as $k => $v) {
        if (0 != $c = floor($t / (int)$k)) {
            return $c . $v . '前';
        }
    }
    return '刚刚';
}

/**
 * 计算两日期相差天数
 * @param string $endTime 结束时间
 * @param string $startTime 开始时间
 * @param int $flag 传入日期格式(0-时间戳,1-日期格式)
 * @return false|float
 */
function calDifferentDay($endTime = '', $startTime = '', $flag = 1)
{
    //转换为天，取出时分秒
    $startTime = ($startTime == '') ? date('Y-m-d H:i:s', time()) : $startTime;
    $endTime = ($endTime == '') ? date('Y-m-d H:i:s', time()) : $endTime;
    if ($flag) {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
    }
    $startTime = floor($startTime / 86400);
    $endTime = floor($endTime / 86400);
    return $endTime - $startTime;
}

/**
 * 隐藏手机号
 * @param string|int $str 手机号码
 * @param int $start 开始位置，从0开始
 * @param int $length 隐藏长度
 * @return bool|string|string[]
 */
function hidePhone($str, int $start = 3, int $length = 4)
{
    //获取最后一位
    $end = $start + $length;
    //判断传参是否正确
    if ($start < 0 || $end > 11) return false;
    $replace = ''; //用于判断多少
    for ($i = 0; $i < $length; $i++) $replace .= '*';
    return substr_replace($str, $replace, $start, $length);
}

/**
 * @param string $url 请求的url
 * @param string $type 请求类型
 * @param string $res 返回数据类型
 * @param string $arr post请求参数
 * @return mixed
 */
function http_curl(string $url, $type = 'get', $res = 'json', $arr = '')
{
    //1. 初始化 curl
    $ch = curl_init();
    //2. 设置 curl 的参数
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if ($type == 'post') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
    }
    //3. 采集
    $output = curl_exec($ch);
    //4. 关闭
    curl_close($ch);
    if ($res == 'json') {
        return json_decode($output, true);
    } elseif (curl_errno($ch)) {
        return (curl_errno($ch));
    } else {
        return null;
    }
}

if (!function_exists('check_chinese_text')) {
    /**
     * 校验是否包含文字
     * @param string $string
     * @param bool $filter 是否过滤，是则返回过滤后的字符串
     * @return bool|string|string[]|null
     */
    function check_chinese_text(string $string, $filter = false)
    {
        $pattern = '/[\\x80-\\xff]/';
        if (preg_match($pattern, $string)) {
            if ($filter) {
                return preg_replace($pattern, '', $string);
            }
            return true;
        }
        return false;
    }
}

if (!function_exists('get_data_tree')) {
    /**
     * 生成树形数组
     * @param $arr
     * @param string $auto_id_name
     * @param string $parent_id_name
     * @param string $children_name
     * @return array
     */
    function get_data_tree($arr, $auto_id_name = 'id', $parent_id_name = 'pid', $children_name = 'children'): array
    {
        $refer = array();
        $tree = array();
        foreach ($arr as $k => $v) {
            $refer[$v[$auto_id_name]] = &$arr[$k]; //创建主键的数组引用
        }
        foreach ($arr as $k => $v) {
            $pid = $v[$parent_id_name];  //获取当前分类的父级id
            if ($pid == 0) {
                $tree[] = &$arr[$k];  //顶级栏目
            } else {
                if (isset($refer[$pid])) {
                    $refer[$pid][$children_name][] = &$arr[$k]; //如果存在父级栏目，则添加进父级栏目的子栏目数组中
                }
            }
        }
        return $tree;
    }
}

if (!function_exists('check_mobile')) {
    /**
     * 校验是否为有效手机号
     * @param $string
     * @return bool
     */
    function check_mobile($string): bool
    {
        return preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|6[6]|7[0135678]|8[0-9]|99)\d{8}$/', $string);
    }
}


if (!function_exists('get_mock_mobile')) {
    /**
     * 获取模拟的手机号
     * @return string
     * @throws Exception
     */
    function get_mock_mobile(): string
    {
        return '1' . random_int(30, 39) . random_int(1000, 9999) . random_int(1000, 9999);
    }
}

if (!function_exists('generate_code')) {
    /**
     * 生成随机码
     * @param int $length 长度
     * @param array $type 码包含类型，number 数字 special 特殊字符 lower 小写 upper 大写
     * @return string
     */
    function generate_code(int $length = 16, array $type = ['number', 'special', 'lower', 'upper']): string
    {
        $source_string = [
            'lower' => str_shuffle('abcdefghijklmnopqrstuvwxyz'),
            'upper' => str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),
            'number' => str_shuffle('0123456789'),
            'special' => str_shuffle('!@#$%^&*'),
        ];

        if (empty($type)) {
            return '';
        }

        $parts = ceil($length / count($type));

        $temp = array_map(function ($item) use ($parts, $source_string) {
            if (isset($source_string[$item])) {
                $temp_string = '';
                do {
                    $temp_string .= substr(str_shuffle($source_string[$item]), 0, $parts);
                } while (strlen($temp_string) < $parts);
                return substr($temp_string, 0, $parts);
            }
            return substr(str_shuffle(
                $source_string['lower']
                . $source_string['number']
                . $source_string['upper']
                . $source_string['special']), 0, $parts);
        }, $type);

        return str_shuffle(substr(implode('', $temp), 0, $length));
    }
}

if (!function_exists('get_date_moments_format')) {
    /**
     * @Description 获取类似微信朋友圈时间格式
     * @Author shayvmo
     * @Date 2021/5/13 14:58
     * @param int|string $create_time 待转换时间字符串 Y-m-d H:i:s 或者是 时间戳int
     * @return false|string
     */
    function get_date_moments_format($create_time)
    {
        if (is_string($create_time)) {
            $create_time = strtotime($create_time);
        }
        if ($create_time === false) {
            return false;
        }
        $now_time = time();
        if (date("Y", $now_time) === date("Y", $create_time)) {
            $time = $now_time - $create_time;
            if ($time < 60) {
                return "刚刚";
            }

            $sec = $time / 60;
            if ($sec < 60) {
                return round($sec) . "分钟前";
            }

            $hours = $time / 3600;
            if ($hours < 48) {

                if (date('Ymd', $create_time) + 1 === (int)date('Ymd', $now_time)) {
                    return "昨天 " . date("H:i", $create_time);
                } elseif ($hours < 24) {
                    return round($hours) . "小时前";
                }
            }

            return date("m月d日 H:i", $create_time);
        } else {
            return date("Y年m月d日 H:i", $create_time);
        }
    }
}

if (!function_exists('get_distance')) {
    /**
     * @Description 求两个已知经纬度之间的距离,单位为米
     * @Author shayvmo
     * @Date 2021/6/22 12:08
     * @param float $lng1 位置1经度
     * @param float $lat1 位置1纬度
     * @param float $lng2 位置2经度
     * @param float $lat2 位置2纬度
     * @return float 距离，单位米
     */
    function get_distance(float $lng1, float $lat1, float $lng2, float $lat2): float
    {
        // 将角度转为狐度
        $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        return 2 * asin(sqrt(sin($a / 2) ** 2 + cos($radLat1) * cos($radLat2) * (sin($b / 2) ** 2))) * 6378.137 * 1000;
    }
}

if (!function_exists('mysql_timestamp')) {
    /**
     * 生成mysql数据库时间戳（eg. 2000-01-01 12:00:00）
     * @param integer $time
     * @return false|string
     */
    function mysql_timestamp($time = null)
    {
        return date('Y-m-d H:i:s', $time ?: time());
    }
}
