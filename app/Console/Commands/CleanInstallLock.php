<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanInstallLock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clean installed lock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lock_path = storage_path('app/install.lock');
        File::exists($lock_path) && File::delete($lock_path);
        $this->info('清理安装锁成功!');
    }
}
