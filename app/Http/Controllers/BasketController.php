<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\BasketService;
use App\Traits\ApiResponser;


class BasketController extends Controller
{
    use ApiResponser;
    protected $basketService;

    public function __construct(BasketService $basketService)
    {
        $this->basketService = $basketService;
    }

    /*
    * @route    :: GET /api/baskets/{restaurant_id}
    * @desc     :: Get basket by restaurant id
    * @access   :: Private:: User
    */
    public function index($restaurant_id, Request $request)
    {
        return $this->basketService->getBasketDishes($request->user()->user_id, $restaurant_id);
    }

    /*
    * @route    :: POST /api/baskets
    * @desc     :: Add dishes to basket
    * @access   :: Private:: User
    */
    public function store(Request $request) {
        $result = $this->basketService->createBasketDish($request->user()->user_id, $request->json()->all()['dish_id'], $request->json()->all()['quantity']);
    
        if ($result['success']) {
            return $this->successResponse($result['msg'], $result['status']);
        } else {
            return $this->errorResponse($result['msg'], $result['status']);
        }
    }

    /*
    * @route    :: PUT /api/baskets/{dish_id}/{increment}
    * @desc     :: Increment quantity of dish in basket
    * @access   :: Private:: User
    */
    public function incrementDish($dish_id, $increment, Request $request) {
        $result = $this->basketService->updateBasketDish($request->user()->user_id, $dish_id, $increment);
    
        if ($result['success']) {
            return $this->successResponse($result['msg'], $result['status']);
        } else {
            return $this->errorResponse($result['msg'], $result['status']);
        }
    }

    /*
    * @route    :: GET /api/baskets/{restaurant_id}/checkout
    * @desc     :: Checkout basket
    * @access   :: Private:: User
    */

    public function checkout($restaurant_id, Request $request) {
        $result = $this->basketService->checkoutBasket($request->user()->user_id, $restaurant_id);
    
        if ($result['success']) {
            return $this->successResponse($result['msg'], $result['status']);
        } else {
            return $this->errorResponse($result['msg'], $result['status']);
        }
    }
}