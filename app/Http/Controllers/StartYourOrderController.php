<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class StartYourOrderController extends Controller
{
    public function create()
    {
        return view('start-your-order', [
            'onlineOrderEnabled' => Cache::get('online-order-enabled', Transaction::ONLINE_ORDER_DISABLED),
        ]);
    }

    public function store()
    {
        $request = Request::validate([
            'type' => ['required', 'in:'.implode(',', Transaction::TYPE)],
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'phone' => ['required', 'max:50'],
            'address' => ['nullable', 'required_if:type,delivery', 'max:250'],
            'takeout_time' => ['nullable', 'required_if:type,takeout', 'max:50'],
            'message' => ['nullable', 'string'],
            'items' => ['required', 'array'],
            'items.*' => ['required', 'integer'],
            'tip_percentage' => ['required', 'min:0'],
        ]);

        Session::put('order', $request);

        return Response::json($request);
    }
}