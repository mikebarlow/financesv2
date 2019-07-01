<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Routing\Router;

Route::get('/', function () {
    return redirect()->route('accounts.list');
});

Auth::routes(
    ['register' => false, 'reset' => false,]
);

$router->group(
    [
        'middleware' => ['auth',],
    ],
    function (Router $router) {
        $router->get('/home', function () {
            return redirect()->route('accounts.list');
        });

        // --------------------
        // budgets
        $router->get('/budgets', Budgets\ListBudgetsController::class)
            ->name('budgets.list');

        $router->get('/budgets/create', Budgets\CreateBudgetController::class)
            ->name('budgets.create');

        $router->delete('/budgets/{id}', Budgets\DeleteBudgetController::class)
            ->name('budgets.delete');

        $router->get('/budgets/{id}/edit', Budgets\EditBudgetController::class)
            ->name('budgets.edit');

        // --------------------
        // accounts
        $router->get('/accounts', Accounts\ListAccountsController::class)
            ->name('accounts.list');

        $router->get('/accounts/create', Accounts\CreateAccountFormController::class)
            ->name('accounts.create');

        $router->post('/accounts/create', Accounts\CreateAccountController::class)
            ->name('accounts.create.post');

        $router->get('/accounts/{id}', Accounts\ViewAccountController::class)
            ->name('accounts.view');

        $router->get('/accounts/{id}/start', Accounts\StartAccountController::class)
            ->name('accounts.start');

        $router->get('/accounts/{id}/old-sheets', Accounts\ListOldSheetsController::class)
            ->name('accounts.list-old-sheets');

        $router->get('/accounts/{id}/old-sheets/{sheetId}', Accounts\ViewOldSheetController::class)
            ->name('accounts.view-old-sheet');

        // --------------------
        // mass transfers
        $router->get('/accounts/{id}/mass-transfers', Accounts\ManageMassTransfersController::class)
            ->name('accounts.mass-transfers');

        $router->get('/accounts/{id}/mass-transfers/create', Accounts\MassTransfers\CreateController::class)
            ->name('accounts.masstransfers.create');

        $router->get('/accounts/{id}/mass-transfers/{transferId}/edit', Accounts\MassTransfers\EditController::class)
            ->name('accounts.masstransfers.edit');

        $router->get('/accounts/{id}/mass-transfers/{transferId}/delete', Accounts\MassTransfers\DeleteController::class)
            ->name('accounts.masstransfers.delete');
    }
);
