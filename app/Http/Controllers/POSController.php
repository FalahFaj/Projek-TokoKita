<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hakAkesaAdminPanel()) {
            return redirect()->route('admin.dashboard');
        }

        // Ambil data produk yang aktif dan tersedia
        $products = Product::with('category')
            ->where('is_active', true)
            ->get();

        // Ambil data kategori yang aktif
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('pos.index', [
            'user' => $user,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
