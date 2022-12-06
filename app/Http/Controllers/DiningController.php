<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Services\DiningService;


class DiningController extends Controller {
    use ApiResponser;

    protected $diningService;

    public function __construct(DiningService $diningService) {
        $this->diningService = $diningService;
    }

    /*
    * @route    :: GET /api/dining/{restaurant_id}
    * @desc     :: Get dining info by restaurant id
    * @access   :: Public
    */
    public function index($restaurant_id) {
        return $this->diningService->getDiningByRestaurantId($restaurant_id);
    }

    /*
    * @route    :: PUT /api/dining/{dining_id}
    * @desc     :: Update dining info
    * @access   :: Public
    */
    public function update(Request $request, $dining_id) {
        $result = $this->diningService->updateDining($request, $dining_id);

        if ($result['success']) {
            return $this->successResponse($result['msg'], $result['status']);
        }
        return $this->errorResponse($result['msg'], $result['status']);
    }

    public function update2(Request $request, $dining_id) {
        $result = $this->diningService->updateDining2($request, $dining_id);

        if ($result['success']) {
            return $this->successResponse($result['msg'], $result['status']);
        }
        return $this->errorResponse($result['msg'], $result['status']);
    }
}