<?php

namespace App\Repositories;

use App\Models\History;

class HistoryRepository implements HistoryRepositoryInterface
{
    public function getAllHistories()
    {
        return $histories = History::all();
    }
}
