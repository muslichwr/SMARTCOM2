<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BantuanController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role_as;
        $pdfFilePath = '';

        // Tentukan PDF berdasarkan peran pengguna
        if ($role == 1) { // Guru
            $pdfFilePath = 'bantuans/panduan-admin.pdf';
        } elseif ($role == 2) { // Admin
            $pdfFilePath = 'bantuans/panduan-guru.pdf';
        } else { // Siswa atau peran lain
            $pdfFilePath = 'bantuans/panduan-siswa.pdf';
        }

        return view('bantuan.index', compact('pdfFilePath'));
    }
}
