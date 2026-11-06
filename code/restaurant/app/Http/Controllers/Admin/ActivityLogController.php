<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data activity log berhasil diambil',
            'data' => $logs
        ]);
    }

    public function show(ActivityLog $activityLog)
    {
        return response()->json([
            'message' => 'Data activity log berhasil diambil',
            'data' => $activityLog->load('user')
        ]);
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return response()->json([
            'message' => 'Activity log berhasil dihapus'
        ]);
    }
}