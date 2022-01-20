<?php

namespace App\Providers;

use App\Http\Validators\ChineseTextValidator;
use App\Http\Validators\HashValidator;
use App\Http\Validators\IdNumberValidator;
use App\Http\Validators\PhoneValidator;
use App\Http\Validators\UsernameValidator;
use App\Models\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Krlove\EloquentModelGenerator\Provider\GeneratorServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $validators = [
        'phone' => PhoneValidator::class,
        'id_no' => IdNumberValidator::class,
        'hash' => HashValidator::class,
        'username' => UsernameValidator::class,
        'chinese' => ChineseTextValidator::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(GeneratorServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });

        // 注册验证器
        $this->registerValidators();

        // 打印sql log
        $this->dumpSqlLog();

        // 同步数据库配置
        if (!$this->app->runningInConsole() && Schema::hasTable((new Config())->getTable())) {
            $configs = Config::all();
            foreach ($configs as $config) {
                if ($config->config_file_key === '') {
                    continue;
                }
                \config([$config->config_file_key => $config->val]);
            }
        }

    }

    private function registerValidators()
    {
        foreach ($this->validators as $rule => $validator) {
            Validator::extend($rule, "{$validator}@validate");
        }
    }

    private function dumpSqlLog()
    {
        DB::listen(
            function ($sql) {
                foreach ($sql->bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }

                // Insert bindings into query
                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);

                $query = vsprintf($query, $sql->bindings);

                // Save the query to file
                $logFile = fopen(
                    storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m-d-H') . '-query.log'),
                    'a+'
                );
                fwrite($logFile, date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL . PHP_EOL);
                fclose($logFile);
            }
        );
    }
}
