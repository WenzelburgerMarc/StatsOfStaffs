<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        if (app()->environment('production') && $exception->getMessage() !== 'Unauthenticated.') {
            try {

                session()->flush();

                return redirect('/login')->with('error', 'Unknown Error Occurred');
            } catch (Throwable $e) {

                return parent::render($request, $exception);
            }
        } else {
            return parent::render($request, $exception);
        }

    }
}
