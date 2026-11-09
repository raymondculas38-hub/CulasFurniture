<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddToCartRequest;
use App\Http\Requests\User\UpdateCartRequest;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with(['product.category', 'product.images'])
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->effective_price;
        });

        return view('user.cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(AddToCartRequest $request)
    {
        $product = Product::active()->findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', "Sorry! Only {$product->stock} unit(s) available in stock.");
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return back()->with('error', "Cannot add more. Stock limit of {$product->stock} reached.");
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', "{$product->name} added to your cart!");
    }

    public function update(UpdateCartRequest $request, CartItem $cartItem)
    {
        // Enforce ownership
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized cart operation.');
        }

        $product = $cartItem->product;
        if ($request->quantity > $product->stock) {
            return back()->with('error', "Maximum available stock is {$product->stock}.");
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(CartItem $cartItem)
    {
        // Enforce ownership
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized cart operation.');
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Cart cleared.');
    }
}
