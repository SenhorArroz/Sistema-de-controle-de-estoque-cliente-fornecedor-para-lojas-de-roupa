<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\CodigoBarra;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function dashboard()
    {
        $totalClientes = Cliente::count();
        $vendasHoje = 0;
        $totalProdutos = CodigoBarra::count();
        $registeredProdutos = Produto::count();
        return view('dashboard', compact('totalClientes', 'vendasHoje', 'totalProdutos', 'registeredProdutos'));
    }
}
