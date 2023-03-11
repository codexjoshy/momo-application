<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\MomoSchedule;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMomoScheduleRequest;
use App\Http\Requests\UpdateMomoScheduleRequest;

class MomoScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $momoSchedules = MomoSchedule::query()->with('customers')
            ->latest()
            ->get();
        return view('admin.schedule.index', compact('momoSchedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.schedule.create');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MomoSchedule $momoSchedule)
    {
        $momoSchedule->delete();
        return back()->with('success', 'deleted successfully');

    }
}
