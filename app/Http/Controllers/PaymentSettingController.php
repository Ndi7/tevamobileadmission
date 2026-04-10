<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $setting = PaymentSetting::first();
        return view('pengaturan-pembayaran', compact('setting'));
    }

    // =========================
    // UPDATE BANK
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'bank' => 'required',
            'rekening' => 'required',
            'pemilik' => 'required',
        ]);

        PaymentSetting::updateOrCreate(
            ['id' => 1],
            [
                'bank' => $request->bank,
                'rekening' => $request->rekening,
                'pemilik' => $request->pemilik,
            ]
        );

        return back()->with('success', 'Data bank berhasil disimpan');
    }

    // =========================
    // UPDATE QRIS
    // =========================
    public function updateQris(Request $request)
    {
        $request->validate([
            'qris' => 'required|image'
        ]);

        $setting = PaymentSetting::first();

        // hapus lama
        if ($setting && $setting->qris) {
            if (Storage::disk('public')->exists($setting->qris)) {
                Storage::disk('public')->delete($setting->qris);
            }
        }

        // upload baru
        $path = $request->file('qris')->store('qris', 'public');

        PaymentSetting::updateOrCreate(
            ['id' => 1],
            [
                'qris' => $path
            ]
        );

        return back()->with('success', 'QRIS berhasil diupdate');
    }

    // =========================
    // DELETE QRIS
    // =========================
    public function deleteQris()
    {
        $setting = PaymentSetting::first();

        if ($setting && $setting->qris) {

            if (Storage::disk('public')->exists($setting->qris)) {
                Storage::disk('public')->delete($setting->qris);
            }

            $setting->qris = null;
            $setting->save();
        }

        return back()->with('success', 'QRIS berhasil dihapus');
    }


    public function api()
    {
        $setting = PaymentSetting::first();

        return response()->json([
            'bank' => $setting->bank ?? null,
            'rekening' => $setting->rekening ?? null,
            'pemilik' => $setting->pemilik ?? null,
            'qris' => $setting && $setting->qris
                ? 'http://10.0.2.2:8000/storage/' . $setting->qris
                : null,
        ]);
    }
}