<?php

namespace App\Http\Controllers;

use App\Traits\AdminValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller {
    use ApiResponser;
    use AdminValidator;

    protected $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    /*
    * @route    :: GET /api/orders
    * @desc     :: Get all orders of a restaurant
    * @access   :: Private:: Restaurant Owner
    */

    public function index(Request $request) {
        if (!$this->isRestaurantAdmin($request)) {
            return $this->errorResponse('You are not authorized to view orders', Response::HTTP_UNAUTHORIZED);
        }

        return $this->orderService->getOrdersByRestaurant($request->user()->user_id);
    }

    /*
    * @route    :: POST /api/orders/report
    * @desc     :: Generate Report
    * @access   :: Private:: Restaurant Owner
    */

    public function report(Request $request) {
        DB::statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
        DB::statement('START TRANSACTION');
        DB::statement('LOCK TABLES orders WRITE, order_dishes WRITE');
        if (!$this->isRestaurantAdmin($request)) {
            return $this->errorResponse('You are not authorized to view orders', Response::HTTP_UNAUTHORIZED);
        }

        $restaurant_id = $request->user()->user_id;

        $ordersCount = DB::table('orders')->where('restaurant_id', $restaurant_id)->count();

        sleep(5);

        $amounts = DB::table('orders')
            ->join('order_dishes', 'orders.order_id', '=', 'order_dishes.order_id')
            ->where('orders.restaurant_id', $restaurant_id)
            ->select('order_dishes.quantity', 'order_dishes.dish_price');

        $totalAmount = $amounts->sum(DB::raw('order_dishes.quantity * order_dishes.dish_price'));

        $orders = DB::table('orders')->where('restaurant_id', $restaurant_id)->get();

        DB::statement('COMMIT');
        DB::statement('UNLOCK TABLES');
        Log::info('[OrderController][generatereport] Report generated for restaurant ' . $restaurant_id);
        return $this->successResponse([
            'ordersCount' => $ordersCount,
            'totalAmount' => $totalAmount,
            'orders' => $orders
        ], Response::HTTP_OK);    
    }
}