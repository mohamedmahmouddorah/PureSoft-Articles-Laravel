<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart is empty!']);
        }

        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending'
            ]);

            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'article_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price']
                ]);
                
                // Update stock if tracked
                $article = Article::find($id);
                if ($article) {
                    $article->decrement('stock', $details['quantity']);
                }
            }

            DB::commit();
            session()->forget('cart');

            return response()->json(['success' => true, 'message' => 'Order placed successfully!', 'order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Order failed: ' . $e->getMessage()]);
        }
    }
}
