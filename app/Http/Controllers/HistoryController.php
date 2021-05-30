<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\HistoryRepositoryInterface;

class HistoryController extends Controller
{
    private $history;

    public function __construct(HistoryRepositoryInterface $history_repo)
    {
        $this->history = $history_repo;
    }

    public function get(Request $request)
    {
        $histories = $request->all();
        $histories = $this->history->getAllHistories();
        return response()->json($histories, 200);
    }
}
