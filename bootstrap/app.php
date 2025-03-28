<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use \App\Exceptions\StockManipulationException;
use \Illuminate\Http\Request;
use \App\Enums\StockErrorCodeEnum;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (StockManipulationException $e, Request $request) {
            if($request->is('api/*')) {
                $responseArr = ['message' => $e->getMessage()];

                switch ($e->getCode()) {
                    case StockErrorCodeEnum::INSUFFICIENT_STOCK->value:
                    case StockErrorCodeEnum::PRODUCT_NOT_FOUND_ON_WAREHOUSE->value:
                        $responseArr['data'] = ['product_id' => $e->getProductId()];
                        break;
                }

                return response()->json($responseArr, 400);
            }
        });
    })->create();
