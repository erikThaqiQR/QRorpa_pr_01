<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect,Response,Stripe;
use App\Orders;


class StripeController extends Controller
{
    public function index()
    {
        return view('stripe');
    }


    public function store(Request $request)
    {
        $order = new Orders;
        $order->nrTable = 1;
        $order->statusi = 0;
        $order->byId = $request->userId;;
        $order->userEmri = $request->userName;
        $order->userEmail = $request->userEmail;
        $order->porosia = $request->userOrder;

        

        $order->save();

        $stripe = Stripe::charges()->create([
            'source' => $request->get('tokenId'),
            'currency' => 'eur',
            'amount' => $request->get('amount') 
        ]);
  
        return $stripe;
    }
}
