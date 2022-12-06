<?php

namespace App\Http\Controllers;

use App\Services\DishService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Traits\AdminValidator;


class DishController extends Controller {
    use ApiResponser;
    use AdminValidator;

    protected $dishService;

    public function __construct(DishService $dishService) {
        $this->dishService = $dishService;
    }
    
    public function showDishesByRestaurant($restaurant_id) {
        return $this->dishService->getDishesByRestaurant($restaurant_id);
    }

    /*
    * @route    :: GET /api/dishes/{dish_id}
    * @desc     :: Get a dish by id
    * @access   :: Public
    */
    public function show($dish_id) {
        return $this->dishService->getDishById($dish_id);
    }

    /*
    * @route    :: POST /api/dishes
    * @desc     :: Add new dish
    * @access   :: Private:: Restaurant Owner
    */

    public function store(Request $request) {
        if (!$this->isRestaurantAdmin($request)) {
            return $this->errorResponse('You are not authorized to add dish', Response::HTTP_UNAUTHORIZED);
        }

        $result = $this->dishService->addDish($request->json()->all(), $request);

        if ($result['success']) {
            return $this->successResponse($result['msg'], $result['status']);
        }
        return $this->errorResponse($result['msg'], $result['status']);
    }

    /*
    * @route    :: DELETE /api/dishes/{id}
    * @desc     :: Delete dish
    * @access   :: Private:: Restaurant Owner
    */

    public function destroy($dish_id, Request $request) {
        if (!$this->isRestaurantAdmin($request)) {
            return $this->errorResponse('You are not authorized to delete dish', Response::HTTP_UNAUTHORIZED);
        }

        $result = $this->dishService->deleteDish($dish_id, $request);

        if ($result['success']) {
            return $this->successResponse($result['msg'], $result['status']);
        }
        return $this->errorResponse($result['msg'], $result['status']);
    }
}