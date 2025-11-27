@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="mb-3">Daftar Transaksi</h2>

    <!-- Alert jika ada pesan sukses -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tombol tambah data -->
    <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>

    <!-- Tabel transaksi -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Judul</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ $t->date }}</td>
                    <td>{{ $t->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                    <td>{{ $t->title }}</td>
                    <td>Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('transactions.edit', $t->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('transactions.destroy', $t->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
