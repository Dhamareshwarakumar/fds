<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService {
    public function createOrder($user_id, $restaurant_id) {
        $result = DB::table('orders')->insert([
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id
        ]);

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Order created',
                'status' => Response::HTTP_CREATED
            ];
        } else {
            Log::error('OrderService::createOrder() failed');
            return [
                'success' => false,
                'msg' => 'Order not created',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function getOrderById($order_id) {
        return DB::table('orders')
            ->where('order_id', $order_id)
            ->first();
    }

    public function getOrdersByRestaurant($restaurant_id) {
        // SELECT users.username, orders.order_status, orders.created_at FROM orders INNER JOIN users ON orders.user_id = users.user_id;
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            ->select('users.username', 'orders.order_status', 'orders.created_at')
            ->where('orders.restaurant_id', $restaurant_id)
            ->get();
    }

    public function getLatestOrder($user_id, $restaurant_id) {
        return DB::table('orders')
            ->where('user_id', $user_id)
            ->where('restaurant_id', $restaurant_id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function createOrderDish($order_id, $dish_id, $quantity, $dish_price) {
        $result = DB::table('order_dishes')->insert([
            'order_id' => $order_id,
            'dish_id' => $dish_id,
            'quantity' => $quantity,
            'dish_price' => $dish_price
        ]);

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Order dish created',
                'status' => Response::HTTP_CREATED
            ];
        } else {
            Log::error('OrderService::createOrderDish() failed');
            return [
                'success' => false,
                'msg' => 'Order dish not created',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }
}