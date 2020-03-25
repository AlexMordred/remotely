<?php

namespace App\Http\Controllers;

use App\News;
use App\Services\RpcRequest;
use App\Services\RpcResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function store(Request $request, RpcRequest $rpc)
    {
        $validator = $this->validator($rpc->getParams());

        if ($validator->fails()) {
            return RpcResponse::error($rpc->getId(), RpcResponse::ERROR_INVALID_PARAMS);
        }

        $news = News::create(array_merge($rpc->getParams(), [
            'page_uid' => \Str::slug($rpc->getParams()['title']),
        ]));

        return RpcResponse::success($rpc->getId(), $news);
    }

    public function show(Request $request, RpcRequest $rpc)
    {
        if (empty($rpc->getParams()['page_uid'])) {
            abort(404);
        }
        
        $news = News::where('page_uid', $rpc->getParams()['page_uid'])->first();

        if (!$news) {
            abort(404);
        }

        return RpcResponse::success($rpc->getId(), $news);
    }

    protected function validator($data) {
        return Validator::make($data, [
            'title' => 'required|string|max:255',
            'snippet' => 'required|string|max:255',
            'full_text' => 'required|string',
        ]);
    }
}
