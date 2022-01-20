<?php


namespace App\Http\Controllers\Admin;


use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Models\AttachmentGroup;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    // 素材列表
    public function index()
    {

    }

    // 素材移动操作
    public function move()
    {

    }

    // 分组
    public function groups(Request $request)
    {
        [
            'keyword' => $keyword
        ] = $request->all();
        $query = AttachmentGroup::query();
        $keyword && $query->whereLike('title', $keyword);
        $data = $query->all();
        return $data;
    }

    // 添加分组
    public function groupsAdd(Request $request)
    {
        [
            'title' => $title
        ] = $request->all();
        if (empty($title)) {
            throw new ServiceException('参数错误');
        }
        AttachmentGroup::create(compact('title'));
        return $this->success();
    }
}
