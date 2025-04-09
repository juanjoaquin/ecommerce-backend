<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{

    public function index() //Pertenece al admin
    {
        $pedidos = Pedido::with(['user', 'productos'])
        ->where('estado', false)
        ->whereHas('pago') 
        ->get();

        if(!$pedidos) {
            return response()->json([
                'message' => 'Aun no tienes pedidos'
            ], 404);
        }

        return response()->json([
            'pedidos' => $pedidos
        ], 200);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'total' => 'required|numeric',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'pago.metodo_pago' => 'required|in:tarjeta_debito,tarjeta_credito',
            'pago.numero_tarjeta' => 'required|digits:16',
            'pago.fecha_vencimiento' => 'required|date|after:today',
            'pago.CVV' => 'required|digits:3',
        ]);
    
        // Pedido
        $pedido = new Pedido; 
        $pedido->user_id = Auth::id();
        $pedido->total = $request->total;
        $pedido->save(); 
    
        // le hago un foreach
        $pedido_producto = [];
        foreach ($request->productos as $producto) {
            $pedido_producto[] = [
                'pedido_id' => $pedido->id,
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        PedidoProducto::insert($pedido_producto);
    
        // agrego el new component de pago
        $pago = new Pago();
        $pago->pedido_id = $pedido->id;
        $pago->metodo_pago = $request->pago['metodo_pago'];
        $pago->numero_tarjeta = $request->pago['numero_tarjeta'];
        $pago->fecha_vencimiento = $request->pago['fecha_vencimiento'];
        $pago->CVV = $request->pago['CVV'];
        $pago->save();
    
        
        return response()->json([
            'message' => 'Realizaste un pedido y pago correctamente'
        ]);
    }
    

    public function update($id) // Tmb pertenece al admin
    {

        $pedido = Pedido::find($id);

        if(!$pedido) {
            return response()->json([
                'message' => 'El pedido no se encuentra'
            ], 404);
        }

        $pedido->estado = 1;
        $pedido->save();

        return response()->json([
            'message' => 'El pedido fue marcado completado correctamente',
            'pedido' => $pedido
        ], 200);
    }
}
