<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ExcelImportController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::with('errors')->orderBy('created_at', 'desc')->paginate(50);
        $import_completed_count = ErrorLog::where('import_completed', 1)->count();

        return Inertia::render('ExcelImport/Index', [
            'error_logs' => $batches,
            'import_completed_count' => $import_completed_count,
        ]);
    }
}
