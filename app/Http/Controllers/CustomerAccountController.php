<?php

namespace App\Http\Controllers;

use App\Models\CustomerAccount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PDF;

class CustomerAccountController extends Controller
{
    public function index()
    {
        $customer_accounts = CustomerAccount::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($ca) => [
                'id' => $ca->id,
                'name' => $ca->name,
                'email' => $ca->email,
                'created_at' => $ca->created_at->format('Y-m-d'),
                'products' => json_decode($ca->products),
                'services' => json_decode($ca->services),
            ]);

        return Inertia::render('CustomerAccount/Index', [
            'customer_accounts' => $customer_accounts,
        ]);
    }

    public function create()
    {
        return Inertia::render('CustomerAccount/Edit', [
            'edit_mode' => false,
            'product_options' => product_options(),
            'service_options' => service_options(),
        ]);
    }


    public function edit($id)
    {
        $ca = CustomerAccount::find($id);

        $selected_products = [];
        foreach (json_decode($ca->products) as $key => $item) {
            $selected_products[] = [
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'desc' => $item->desc,
            ];
        }

        $selected_services = [];
        foreach (json_decode($ca->services) as $key => $item) {
            $selected_services[] = $item->service_id;
        }

        return Inertia::render('CustomerAccount/Edit', [
            'customer_account' => $ca,
            'product_options' => product_options(),
            'service_options' => service_options(),
            'selected_products' => $selected_products,
            'selected_services' => $selected_services,
            'edit_mode' => true,
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'account_type' => 'required',
            'name' => 'required',
            'phone_home' => 'required',
            'phone_mobile' => 'required',
            'email' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'services' => 'required',
            'payment_method' => 'required',
            'motor_warranty' => 'required',
            'frame_warranty' => 'required',
            'electronics_warranty' => 'required',
            'comments' => 'required',
            'products.*.product_id' => 'required',
            'products.*.qty' => 'required',
            'products.*.desc' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $products_array = [];
            foreach ($request->products as $key => $value) {
                $products_array[] = [
                    'product_id' => $value['product_id'],
                    'name' => product_name($value['product_id']),
                    'qty' => $value['qty'],
                    'desc' => $value['desc']
                ];
            }

            $services_array = [];
            foreach ($request->services as $key => $value) {
                $services_array[] = [
                    'service_id' => $value,
                    'name' => service_name($value),
                ];
            }

            $data = [
                'account_type' => $request->account_type,
                'name' => $request->name,
                'phone_home' => $request->phone_home,
                'phone_mobile' => $request->phone_mobile,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'payment_method' => $request->payment_method,
                'motor_warranty' => $request->motor_warranty,
                'frame_warranty' => $request->frame_warranty,
                'electronics_warranty' => $request->electronics_warranty,
                'comments' => $request->comments,
                'products' => json_encode($products_array),
                'services' => json_encode($services_array),
            ];

            CustomerAccount::updateOrCreate(['id' => $request->ca_id], $data);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
        }


        return Redirect::route('customer-account.index');
    }

    public function pdf($id)
    {
        $ca = CustomerAccount::find($id);

        view()->share([
            'ca' => $ca,
        ]);

        $pdf = PDF::loadView('pdf.customer-account');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('customer-account.pdf');
    }
}
