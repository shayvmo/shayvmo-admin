<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ServiceException;
use App\Http\Requests\Backend\ConfigPostRequest;
use App\Models\ConfigGroup;
use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index()
    {
        $groups = ConfigGroup::with('configs')->orderBy('sort', 'DESC')->get()->toArray();
        foreach ($groups as &$group) {
            foreach ($group['configs'] as &$config) {
                if ($config['type'] === 'switch') {
                    $config['val'] = (int)$config['val'];
                }
            }
        }
        return view('admin.config.index',compact('groups'));
    }

    public function data()
    {
        $groups = ConfigGroup::with('configs')->orderBy('sort', 'DESC')->get()->toArray();
        foreach ($groups as &$group) {
            foreach ($group['configs'] as &$config) {
                if ($config['type'] === 'switch') {
                    $config['val'] = (int)$config['val'];
                }
            }
        }
        return $this->successData(compact('groups'));
    }

    public function store(ConfigPostRequest $request)
    {
        $data = $request->fillData();
        $config = Config::create($data);
        if (!$config) {
            return $this->error('新增失败');
        }
        return $this->success();
    }

    public function update(Request $request)
    {
        $data = $request->post('data');
        if (empty($data)) {
            throw new ServiceException('参数异常');
        }
        $data = array_column($data, NULL, 'id');
        $configs = Config::whereIn('id', array_keys($data))->get();
        if (!$configs) {
            throw new ServiceException('未找到配置项');
        }
        \DB::transaction(function () use ($configs, $data) {
            foreach ($configs as $config) {
                if (isset($data[$config->id]['val'])) {
                    $config->val = $data[$config->id]['val'];
                    $config->save();
                }
            }
        });
        return $this->success();
    }

}
