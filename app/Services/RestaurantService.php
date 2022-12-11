<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Tables\RestaurantTable;

class RestaurantService {
    protected $restaurantTable;

    public function __construct(RestaurantTable $restaurantTable) {
        $this->restaurantTable = $restaurantTable;
    }

    public function getAllRestaurants() {
        return $this->restaurantTable->index();
    }

    public function getRestaurantById($id) {
        return DB::table('restaurants')->where('restaurant_id', $id)->first();
    }

    public function getRestaurantByNameCity($name, $city) {
        return DB::table('restaurants')->where('restaurant_name', $name)->where('restaurant_city', $city)->first();
    }

    public function getRestaurantByContact($contact) {
        return DB::table('restaurants')->where('restaurant_contact', $contact)->first();
    }

    public function addRestaurant($data, Request $request) {
        if ($this->getRestaurantById($request->user()->user_id)) {
            return [
                'success' => false,
                'msg' => 'Restaurant already exists',
                'status' => Response::HTTP_CONFLICT
            ];
        }

        if ($this->getRestaurantByNameCity($data['restaurant_name'], $data['restaurant_city'])) {
            return [
                'success' => false,
                'msg' => 'Restaurant already exists',
                'status' => Response::HTTP_CONFLICT 
            ];
        }

        if ($this->getRestaurantByContact($data['restaurant_contact'])) {
            return [
                'success' => false,
                'msg' => 'Restaurant contact already exists',
                'status' => Response::HTTP_CONFLICT
            ];
        }

        $result = DB::table('restaurants')->insert([
            'restaurant_id' => $request->user()->user_id,
            'restaurant_name' => $data['restaurant_name'],
            'restaurant_lat' => $data['restaurant_lat'],
            'restaurant_lng' => $data['restaurant_lng'],
            'restaurant_city' => $data['restaurant_city'],
            'restaurant_contact' => $data['restaurant_contact']
        ]);

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Restaurant added successfully',
                'status' => Response::HTTP_CREATED
            ];
        }
        Log::error('RestaurantService::addRestaurant() -> Restaurant not added');
        return [
            'success' => false,
            'msg' => 'Restaurant could not be added',
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR
        ];
    }
}