@extends('layouts.app')

@section('content')
   <div class="container">
       <h1>Dashboard</h1>

       <table>
           <thead>
               <tr>
                  <th>Orders</th>
                  <th>Quantity</th>
               </tr>
           </thead>
           <tbody>
               @foreach ($products as $date => $product)
               <tr>
                  <td>{{ $date }}</td>
                  <td>{{ $product['orders'] }}</td>
                  <td>{{ $product['quantity'] }}</td>
               </tr>
               @endforeach
           </tbody>
       </table>
       
       <table>
           <thead>
               <tr>
                  <th>Purchase Price</th>
                  <th>Retail Price</th>
                  <th>Deal Price</th>
               </tr>
           </thead>
           <tbody>
               @foreach ($sales as $date => $sale)
               <tr>
                  <td>{{ $date }}</td>
                  <td>{{ $sale['purchase_price'] }}</td>
                  <td>{{ $sale['retail_price'] }}</td>
                  <td>{{ $sale['deal_price'] }}</td>
               </tr>
               @endforeach
           </tbody>
       </table>
   </div>
@endsection