<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Throwable $exception)
    // {
    //     // Check if the exception is a database query exception
    //     if ($exception instanceof QueryException) {
    //         // Extract the message from the exception
    //         $errorMessage = $exception->getMessage();
    //         // dd($errorMessage);

    //         // Check for a specific type of SQL error (e.g., unknown column)
    //         if (strpos($errorMessage, 'Unknown column') !== false) {
    //             // You can parse the error message to extract the column name
    //             preg_match("/Unknown column '(.+?)'/", $errorMessage, $matches);
    //             $columnName = $matches[1] ?? 'a column';

    //             // Create a custom error message
    //             $customMessage = "It seems like there is an issue with the database: Column '{$columnName}' was not found.";

    //             // Return a response with the custom message
    //             return response()->json(['error' => $customMessage], 400);
    //         }

    //         // Handle other SQL errors if needed...
    //     }

    //     // For all other exceptions, use the default handler
    //     return parent::render($request, $exception);
    // }
}
