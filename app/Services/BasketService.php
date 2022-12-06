<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Services\DishService;
use App\Services\OrderService;

class BasketService {

    protected $dishService;
    protected $orderService;

    public function __construct(DishService $dishService, OrderService $orderService) {
        $this->dishService = $dishService;
        $this->orderService = $orderService;
    }

    public function getBasket($user_id, $restaurant_id) {
        return DB::table('baskets')
            ->where('user_id', $user_id)
            ->where('restaurant_id', $restaurant_id)
            ->first();
    }

    public function createBasket($user_id, $restaurant_id) {
        return DB::table('baskets')->insert([
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id
        ]);
    }

    public function deleteBasket($user_id, $restaurant_id) {
        $basket = $this->getBasket($user_id, $restaurant_id);

        if ($basket) {
            return DB::table('baskets')
                ->where('basket_id', $basket->basket_id)
                ->delete();
        }
    }

    public function getBasketDishes($user_id, $restaurant_id) {
        $basket = $this->getBasket($user_id, $restaurant_id);

        if ($basket) {
            return DB::table('basket_dishes')
                ->join('dishes', 'dishes.dish_id', '=', 'basket_dishes.dish_id')
                ->where('basket_dishes.basket_id', $basket->basket_id)
                ->get();
        } else {
            return [];
        }
    }

    public function getBasketDishByDishId($basket_id, $dish_id) {
        return DB::table('basket_dishes')
            ->where('basket_id', $basket_id)
            ->where('dish_id', $dish_id)
            ->first();
    }

    public function deleteBasketDish($basket_id, $dish_id) {
        return DB::table('basket_dishes')
            ->where('basket_id', $basket_id)
            ->where('dish_id', $dish_id)
            ->delete();
    }

    public function createBasketDish($user_id, $dish_id, $quantity) {
        // check if dish exists
        $dish = $this->dishService->getDishById($dish_id);
        if (!$dish) {
            return [
                'success' => false,
                'msg' => 'Dish does not exist',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        // check if basket exists else create one
        $basket = $this->getBasket($user_id, $dish->restaurant_id);
        if (!$basket) {
            $basket = $this->createBasket($user_id, $dish->restaurant_id);
            $basket = $this->getBasket($user_id, $dish->restaurant_id);
        }
        
        // Check if dish already exists in basket
        $basketDish = $this->getBasketDishByDishId($basket->basket_id, $dish_id);

        if ($basketDish) {
            return [
                'success' => false,
                'msg' => 'Dish already in the cart',
                'status' => Response::HTTP_CONFLICT
            ];   
        }

        $result = DB::table('basket_dishes')->insert([
            'basket_id' => $basket->basket_id,
            'dish_id' => $dish_id,
            'quantity' => $quantity
        ]);

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Dish added to cart',
                'status' => Response::HTTP_CREATED
            ];
        } else {
            Log::channel('error_log')->error('Database Error :: Error adding dish to cart');
            return [
                'success' => false,
                'msg' => 'Dish could not be added',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function updateBasketDish($user_id, $dish_id, $quantity) {
        // check if dish exists
        $dish = $this->dishService->getDishById($dish_id);
        if (!$dish) {
            return [
                'success' => false,
                'msg' => 'Dish does not exist',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        // check if basket exists else create one
        $basket = $this->getBasket($user_id, $dish->restaurant_id);
        if (!$basket) {
            return [
                'success' => false,
                'msg' => 'Basket does not exist',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }
        
        // Check if dish already exists in basket
        $basketDish = $this->getBasketDishByDishId($basket->basket_id, $dish_id);

        if (!$basketDish) {
            return [
                'success' => false,
                'msg' => 'Dish not in the cart',
                'status' => Response::HTTP_CONFLICT
            ];   
        }

        if ($quantity == 'increment') {
            $quantity = $basketDish->quantity + 1;
        } else if ($quantity == 'decrement') {
            $quantity = $basketDish->quantity - 1;

            if ($quantity < 1) {
                if ($this->deleteBasketDish($basket->basket_id, $dish_id)) {
                    return [
                        'success' => true,
                        'msg' => 'Dish removed from cart',
                        'status' => Response::HTTP_OK
                    ];
                } else {
                    Log::channel('error_log')->error('Database Error :: Error removing dish from cart');
                    return [
                        'success' => false,
                        'msg' => 'Dish could not be removed',
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ];
                }
            }
        }

        $result = DB::table('basket_dishes')
            ->where('basket_id', $basket->basket_id)
            ->where('dish_id', $dish_id)
            ->update([
                'quantity' => $quantity
            ]);

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Dish updated in cart',
                'status' => Response::HTTP_OK
            ];
        } else {
            Log::channel('error_log')->error('Database Error :: Error updating dish in cart');
            return [
                'success' => false,
                'msg' => 'Dish could not be updated',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function checkoutBasket($user_id, $restaurant_id) {
        DB::statement('START TRANSACTION');

        // Check if basket exists
        $basket = $this->getBasket($user_id, $restaurant_id);
        if (!$basket) {
            return [
                'success' => false,
                'msg' => 'Basket does not exist',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        // Check if basket has items
        $basketDishes = $this->getBasketDishes($user_id, $restaurant_id);
        if (count($basketDishes) < 1) {
            return [
                'success' => false,
                'msg' => 'Basket is empty',
                'status' => Response::HTTP_CONFLICT
            ];
        }

        // Create an order
        $order = $this->orderService->createOrder($user_id, $restaurant_id);

        if ($order['success']) {
            $order = $this->orderService->getLatestOrder($user_id, $restaurant_id);

            // Move basket items to order items
            foreach ($basketDishes as $basketDish) {
                $result = $this->orderService->createOrderDish($order->order_id, $basketDish->dish_id, $basketDish->quantity, $basketDish->dish_price);

                // $this->orderService->createOrderDish($order->order_id, $basketDish->dish_id, 0, $basketDish->dish_price);
                if (!$result['success']) {
                    Log::channel('error_log')->error('Database Error :: Error moving basket items to order items');
                    DB::statement('ROLLBACK');
                    return $result;
                }
            }

            if (!$this->deleteBasket($user_id, $restaurant_id)) {
                Log::channel('error_log')->error('Database Error :: Error deleting basket');
                DB::statement('ROLLBACK');
                return [
                    'success' => false,
                    'msg' => 'Basket could not be deleted',
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
            }

            Log::channel('access_log')->info("{$user_id} placed an order");
            DB::statement('COMMIT');
            return [
                'success' => true,
                'msg' => 'Order created',
                'status' => Response::HTTP_CREATED
            ];
        } else {
            Log::channel('error_log')->error('Database Error :: Error creating order');
            DB::statement('ROLLBACK');
            return [
                'success' => false,
                'msg' => 'Order could not be created',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }
}