<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\ApiResponse;

class BannerController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $banners = Banner::where('is_active', true)
                ->orderBy('order', 'asc')
                ->get();

            return $this->successResponse($banners, 'Banners retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve banners: ' . $e->getMessage(), [], 500);
        }
    }
}
