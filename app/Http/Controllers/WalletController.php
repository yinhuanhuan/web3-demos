<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    // 查询钱包地址余额
    public function balance(Request $request)
    {
        $address = $request->input('address');
        $param = [
            "jsonrpc" => "2.0",
            "method" => "eth_getBalance",
            "params" => [
                $address,
                "latest",
            ],
            "id" => uniqid(),
        ];
        $requestURL = config('web3.infura_api_url');
        $response = Http::withOptions(['verify' => false])->post($requestURL, $param);
        return $response->body();
    }
}
