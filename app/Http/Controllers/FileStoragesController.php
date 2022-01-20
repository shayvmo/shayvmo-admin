<?php

namespace App\Http\Controllers;

use App\Exceptions\ServiceException;
use App\Http\Requests\Backend\AttachmentListRequest;
use App\Models\Attachment;
use App\Models\AttachmentGroup;
use App\Services\Base\CommonService;
use App\Services\Base\UploadService;
use Illuminate\Http\Request;

class FileStoragesController extends Controller
{
	public function upload(Request $request)
	{
        $service = new UploadService();
        $data = $service->uploadFile($request);
        if ($request->from && $request->from=='wangEditor') {
            foreach ($data as &$item) {
                $item = [
                    'url' => $item['path'],
                    'alt' => '',
                    'href' => $item['path'],
                ];
            }
            return response()->json([
                "errno" => 0,
                "msg"  => '文件上传成功！',
                "data" => $data,
            ]);
        }
        return $this->successData($data);
	}

	public function groups()
    {
        $groups = AttachmentGroup::query()->select([
            'id', 'title'
        ])->get()->toArray();
        return $this->successData($groups);
    }

    public function saveGroup(Request $request)
    {
        $id = $request->post('id', 0);
        $title = $request->post('name');
        if (!$title) {
            throw new ServiceException('参数错误');
        }
        $group = $id ? AttachmentGroup::where(['id' => $id])->first() : new AttachmentGroup();
        $group->fill(compact('title'))->saveOrFail();
        return $this->successData($group->toArray());
    }

    public function deleteGroup(AttachmentGroup $attachmentGroup)
    {
        $attachmentGroup->attachments()->update(['group_id' => 0]);
        $attachmentGroup->delete();
        return $this->success();
    }

    public function moveToGroup(Request $request)
    {
        [
            'ids' => $ids,
            'attachment_group_id' => $attachment_group_id,
        ] = $request->post();
        if (!$ids || !$attachment_group_id) {
            throw new ServiceException('参数错误');
        }
        $group = AttachmentGroup::query()->find($attachment_group_id);
        $attachments = Attachment::query()->whereIn('id', $ids)->get();
        $group && $attachments && $group->attachments()->saveMany($attachments);
        return $this->successData(compact('ids', 'attachment_group_id'));
    }

    public function list(AttachmentListRequest $request)
    {
        [
            'page_size' => $page_size,
            'keyword' => $keyword,
            'attachment_group_id' => $attachment_group_id,
        ] = $request->fillData();
        $data = Attachment::query()
            ->when($keyword, function ($query, $keyword) {
                return $query
                    ->where('name', 'like', "%$keyword%");
            })
            ->when($attachment_group_id, function ($query, $attachment_group_id) {
                return $query->where(['group_id'=> $attachment_group_id]);
            })
            ->orderBy('id','DESC')
            ->paginate($page_size)->toArray();
        $data = CommonService::changePageDataFormat($data);
        return $this->successData($data);
    }

    public function destroy(Request $request)
    {
        $ids = $request->post('ids', []);
        if (!$ids) {
            throw new ServiceException('参数错误');
        }
        Attachment::destroy($ids);
        return $this->success();
    }
}
