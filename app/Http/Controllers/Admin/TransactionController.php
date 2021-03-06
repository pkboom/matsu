<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Transactions/Index', [
            'filters' => Request::all('search'),
            'transactions' => Transaction::latest()
                ->filter(Request::only('search'))
                ->paginate(),
            'status' => [
                'accepted' => Transaction::TRANSACTION_ACCEPTED,
            ],
            'type' => [
                'delivery' => Transaction::DELIVERY,
            ],
        ]);
    }

    public function show(Transaction $transaction)
    {
        return Inertia::render('Admin/Transactions/Show', [
            'transaction' => $transaction->load('items'),
            'status' => [
                'succeeded' => Transaction::TRANSACTION_SUCCEEDED,
                'pending' => Transaction::TRANSACTION_PENDING,
                'refunded' => Transaction::TRANSACTION_REFUNDED,
                'failed' => Transaction::TRANSACTION_FAILED,
            ],
        ]);
    }

    public function update(Transaction $transaction)
    {
        $transaction->update(
            Request::validate([
                'status' => ['required', 'in:'.implode(',', [Transaction::TRANSACTION_SUCCEEDED, Transaction::TRANSACTION_FAILED, Transaction::TRANSACTION_PENDING])],
            ])
        );

        if ($transaction->status === Transaction::TRANSACTION_SUCCEEDED) {
            $transaction->update([
                'created_at' => now(),
            ]);

            $transaction->succeeded();
        }

        return Redirect::route('admin.transactions')->with('success', 'Transaction updated.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return Redirect::route('admin.transactions')->with('success', 'Transaction deleted.');
    }
}
