<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class StartYourOrderController extends Controller
{
    public function create()
    {
        return view('start-your-order', [
            'onlineOrder' => Cache::get('online-order', Transaction::ONLINE_ORDER_DISABLED),
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
            'orders' => ['required', 'array'],
            'orders.*' => ['required', 'integer'],
            'tip' => ['required', 'min:0'],
        ]);

        return Response::json($request);
    }
}
