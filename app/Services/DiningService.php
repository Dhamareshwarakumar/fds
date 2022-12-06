<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiningService {
    public function getDiningByRestaurantId($restaurant_id) {
        return DB::table('dining')
            ->where('restaurant_id', $restaurant_id)
            ->get();
    }

    public function getDiningById($dining_id) {
        return DB:: table('dining')
            ->where('dining_id', $dining_id)
            ->first();
    }

    public function updateDining($request, $dining_id) {
        // Check if dining exists
        $dining = $this->getDiningById($dining_id);
        
        if (!$dining) {
            return [
                'success' => false,
                'msg' => 'Dining does not exist',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }
        sleep(7);
        error_log('updateDining '.$dining->dining_status);
        
        // Check if Dining is available
        if ($dining->dining_status == '1') {
            return [
                'success' => false,
                'msg' => 'Dining is not available',
                'status' => Response::HTTP_BAD_REQUEST
            ];
        }
        error_log('updateDining'.$dining_id);
        error_log('updateDining'.$request->user()->user_id
    );

        // Update dining
        try {
            $result = DB::table('dining')
                ->where('dining_id', $dining_id)
                ->update([
                    "user_id" => $request->user()->user_id,
                    "dining_status" => "1"
                ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return [
                'success' => false,
                'msg' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Dining Booked successfully',
                'status' => Response::HTTP_OK
            ];
        }
        Log::error('Database Error :: Error booking dining');
        return [
            'success' => false,
            'msg' => 'Dining could not be updateds',
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR
        ];
    }

    public function updateDining2($request, $dining_id) {
        // Check if dining exists
        $dining = $this->getDiningById($dining_id);

        if (!$dining) {
            return [
                'success' => false,
                'msg' => 'Dining does not exist',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        // Check if Dining is available
        if ($dining->dining_status == '1') {
            return [
                'success' => false,
                'msg' => 'Dining is not available',
                'status' => Response::HTTP_BAD_REQUEST
            ];
        }

        // Update dining
        $result = DB::table('dining')
            ->where('dining_id', $dining_id)
            ->update([
                "user_id" => $request->user()->user_id,
                "dining_status" => "1"
            ]);

        if ($result) {
            return [
                'success' => true,
                'msg' => 'Dining Booked successfully',
                'status' => Response::HTTP_OK
            ];
        }
        Log::error('Database Error :: Error booking dining');
        return [
            'success' => false,
            'msg' => 'Dining could not be updated',
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR
        ];
    }
}