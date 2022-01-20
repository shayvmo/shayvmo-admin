<?php

namespace App\Http\Controllers\Install;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    private $env_path;
    private $env_example_path;
    private $lock_file_path;

    private $allow_keys = [
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
        'DB_PREFIX',
    ];

    public function __construct()
    {
        $this->env_path = base_path('.env');
        $this->env_example_path = base_path('.env.example');
        $this->lock_file_path = storage_path('app/install.lock');
    }

    public function index()
    {
        if (File::exists($this->lock_file_path)) {
            abort(403, '已安装，无需再次操作');
        }

        if (!File::exists($this->env_path)) {
            if (!File::copy($this->env_example_path, $this->env_path)) {
                abort(500, '复制env文件失败');
            }
        }

        $content_array = collect(file($this->env_path, FILE_IGNORE_NEW_LINES));
        $content_array->transform(function ($item) use (&$env_array) {
            if ($item) {
                @list($key, $value) = explode('=', $item);
                $env_array[$key] = $value ?? '';
            }
        });

        $allow_keys = $this->allow_keys;
        $env_array = array_filter($env_array, static function ($k) use ($allow_keys) {
            if (in_array($k, $allow_keys, true)) {
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_KEY);

        return view('install', ['env_array' => $env_array]);
    }

    public function save(Request $request)
    {
        $config_data = $request->input();
        $content_array = collect(file($this->env_path, FILE_IGNORE_NEW_LINES));
        $allow_keys = $this->allow_keys;
        $content_array->transform(function ($item) use ($config_data, $allow_keys) {
            foreach ($config_data as $key => $value) {
                if (str_contains($item, strtoupper($key).'=') && in_array(strtoupper($key), $allow_keys, true)) {
                    return strtoupper($key) . '=' . $value;
                }
            }
            return $item;
        });
        if (strpos(PHP_VERSION, '7.4') === 0) {
            // 7.4 版本有兼容性问题
            $content = implode("\n", $content_array->toArray());
        } else {
            $content = implode($content_array->toArray(), "\n");
        }

        try {
            if (!File::put($this->env_path, $content)) {
                throw new \Exception('写入配置文件异常');
            }
            if (!Storage::disk('local')->put('install.lock', '安装更新配置于：' . Carbon::now())) {
                throw new \Exception('写入锁文件失败');
            }

            return response()->json([
                'code' => 0,
                'msg' => '保存成功',
                'data' => [],
            ]);
        } catch (\Exception | \Throwable $exception) {
            File::delete($this->lock_file_path);
            return response()->json([
                'code' => 1,
                'msg' => $exception->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function execute()
    {
        try {
            Artisan::call('system:init');
            return response()->json([
                'code' => 0,
                'msg' => '安装成功',
                'data' => [],
            ]);
        } catch (\Exception | \Throwable $exception) {
            return response()->json([
                'code' => 1,
                'msg' => $exception->getMessage(),
                'data' => [],
            ]);
        }

    }
}
