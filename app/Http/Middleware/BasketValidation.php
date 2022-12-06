<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Closure;


class BasketValidation {
    use ApiResponser;

    public function handle($request, Closure $next) {
        if ($request->isMethod('post')) {
            $rules = [
                'dish_id' => 'required|uuid',
                'quantity' => 'required|numeric',
            ];

            $validator = Validator::make($request->json()->all(), $rules);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        return $next($request);
    }
}