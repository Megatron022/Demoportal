<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller {
   
   public function index() {
      
      $products = [];
      $sales = [];
      
      for ($date = Carbon::now(); $date->lt(Carbon::now()->addDays(7)); $date->addDay()) {
         
         $orders = Order::whereDate('created_at', $date)->get();
         $key = $date->format('Y-m-d');
         
         foreach ($orders as $order) {
            $products[$key]['orders'] += $order->status == 'completed' ? 1 : 0;
            $products[$key]['quantity'] += $order->details->sum('quantity');
            
            $sales[$key]['purchase_price'] += $order->details->sum('purchase_price');
            $sales[$key]['retail_price'] += $order->details->sum('retail_price');
            $sales[$key]['deal_price'] += $order->details->sum('deal_price');
         }
      }
      
      return view('dashboard.index', compact('products', 'sales'));
   }
}