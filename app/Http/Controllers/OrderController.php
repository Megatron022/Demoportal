<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Constructor to ensure only admin, staff, or accountant users have access.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __construct()
    {
        $user = Auth::user();

        if (
            !$user ||
            !$user->hasRole("admin") ||
            !$user->hasRole("staff") ||
            !$user->hasRole("accountant")
        ) {
            return redirect()
                ->route("home")
                ->with(
                    "error",
                    "Unauthorized access: Admin, Staff, or Accountant role required."
                );
        }
    }

    /**
     * Display a listing of orders.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::all();
        return view("orders.index", compact("orders"));
    }

    /**
     * Show the form for creating a new order.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $users = User::all();
        return view("orders.create", compact("users"));
    }

    /**
     * Store a newly created order in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            "user_id" => "required",
            "status" => "required",
            // Add validation for other fields as needed
        ]);

        Order::create([
            "user_id" => $request->input("user_id"),
            "address_id" => $request->input("address_id"),
            "status" => $request->input("status"),
            // Add other fields as needed
        ]);

        return redirect()
            ->route("orders.index")
            ->with("success", "Order created successfully");
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $order = Order::with('details.product')->find($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);

        $users = User::all();
        return view("orders.edit", compact("order", "users"));
    }

    /**
     * Update the specified order in the database.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            "user_id" => "required",
            "status" => "required",
            // Add validation for other fields as needed
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            "user_id" => $request->input("user_id"),
            "address_id" => $request->input("address_id"),
            "status" => $request->input("status"),
            // Update other fields as needed
        ]);

        return redirect()
            ->route("orders.index")
            ->with("success", "Order updated successfully");
    }

    /**
     * Remove the specified order from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()
            ->route("orders.index")
            ->with("success", "Order deleted successfully");
    }
}
