<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Mail\CapitalWeekRemind;
use App\Services\CapitalStatisticService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DemoController extends Controller
{
    public function sendMail()
    {
//        $toMail = '1006897172@qq.com';
//        $title = '邮件Demo';
//        $content = '测试邮件';
//
//        Mail::raw($content, function ($message) use ($toMail, $title) {
//            $message->to($toMail)->subject($title);
//        });
    }
}
