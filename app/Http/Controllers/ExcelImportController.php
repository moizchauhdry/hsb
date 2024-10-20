<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ExcelImportController extends Controller
{
    public function index(Request $request)
    {   
        $error_logs = ErrorLog::where('type','excel_import')->paginate(50);
        $import_completed_count = ErrorLog::where('import_completed',1)->count();

        return Inertia::render('ExcelImport/Index', [
            'error_logs' => $error_logs,
            'import_completed_count' => $import_completed_count,
        ]);
    }
}
