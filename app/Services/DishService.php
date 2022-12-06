<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\RestaurantService;
use Illuminate\Support\Facades\Log;

class DishService {

    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService) {
        $this->restaurantService = $restaurantService;
    }

    public function getDishById($dish_id) {
        return DB::table('dishes')
            ->where('dish_id', $dish_id)
            ->first();
    }

    public function getDishesByRestaurant($restaurantId) {
        return DB::table('dishes')
            ->where('restaurant_id', $restaurantId)
            ->get();
    }

    public function getDishByNameRestaurant($dish_name, $restaurant_id) {
        return DB::table('dishes')->where('dish_name', $dish_name)->where('restaurant_id', $restaurant_id)->first();
    }

    public function addDish($data, Request $request) {
        // Check if restaurant exists
        if (!$this->restaurantService->getRestaurantById($request->user()->user_id)) {
            return [
                'success' => false,
                'msg' => 'Restaurant does not exist',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        // Check if dish already exists
        if ($this->getDishByNameRestaurant($data['dish_name'], $request->user()->user_id)) {
            return [
                'success' => false,
                'msg' => 'Dish already exists',
                'status' => Response::HTTP_CONFLICT
            ];
        }

        $result = DB::table('dishes')->insert([
            'dish_name' => $data['dish_name'],
            'dish_price' => $data['dish_price'],
            'dish_description' => $data['dish_description'],
            'dish_image' => $data['dish_image'],
            'dish_type' => $data['dish_type'],
            'restaurant_id' => $request->user()->user_id
        ]);

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Dish added successfully',
                'status' => Response::HTTP_CREATED
            ];
        }

        Log::error('DishService::addDish() - Error adding dish');
        return [
            'success' => false,
            'msg' => 'Dish could not be added',
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR
        ];
    }

    public function deleteDish($dish_id, Request $request) {
        // Check if dish exists
        $dish = $this->getDishById($dish_id);
        if (!$dish) {
            return [
                'success' => false,
                'msg' => 'Dish does not exist',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        // Check if that dish belongs to the restaurant owned by the requesting Admin
        if ($dish->restaurant_id != $request->user()->user_id) {
            return [
                'success' => false,
                'msg' => 'You are not authorized to delete this dish',
                'status' => Response::HTTP_UNAUTHORIZED
            ];
        }

        $result = DB::table('dishes')->where('dish_id', $dish_id)->delete();

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Dish deleted successfully',
                'status' => Response::HTTP_OK
            ];
        }

        Log::error('DishService::deleteDish() - Error deleting dish');
        return [
            'success' => false,
            'msg' => 'Dish could not be deleted',
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR
        ];
    }
}