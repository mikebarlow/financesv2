<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')
    ->middleware('auth:api')
    ->group(
        function (Router $router) {
            // --------------------
            // budgets
            $router->post('/budgets/create', Api\Budgets\CreateBudgetController::class)
                ->name('budgets.create');

            $router->get('/budgets/{id}', Api\Budgets\GetBudgetController::class)
                ->name('budgets.get');

            $router->post('/budgets/{id}', Api\Budgets\UpdateBudgetController::class)
                ->name('budgets.update');


            // --------------------
            // accounts
            $router->get('/accounts/{id}', Api\Accounts\GetAccountController::class)
                ->name('accounts.get');

            $router->get('/accounts/{id}/latest', Api\Accounts\GetLatestSheetController::class)
                ->name('accounts.get.latest');

            // --------------------
            // sheets
            $router->post('/sheets', Api\Sheets\CreateSheetController::class)
                ->name('sheets.create');

            $router->post('/sheets/payment', Api\Sheets\MakePaymentController::class)
                ->name('sheets.payment');

            $router->get('/sheets/{id}/transactions', Api\Sheets\GetTransactionsController::class)
                ->name('sheets.transactions');
        }
    );
