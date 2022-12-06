<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;


trait AdminValidator {
    use ApiResponser;

    public function isSuperAdmin(Request $request) {
        if ($request->user()->role != 4) {
            return false;
        }
        return true;
    }
    
    public function isRestaurantAdmin(Request $request) {
        error_log($request->user()->role);
        if ($request->user()->role != 1) {
            return false;
        }
        return true;
    }
}