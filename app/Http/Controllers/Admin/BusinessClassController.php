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
    public function index(Request $request)
    {
        $filter = [
            'search' => $request->search,
        ];

        $cobs = BusinessClass::query()
            ->select(
                'cob.id as cob_id',
                'cob.code as cob_code',
                'cob.class_name as cob_name',
                'd.name as department_name',
                'g.name as group_name',
            )
            ->from('business_classes as cob')
            ->join('departments as d', 'd.id', 'cob.department_id')
            ->join('groups as g', 'g.id', 'cob.group_id')
            ->orderBy('cob.id', 'desc')
            ->when($filter['search'], function ($q) use ($filter) {
                $q->where('id', $filter['search']);
                $q->orWhere('class_name', 'LIKE', '%' . $filter['search'] . '%');
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('BusinessClass/Index', [
            'cobs' => $cobs,
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
