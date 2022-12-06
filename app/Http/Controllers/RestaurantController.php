<?php

namespace App\Http\Controllers;

use App\Services\RestaurantService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\AdminValidator;
use App\Traits\ApiResponser;


class RestaurantController extends Controller {
    use ApiResponser;
    use AdminValidator;

    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService) {
        $this->restaurantService = $restaurantService;
    }

    /*
    * @route    :: GET /api/restaurants
    * @desc     :: Get all restaurants
    * @access   :: Public
    */
    public function index() {
        return $this->successResponse($this->restaurantService->getAllRestaurants());
    }

    /*
    * @route    :: GET /api/restaurants/{id}
    * @desc     :: Get restaurant by id
    * @access   :: Public
    */
    public function show($id) {
        $restaurant = $this->restaurantService->getRestaurantById($id);

        if ($restaurant) {
            return $this->successResponse($restaurant);
        }
        return $this->errorResponse('Restaurant not found', Response::HTTP_NOT_FOUND);
    }

    /*
    * @route    :: POST /api/restaurants
    * @desc     :: Add new restaurant
    * @access   :: Private:: Restaurant Owner
    */

    public function store(Request $request) {
        if (!$this->isRestaurantAdmin($request)) {
            return $this->errorResponse('You are not authorized to add restaurant', Response::HTTP_UNAUTHORIZED);
        }

        $result = $this->restaurantService->addRestaurant($request->json()->all(), $request);

        if ($result['success']) {
            return $this->successResponse($result['msg'], $result['status']);
        }
        return $this->errorResponse($result['msg'], $result['status']);
    }
}