<?php

namespace App\Exceptions;

use App\Constants\SystemConstant;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ValidateException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->ajax()) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->error(SystemConstant::MODEL_NOT_FOUND_MSG);
            }

            if ($exception instanceof AuthenticationException) {
                return $this->notAuth(SystemConstant::LOGIN_EXPIRE_OR_NOT_LOGIN);
            }

            if ($exception instanceof UnauthorizedHttpException) {
                return $this->notAuth(SystemConstant::LOGIN_EXPIRE_OR_NOT_LOGIN);
            }

            if ($exception instanceof AccessDeniedHttpException) {
                return $this->notAllow($exception->getMessage());
            }

            if (config('app.env') === 'production') {
                Log::error('???????????????????????????'.$exception->getMessage());
                return $this->error(SystemConstant::SYSTEM_ERROR);
            }
        }


        return parent::render($request, $exception);
    }
}
