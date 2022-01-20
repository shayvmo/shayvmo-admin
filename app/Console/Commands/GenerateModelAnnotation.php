<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateModelAnnotation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gma {table_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate model property without prefix. eg: gma admins';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $table_name  = config('database.connections.mysql.prefix').$this->argument('table_name');
        $table_schema     = config('database.connections.mysql.database');
        $columns = DB::select(
            "SELECT COLUMN_NAME, DATA_TYPE , COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ? AND table_schema = ?",
            [$table_name, $table_schema]
        );
        if (!$columns) {
            $this->error('未找到该数据表！');
            return;
        }
        $annotation = "";
        foreach ($columns as $column) {
            $type = 'string';
            if (in_array($column->DATA_TYPE, ['int', 'tinyint', 'smallint', 'mediumint', 'bigint'])) {
                $type = 'int';
            } elseif (in_array($column->DATA_TYPE, ['float', 'double', 'decimal'])) {
                $type = 'float';
            }
            $columnName = $column->COLUMN_NAME;
            if (in_array($columnName, ['created_at', 'updated_at', 'deleted_at'])) {
                $type = '\\Carbon\\Carbon';
            }
            $columnComment = $column->COLUMN_COMMENT;
            $annotation    .= sprintf("\n * @property %s \$%s  %s", $type, $columnName, $columnComment);
        }
        $annotation .= "\n";
        echo($annotation);
    }
}
