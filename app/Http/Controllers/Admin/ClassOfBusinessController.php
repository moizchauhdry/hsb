<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Models\ClassOfBusiness;
use App\Http\Controllers\Controller;

class ClassOfBusinessController extends Controller
{
    public function index()
    {
        $classOfBusinesses = ClassOfBusiness::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($classOfBusiness) => [
                'id' => $classOfBusiness->id,
                'b_class_name' => $classOfBusiness->b_class_name,
                'brokeage_rate_percentage' => $classOfBusiness->brokeage_rate_percentage,
                'insurance_id' => $classOfBusiness->insurance_id,
                'created_at' => $classOfBusiness->created_at->format('d-m-Y h:i A'),
            ]);

        $insurances = Insurance::select('id', 'name')->get();

        return Inertia::render('ClassOfBusiness/Index', [
            'classOfBusinesses' => $classOfBusinesses,
            'insurances' => $insurances,
        ]);
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'b_class_name' => ['required', 'string', 'min:5', 'max:50'],
            'brokeage_rate_percentage' => ['required'],
            'insurance_name' => ['required'],
        ]);

        $insuranceNamesArray = implode(',', $request->insurance_name);

        $data = [
            'b_class_name' => $request->b_class_name,
            'brokeage_rate_percentage' => $request->brokeage_rate_percentage,
            'insurance_name' => $insuranceNamesArray,
        ];

        $classOfBusiness = ClassOfBusiness::create($data);
    }

    public function update(Request $request)
    {        
        $classOfBusiness = ClassOfBusiness::findOrFail($request->class_of_business_id);

        $validate = $request->validate([
            'class_of_business_id' => ['required'],
            'b_class_name' => ['required', 'string', 'min:5', 'max:50'],
            'brokeage_rate_percentage' => ['required'],
            'insurance_name' => ['required'],
        ]);

        $insuranceNamesArray = implode(',', $request->insurance_name);

        $data = [
            'b_class_name' => $request->b_class_name,
            'brokeage_rate_percentage' => $request->brokeage_rate_percentage,
            'insurance_name' => $insuranceNamesArray,
        ];

        $classOfBusiness->update($data);
    }
}
