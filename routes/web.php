<?php

use App\Http\Controllers\NewsController;
use App\Services\RpcRequest;
use App\Services\RpcResponse;
use Illuminate\Support\Facades\Route;

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

Route::post('/news', function (Illuminate\Http\Request $request) {
    $data = json_decode($request->getContent(), true);
    $rpc = new RpcRequest($data);

    if (!$rpc->validate()) {
        return response()->json(
            RpcResponse::error($rpc->getId(), $rpc->getError())
        );
    }

    if (!in_array($data['method'], ['store', 'show'])) {
        return response()->json(
            RpcResponse::error($rpc->getId(), RpcResponse::ERROR_METHOD_NOT_FOUND)
        );
    }

    return (new NewsController)->{strtolower($data['method'])}($request, $rpc);
})->name('news.crud');
