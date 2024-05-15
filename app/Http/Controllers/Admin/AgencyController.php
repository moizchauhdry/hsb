<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Agency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = Agency::orderBy('id', 'desc')->paginate(10)
            ->withQueryString();

        return Inertia::render('Agency/Index', [
            'agencies' => $agencies
        ]);
    }
}
