<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusyResource;
use App\Models\Busy;
use Illuminate\Http\Request;

class BusyController extends Controller
{
    public function index()
    {
        $orderColumn = request('order_column', 'start_time');
        if (!in_array($orderColumn, ['id', 'date', 'start_time'])) {
            $orderColumn = 'start_time';
        }

        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        $busies = Busy::with('employee')
            ->when(request('search_employee'), function ($query) {
                $query->where('employee_id', request('search_employee'));
            })
            ->when(request('search_id'), function ($query) {
                $query->where('id', request('search_id'));
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);
        return BusyResource::collection($busies);
    }


}
