<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan Halaman Landing Page
     */
    public function index()
    {
        if (!session('verified')) {
            return redirect()->route('verification.index');
        }

        return view('landing.index');
    }
}
