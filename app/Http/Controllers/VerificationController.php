<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Tampilkan Halaman Verifikasi (Safe Verify)
     */
    public function index()
    {
        // Bersihkan session verified lama setiap kali halaman verifikasi utama dibuka
        session()->forget('verified');

        return view('verification.index');
    }

    /**
     * Set verifikasi sukses ke session dan redirect ke Landing Page
     */
    public function verify(Request $request)
    {
        session(['verified' => true]);

        return response()->json(['success' => true]);
    }
}
