<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Closure;


class RestaurantValidation {
    use ApiResponser;

    public function handle($request, Closure $next) {
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $rules = [
                'restaurant_name' => 'required|max:255',
                'restaurant_lat' => 'required|numeric',
                'restaurant_lng' => 'required|numeric',
                'restaurant_contact' => 'required|max:255',
                'restaurant_city' => 'required|max:255'
            ];

            $validator = Validator::make($request->json()->all(), $rules);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        return $next($request);
    }
}