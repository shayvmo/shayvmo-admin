<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call("key:generate", ['--ansi' => true]);
        $this->call("migrate", ['--seed' => true]);
        $this->call("storage:link");
    }
}
