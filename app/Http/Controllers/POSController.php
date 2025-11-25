<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hakAkesaAdminPanel()) {
            return redirect()->route('admin.dashboard');
        }

        return view('pos.index', [
            'user' => $user
        ]);
    }
}
