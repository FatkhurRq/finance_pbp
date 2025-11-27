<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // ------- Tampilkan semua transaksi milik user -------
    public function index()
    {
        $transactions = auth()->user()->transactions()->latest()->get();

        return view('transactions.index', compact('transactions'));
    }

    // ------- Form untuk tambah transaksi -------
    public function create()
    {
        return view('transactions.create');
    }

    // ------- Simpan transaksi baru -------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    // ------- Form untuk edit transaksi -------
    public function edit(Transaction $transaction)
    {
        // Pastikan hanya owner yang boleh edit
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return view('transactions.edit', compact('transaction'));
    }

    // ------- Update transaksi -------
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // ------- Hapus transaksi -------
    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
