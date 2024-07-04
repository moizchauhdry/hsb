<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Models\BusinessClass;
use App\Http\Controllers\Controller;
use App\Models\BusinessClassInsurance;
use App\Models\Department;

class BusinessClassController extends Controller
{
    public function index()
    {
        $businessClasses = BusinessClass::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($businessClass) => [
                'id' => $businessClass->id,
                'class_name' => $businessClass->class_name,
                'department_name' => $businessClass->department->name,
                'percentage' => $businessClass->percentage,
                'created_at' => $businessClass->created_at->format('d-m-Y h:i A'),
            ]);



        return Inertia::render('BusinessClass/Index', [
            'businessClasses' => $businessClasses,
            'businessClass' => NULL,
        ]);
    }

    public function create()
    {
        $insurances = Insurance::select('id as value', 'name as label')->get()->toArray();
        $departments = Department::select('id', 'name')->get()->toArray();

        $data = [
            'insurances' => $insurances,
            'departments' => $departments,
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => ['required', 'string', 'min:3', 'max:50'],
            'percentage' => ['required'],
            'insurance_id*' => ['required'],
            'department_id' => ['required'],
        ]);

        $data = [
            'class_name' => $request->class_name,
            'percentage' => $request->percentage,
            'department_id' => $request->department_id,
        ];

        $businessClasses = BusinessClass::create($data);

        if ($request->insurance_id) {
            $insuranceIDs = $request->insurance_id;
            foreach ($insuranceIDs as $insuranceID) {
                $data = [
                    'insurance_id' => $insuranceID,
                    'business_class_id' => $businessClasses->id,
                ];

                BusinessClassInsurance::create($data);
            }
        }
    }

    public function edit($id)
    {
        $business_cls = BusinessClass::with('businessInsurance')->where('id', $id)->first();
        $insurances = Insurance::select('id as value', 'name as label')->get()->toArray();
        $departments = Department::select('id', 'name')->get()->toArray();
        $selected_insurers = BusinessClassInsurance::query()
            ->select('i.id as i_id')
            ->join('insurances as i', 'i.id', 'business_class_insurances.insurance_id')
            ->where('business_class_id', $id)
            ->get()->pluck('i_id');

        $data = [
            'insurances' => $insurances,
            'departments' => $departments,
            'business_cls' => $business_cls,
            'selected_insurers' => $selected_insurers,
        ];

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $business_cls = BusinessClass::where('id', $request->business_class_id)->first();

        $validate = $request->validate([
            'class_name' => ['required', 'string', 'min:5', 'max:50'],
            'percentage' => ['required'],
            'insurance_id*' => ['required'],
            'department_id' => ['required'],
        ]);

        $data = [
            'class_name' => $request->class_name,
            'percentage' => $request->percentage,
            'department_id' => $request->department_id,
        ];

        $business_cls->update($data);

        if ($request->insurance_id) {
            BusinessClassInsurance::where('business_class_id', $request->business_class_id)->delete();
            $insuranceIDs = $request->insurance_id;
            foreach ($insuranceIDs as $insuranceID) {
                $data = [
                    'insurance_id' => $insuranceID,
                    'business_class_id' => $request->business_class_id,
                ];
                BusinessClassInsurance::create($data);
            }
        }
    }
}
