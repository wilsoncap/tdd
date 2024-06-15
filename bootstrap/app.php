<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix:'api/v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $validation){
            return jsonResponse(status: 422, message: $validation->getMessage(), errors: $validation->errors());
        });

        /*$exceptions->render(function (NotFoundHttpException $notFound){
            return jsonResponse(status: 404, message: $notFound->getMessage(), errors: $notFound->errors());
        });*/

        /*$exceptions->render(function (Exception $trowable){
            return jsonResponse();
        });*/
    })->create();
