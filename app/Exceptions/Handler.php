<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

// use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    // public function render(Request $request)
    // {
    //     if ($request->expectsJson()) {
         
    //         if ($exception instanceof TokenMismatchException) {
    //             return response()->json([
    //             'message' => 'Your session has expired. You will need to refresh the page and login again to continue using the system.'], 419);
    //         }
         
    //     }

    // }


    // public function render($request, Exception $exception)
    // {
    //     if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
    //         return response()->json(['message' => 'Not Found!'], 404);
    //     }

    // }




}
