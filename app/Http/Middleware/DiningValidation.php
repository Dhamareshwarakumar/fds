<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Closure;


class DiningValidation {
    use ApiResponser;

    public function handle($request, Closure $next) {
        if ($request->isMethod('put')) {
            $rules = [
                'dining_id' => 'required|UUID',
                'user_id' => 'required|UUID',
            ];

            $validator = Validator::make($request->json()->all(), $rules);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        return $next($request);
    }
}