<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Article $article)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$article->id])) {
            $cart[$article->id]['quantity']++;
        } else {
            $cart[$article->id] = [
                "name" => $article->title,
                "quantity" => 1,
                "price" => $article->price,
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true, 'message' => 'Product added to cart successfully!']);
    }

    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    public function getCart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return response()->json([
            'cart' => $cart,
            'total' => $total
        ]);
    }
}
