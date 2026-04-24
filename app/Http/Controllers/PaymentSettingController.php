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
            'qris' => 'required|image|mimes:png,jpg,jpeg'
        ]);
    
        $setting = PaymentSetting::first();
    
        // hapus QRIS lama
        if ($setting && $setting->qris) {
            $old = $_SERVER['DOCUMENT_ROOT'].'/storage/'.$setting->qris;
            if (file_exists($old)) unlink($old);
        }
    
        $folder = $_SERVER['DOCUMENT_ROOT'].'/storage/qris';
    
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }
    
        $file = $request->file('qris');
        $namaFile = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
    
        move_uploaded_file($file->getPathname(), $folder.'/'.$namaFile);
    
        PaymentSetting::updateOrCreate(
            ['id' => 1],
            ['qris' => 'qris/'.$namaFile]
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
    
            $path = public_path('storage/' . $setting->qris);
    
            if (file_exists($path)) {
                unlink($path);
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
                ? 'https://tevacentre.site/storage/' . $setting->qris
                : null,
        ]);
    }
}