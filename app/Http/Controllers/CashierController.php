<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Checkout;
use Illuminate\Http\Request;

class CashierController extends Controller
{

    public function cartCount() {
    $items = Item::all();
    $cartCount = Cart::sum('quantity'); // total number of items
    return view('cashier.index', compact('items', 'cartCount'));
}


    public function index() {
        $items = Item::all();
        return view('cashier.index', compact('items'));
    }

    public function addItem(Request $req) {
        Item::create([
            'name' => $req->name,
            'price' => $req->price,
        ]);
        return back();
    }

    public function addToCart(Request $request, $id) {
    $quantity = (int) $request->input('quantity', 1); // default to 1 if empty

    // Check if the item already exists in the cart
    $cartItem = Cart::where('item_id', $id)->first();

    if ($cartItem) {
        // If it exists, just increment the quantity
        $cartItem->quantity += $quantity;
        $cartItem->save();
    } else {
        // Otherwise, create a new cart row
        Cart::create([
            'item_id' => $id,
            'quantity' => $quantity,
        ]);
    }

    return back()->with('success', 'Item added to cart!');
}



    public function showCart() {
    $cartItems = Cart::with('item')->get();

    // Group by item_id and sum quantity
    $grouped = $cartItems->groupBy('item_id')->map(function($items) {
        $firstItem = $items->first()->item;
        $quantity = $items->sum('quantity');
        return (object)[
            'item' => $firstItem,
            'quantity' => $quantity,
        ];
    });

    return view('cashier.cart', ['cart' => $grouped]);
}


    public function updateCart(Request $request, $id) {
        $quantity = (int) $request->input('quantity', 1);

        $cartItem = Cart::where('item_id', $id)->first();
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            return back()->with('success', 'Quantity updated!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    // Delete an item from the cart
    public function deleteCart($id) {
        $cartItem = Cart::where('item_id', $id)->first();
        if ($cartItem) {
            $cartItem->delete();
            return back()->with('success', 'Item removed from cart!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    public function checkout(Request $req) {
    $method = $req->payment_method; // cash or qr
    $userId = auth()->id(); // get the logged-in user ID

    $cartItems = Cart::with('item')->get();

    foreach ($cartItems as $c) {
        Checkout::create([
            'item_id' => $c->item->id,
            'user_id' => $userId, // store user ID
            'quantity' => $c->quantity,
            'total_price' => $c->item->price * $c->quantity,
            'payment_method' => $method,
        ]);
    }

    Cart::truncate(); // clear cart after checkout

    return view('cashier.success', ['method' => $method]);
}
}
