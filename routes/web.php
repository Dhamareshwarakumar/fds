<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyBasketItems;



$router->get('/', function () {
    return response()->json([
        'msg' => 'Welcome to Zomato Restaurant API Services'
    ]);
});

// Restaurant Routes
$router->get('/api/restaurants', 'RestaurantController@index');
$router->get('/api/restaurants/{id}', 'RestaurantController@show');
$router->post('/api/restaurants', [
    'middleware' => ['auth', 'restaurant_validation'],
    'uses' => 'RestaurantController@store'
]);

// Dish Routes
$router->get('/api/restaurants/{restaurant_id}/dishes', 'DishController@showDishesByRestaurant');
$router->post('/api/dishes', [
    'middleware' => ['auth', 'dish_validation'],
    'uses' => 'DishController@store'
]);
$router->delete('/api/dishes/{dish_id}', [
    'middleware' => ['auth'],
    'uses' => 'DishController@destroy'
]);

// Basket Routes
$router->get('/api/baskets/{restaurant_id}', [
    'middleware' => ['auth'],
    'uses' => 'BasketController@index'
]);

$router->post('/api/baskets', [
    'middleware' => ['auth', 'basket_validation'],
    'uses' => 'BasketController@store'
]);

$router->put('/api/baskets/{dish_id}/{increment}', [
    'middleware' => ['auth'],
    'uses' => 'BasketController@incrementDish'
]);

$router->get('/api/baskets/{restaurant_id}/checkout', [
    'middleware' => ['auth'],
    'uses' => 'BasketController@checkout'
]);

// Order Routes
$router->get('/api/orders', [
    'middleware' => ['auth'],
    'uses' => 'OrderController@index'
]);

$router->get('/api/orders/report', [
    'middleware' => ['auth'],
    'uses' => 'OrderController@report'
]);

// Dining Routes
$router->get('/api/dinings/{restaurant_id}', [
    'middleware' => ['auth'],
    'uses' => 'DiningController@index'
]);

$router->put('/api/dinings/{dining_id}', [
    'middleware' => ['auth'],
    'uses' => 'DiningController@update'
]);

$router->put('/api/dinings2/{dining_id}', [
    'middleware' => ['auth'],
    'uses' => 'DiningController@update2'
]);




// Auth Routes
$router->post('api/auth/login', 'AuthController@login');
$router->post('api/auth/register', 'AuthController@register');
$router->post('api/auth/addrestaurantuser', [
    'middleware' => 'auth',
    'uses' => 'AuthController@addRestaurantUser'
]);
$router->get('api/auth/users', [
    'middleware' => 'auth',
    'uses' => 'AuthController@getAllUsers'
]);