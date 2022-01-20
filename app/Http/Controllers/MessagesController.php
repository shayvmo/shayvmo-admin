<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class MessagesController extends Controller
{
    /**
     * 数据接口
     * @param Request $request
     * @return JsonResponse
     */
    public function panel(Request $request)
    {
        $notificationArr = [];
        $alertArr = [];
        $messageArr = [];
        foreach ($request->user()->unreadNotifications as $notification) {
            // 这里可根据类型不同，分组通知
            $notificationArr[] = array_merge(['id' => $notification->id], $notification->data);
        }
        $data = [
            [
                'id' => 1,
                'title' => '通知',
                'children' => $notificationArr,
            ],
            [
                'id' => 2,
                'title' => '提醒',
                'children' => $alertArr,
            ],
            [
                'id' => 3,
                'title' => '留言',
                'children' => $messageArr,
            ],
        ];
        return Response::json($data);
    }

    public function read(Request $request, $id)
    {
        $message = [
            'time' => '',
            'avatar' => '',
            'title' => '',
            'content' => '',
        ];
        $user = $request->user();
        foreach ($request->user()->unreadNotifications as $notification) {
            if ($notification->id === $id) {
                $notification->markAsRead();
                $message = array_merge($message, $notification->data);
                break;
            }
        }
        return view('admin.message.show', compact('message', 'user'));
    }
}
