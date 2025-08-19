<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Checkout;
use Illuminate\Http\Request;

class AdminController extends Controller
{
     public function salesReport() {
        // Get total sold quantity and revenue per item
        $report = Checkout::with('item')
            ->selectRaw('item_id, SUM(quantity) as total_sold, SUM(total_price) as total_revenue')
            ->groupBy('item_id')
            ->get();

        return view('sales-report', compact('report'));
    }

    // Show the admin dashboard with all items
    public function dashboard() {
        $items = Item::all();
        return view('admin_dashboard', compact('items'));
    }

    // Add a new item
    public function addItem(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Item::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Item added successfully!');
    }

    // Delete an item
    public function deleteItem($id) {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully!');
    }
public function editForm($id)
{
    $item = Item::findOrFail($id); // get the item to edit
    $items = Item::all();           // get all items for the table
    return view('admin_dashboard', compact('items', 'item')); // pass both to the view
}

public function updateItem(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
    ]);

    $item = Item::findOrFail($id);
    $item->update([
        'name' => $request->name,
        'price' => $request->price,
    ]);

    return redirect('/admin')->with('success', 'Item updated successfully!');
}

}
