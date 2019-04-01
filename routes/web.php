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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

$router->group(
    [
        'middleware' => ['auth',],
    ],
    function (Router $router) {
        // --------------------
        // budgets
        $router->get('/budgets', Budgets\ListBudgetsController::class)
            ->name('budgets.list');

        $router->view('/budgets/create', 'budgets.create')
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
    }
);
