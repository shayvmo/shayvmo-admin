<?php


namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;
use GatewayClient\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GatewayController extends Controller
{
    public function receive()
    {
        return view('home.gateway.receive');
    }

    public function send(Request $request)
    {
        if ($request->isMethod('post')) {
            $uid = Session::get('uid');
            $message = $request->post('content') ?: '没说话';
            Gateway::$registerAddress = '127.0.0.1:1236';
            Gateway::sendToUid($uid, json_encode(compact('message')));
            return $this->successData(compact('message', 'uid'));
        }
        return view('home.gateway.send');
    }

    public function bind(Request $request)
    {
        $client_id = $request->post('client_id');
        Gateway::$registerAddress = '127.0.0.1:1236';
        $uid = 1;
        Session::put('uid', $uid);
        Gateway::bindUid($client_id, $uid);
        return $this->successData(compact('uid', 'client_id'));
    }
}
