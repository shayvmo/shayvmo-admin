<?php


namespace App\Http\Controllers\Api;


use App\Constants\FrontendConstant;
use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UserInfoUpdateRequest;
use App\Models\User;
use App\Services\Base\CommonService;
use App\Services\MiniProgramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class BasicController extends Controller
{
    public function miniAppLogin(Request $request)
    {
        [
            'code' => $code,
            'share_user_id' => $share_user_id,
        ] = $request->post();
        $rules = [
            'code' => [
                'required',
                'string',
            ],
        ];
        CommonService::validate($request->post(), $rules);
        $service = new MiniProgramService();
        [
            'session_key' => $session_key,
            'openid' => $openid,
        ] = $service->code2Session($code);
        $user = User::query()->where(['openid' => $openid])->first();
        if (!$user) {
            $user = User::create([
                'openid' => $openid,
                'password' => FrontendConstant::DEFAULT_PASSWORD,
            ]);
            $user->nickname = '用户'.random_int(1000, 9999);
        }
        $user->api_token = md5(generate_code().$openid);
        $user->session_key = $session_key;
        $user->save();
        return $this->successData([
            'id' => $user->id,
            'api_token' => $user->api_token,
            'avatar' => $user->avatar ?: '',
            'nickname' => $user->nickname,
        ]);
    }

    public function updateInfo(UserInfoUpdateRequest $request)
    {
        [
            'encryptedData' => $encryptedData,
            'iv' => $iv,
            'rawData' => $rawData,
            'signature' => $signature,
        ] = $request->fillData();
        $user = Auth::user();
        $session_key = $user->session_key;
        if (!$session_key) {
            throw new ServiceException('解析参数错误');
        }
        $service = new MiniProgramService();
        [
            'avatarUrl' => $user->avatar,
            'gender' => $user->gender,
            'nickName' => $user->nickname,
        ] = $service->decryptData($session_key, $iv, $encryptedData);
        $user->save();
        return $this->success();

    }

    public function index()
    {
        $user = Auth::user();
        return $this->successData([
            'id' => $user->id,
            'avatar_id' => 0,
            'avatar_url' => $user->avatar,
            'mobile' => $user->mobile,
            'nickname' => $user->nickname,
            'sex' => $user->gender,
            'status' => $user->status,
        ]);
    }
}
