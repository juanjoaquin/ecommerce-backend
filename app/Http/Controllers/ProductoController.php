<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        // $productos = Producto::where('disponible', true)->orderBy('id', 'DESC')->get();

        $productos = Producto::orderBy('id', 'DESC')->get();

        if (!$productos) {
            return response()->json([
                'message' => 'Productos no disponibles'
            ], 404);
        }

        return response()->json([
            'productos' => $productos
        ], 200);
    }

    public function update($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'message' => 'El producto no se encuentra'
            ], 404);
        }

        // Alternar entre disponible (1) y agotado (0)
        $producto->disponible = !$producto->disponible;
        $producto->save();

        return response()->json([
            'message' => $producto->disponible ? 'El producto está disponible nuevamente' : 'El producto fue marcado como agotado',
            'producto' => $producto
        ], 200);
    }


}
