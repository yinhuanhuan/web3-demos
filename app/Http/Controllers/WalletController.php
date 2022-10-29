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

    // 发送交易，上送经过签名的原始交易信息
    public function sendRawTransaction(Request $request) {
        $rawTransaction = $request->input('rawTransaction');
        $param = [
            "jsonrpc" => "2.0",
            "method" => "eth_sendRawTransaction",
            "params" => [$rawTransaction],
            "id" => uniqid(),
        ];
        $requestURL = config('web3.infura_api_url');
        $response = Http::withOptions(['verify' => false])->post($requestURL, $param);
        return $response->body();
    }

    // 查询钱包地址token余额
    public function tokenBalance(Request $request)
    {
        $contractAddress = $request->input('contractAddress');
        $rawData = $request->input('rawData');
        $param = [
            "jsonrpc" => "2.0",
            "method" => "eth_getBalance",
            "params" => [
                $contractAddress,
                "latest",
            ],
            "id" => uniqid(),
        ];
        $param = [
            "jsonrpc" => "2.0",
            "method" => "eth_call",
            "params" => [
                [
                    "to" => $contractAddress,
                    "data" => $rawData,
                ],
                "latest",
            ],
            "id" => 42,
            
        ];
        $requestURL = config('web3.infura_api_url');
        $response = Http::withOptions(['verify' => false])->post($requestURL, $param);
        return $response->body();
    }
}
