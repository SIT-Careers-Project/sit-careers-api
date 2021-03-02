<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $dashboard;

    public function __construct(DashboardRepositoryInterface $dashboard_repo)
    {
        $this->dashboard = $dashboard_repo;
    }

    public function getStats(Request $request)
    {
        $stats = $request->all();
        $stats = $this->dashboard->getStats();
        return response()->json($stats, 200);
    }
}
