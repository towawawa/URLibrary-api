<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;



class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            // バリデーションエラー
            if ($e instanceof ValidationException) {
                return $this->invalidJson($request, $e);
            }

            // Httpエラー
            if ($e instanceof HttpException) {
                $status_code = $e->getStatusCode();
                switch ($status_code) {
                    case 401:
                    case 404:
                        return response()->json(config("response.{$status_code}"), $status_code);
                        break;
                    case 405:
                        return response()->json(config('response.401'), 401);
                        break;
                    default:
                        return response()->json([
                            'code' => $status_code,
                            'message' => $e->getMessage()
                        ], $status_code);
                        break;
                }
            }
            // その他のエラー(DBなど)
            else if ($e->getCode()) {
                return response()->json([
                    'code' => 500,
                    'message' => $e->getMessage()
                ], 500);
            }
        });
    }
}
