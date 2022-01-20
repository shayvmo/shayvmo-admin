<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Notifications\AdminLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Laravolt\Avatar\Avatar;

class AuthController extends Controller
{

    use AuthenticatesUsers;

    /**
     * 用户登录表单
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm(Request $request)
    {
        return View::make('admin.auth.login');
    }

    /**
     * Handle a login request to the application.
     * @param Request $request
     * @return RedirectResponse|\Illuminate\Http\Response|JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'captcha' => 'required|captcha',
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);


        // 如果该类使用ThrottlesLogins特征，我们可以自动限制此应用程序的登录尝试。我们将通过向这些应用程序发出这些请求的客户端的用户名和IP地址来对此进行键入。
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if($this->sendLoginResponse($request)){
                return $this->success('登录成功', [
                    'redirect'=>route('admin.layout')
                ]);
            }else{
                return $this->error('登录失败', [
                    'redirect'=>route('admin.login')
                ]);
            }
        }

        // 如果登录尝试失败，我们将增加登录尝试的次数，并将用户重定向回登录表单。当然，当该用户超过最大尝试次数时，他们将被锁定。
        $this->incrementLoginAttempts($request);
        $this->sendFailedLoginResponse($request);
    }


    //登录成功后的跳转地址
    public function redirectTo()
    {
        return URL::route('admin.layout');
    }

    /**
     * 退出后的动作
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function loggedOut(Request $request)
    {
        return Redirect::to(URL::route('admin.auth.login'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    protected function authenticated(Request $request, $user)
    {
        $user->last_login_ip = $request->ip();
        $user->last_login_at = mysql_timestamp();
        $user->save();
        // 暂时注释
        /*$user->notify(new AdminLogin([
            'avatar' => (new Avatar(config('laravolt.avatar')))->create($user->username)->toBase64(),
            'title' => '欢迎回来, ' . $user->nickname,
            'time' => date('Y-m-d'),
        ]));*/
    }

    /**
     * 用于登录的字段
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.auth.page');
    }


}
