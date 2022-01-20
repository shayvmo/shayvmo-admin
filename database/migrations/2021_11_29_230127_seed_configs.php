<?php

use Illuminate\Database\Migrations\Migration;

class SeedConfigs extends Migration
{
    private $configArr;

    private $configGroups;

    private $configs;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->configArr = config('configs', []);

        if ($this->configArr) {
            $this->saveConfig($this->configArr);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->configArr = config('configs', []);

        if ($this->configArr) {
            $groups = \App\Models\ConfigGroup::whereIn('group_key', array_column($this->configArr, 'group_key'))->pluck('id');
            \App\Models\ConfigGroup::destroy($groups);
            \App\Models\Config::whereIn('group_id', $groups)->delete();
        }
    }

    private function saveConfig(array $configs)
    {
        foreach ($configs as $item) {
            $group = \App\Models\ConfigGroup::create([
                'group_key' => $item['group_key'],
                'name' => $item['name'],
                'sort' => $item['sort'],
            ]);
            if (!empty($item['children'])) {
                $group->configs()->createMany($item['children']);
            }
        }

    }
}
