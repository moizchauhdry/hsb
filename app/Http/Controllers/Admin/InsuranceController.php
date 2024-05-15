<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InsuranceController extends Controller
{
    public function index()
    {
        $insurances = Insurance::orderBy('id', 'desc')->paginate(10)
            ->withQueryString();

        return Inertia::render('Insurance/Index', [
            'insurances' => $insurances
        ]);
    }
}
