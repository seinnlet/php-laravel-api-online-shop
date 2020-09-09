<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();

        return response()->json([
            "status" => "ok",
            "totalResults" => count($orders), 
            "orders" => OrderResource::collection($orders)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cartArr = json_decode($request->cart_data);

        $order = new Order;
        $order->voucherno = uniqid(); 
        $order->orderdate = date('Y-m-d');
        $order->user_id = 1;
        $order->shippingmethod = $request->shippingmethod;
        $order->shippingfees = $request->shippingfees;
        $order->totalamount = $request->totalamount;
        $order->status = 1;
        $order->save();

        foreach ($cartArr as $row) {
            $order->items()->attach($row->id,['qty'=>$row->qty]);
        }
        
        return 'Successful!';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
