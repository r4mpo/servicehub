<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private array $responseData;
    private DashboardService $dashboardService;

        public function __construct(DashboardService $dashboardService)
    {
        parent::__construct();
        $this->dashboardService = $dashboardService;
    }


    public function index(Request $request)
    {
        $this->responseData = $this->dashboardService->dashboard($request);
        return $this->responseInertia($this->responseData);
    }
}