<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return response()->json(Voucher::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'discount_amount' => 'required|numeric',
            'discount_type' => 'required|in:fixed,percent',
            'min_order' => 'nullable|numeric',
            'max_uses' => 'nullable|integer',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
        ]);

        $voucher = Voucher::create($request->all());

        return response()->json([
            'message' => 'Voucher berhasil dibuat',
            'data' => $voucher
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Voucher tidak ditemukan'], 404);
        }

        $request->validate([
            'code' => 'nullable|string|unique:vouchers,code,' . $id,
            'discount_amount' => 'nullable|numeric',
            'discount_type' => 'nullable|in:fixed,percent',
            'min_order' => 'nullable|numeric',
            'max_uses' => 'nullable|integer',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $voucher->update($request->all());

        return response()->json([
            'message' => 'Voucher berhasil diperbarui',
            'data' => $voucher
        ]);
    }

    public function destroy($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Voucher tidak ditemukan'], 404);
        }

        $voucher->delete();

        return response()->json(['message' => 'Voucher berhasil dihapus']);
    }

    public function toggleActive($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Voucher tidak ditemukan'], 404);
        }

        $voucher->update(['is_active' => !$voucher->is_active]);

        return response()->json([
            'message' => 'Status voucher berhasil diubah',
            'is_active' => $voucher->is_active
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher tidak ditemukan'], 404);
        }

        if (!$voucher->is_active) {
            return response()->json(['message' => 'Voucher tidak aktif'], 400);
        }

        $today = \Carbon\Carbon::today();
        
        if ($voucher->valid_from && $today->lt($voucher->valid_from)) {
            return response()->json(['message' => 'Voucher belum bisa digunakan'], 400);
        }

        if ($voucher->valid_until && $today->gt($voucher->valid_until)) {
            return response()->json(['message' => 'Voucher sudah kadaluarsa'], 400);
        }

        if ($voucher->used_count >= $voucher->max_uses) {
            return response()->json(['message' => 'Kuota voucher sudah habis'], 400);
        }

        if ($request->subtotal < $voucher->min_order) {
            return response()->json([
                'message' => 'Minimum order Rp ' . number_format($voucher->min_order, 0, ',', '.') . ' untuk voucher ini'
            ], 400);
        }

        return response()->json([
            'message' => 'Voucher valid',
            'data' => $voucher
        ]);
    }
}
