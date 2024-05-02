<?php

namespace App\Http\Controllers;

use App\Mail\WorkOrderCompletedMail;
use App\Mail\WorkOrderGeneratedMail;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderAddress;
use App\Models\WorkOrderItem;
use App\Notifications\WorkOrderInvoiceNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use PDF;

class WorkOrderController extends Controller
{
    public function index()
    {
        $work_orders = WorkOrder::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($work_order) => [
                'id' => $work_order->id,
                'wo_customer_name' => $work_order->billing_address->wo_addr_customer_name,
                'wo_bike_category' => $work_order->wo_bike_category,
                'wo_bike_model' => $work_order->wo_bike_model,
                'wo_date' => Carbon::parse($work_order->created_at)->format('d-m-Y'),
                'wo_return_date' => $work_order->wo_return_date,
                'wo_grand_total' => $work_order->wo_grand_total,
                'wo_completed' => $work_order->wo_completed,
                'wo_status' => $work_order->wo_status == 2 ? 'Pickup' : 'Pending',
            ]);

        return Inertia::render('WorkOrder/Index', [
            'work_orders' => $work_orders,
        ]);
    }

    public function create()
    {
        return Inertia::render('WorkOrder/Edit', [
            'work_order' => null,
            'edit_mode' => false,
        ]);
    }

    public function edit($id)
    {
        $work_order = WorkOrder::with(['billing_address', 'items'])->find($id);

        return Inertia::render('WorkOrder/Edit', [
            'work_order' => $work_order,
            'edit_mode' => true,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate(
            [
                'wo_title' => 'required',
                // 'wo_customer_name' => 'required',
                'wo_bike_category' => 'required',
                'wo_bike_color' => 'required',
                'wo_sr_no' => 'required',
                'wo_po_no' => 'required',
                'wo_bike_warranty' => 'required',
                'wo_bike_model' => 'required',
                'wo_return_date' => 'required',
                'wo_status' => 'required',
                'wo_notes' => 'nullable',

                'wo_addr_customer_name' => 'required',
                'wo_addr_str_address' => 'required',
                'wo_addr_city' => 'required',
                'wo_addr_state' => 'required',
                'wo_addr_zipcode' => 'required',
                'wo_addr_phone' => 'required',
                'wo_addr_email' => 'required|email',

                'wo_items.*.wo_item_category_id' => 'required',
                'wo_items.*.wo_item_name' => 'required',
                'wo_items.*.wo_item_hours' => 'required|numeric|gte:1',
                'wo_items.*.wo_item_rate' => 'required|numeric|gte:0',
                'wo_items.*.wo_item_line_total' => 'required|numeric|gte:0',

                'wo_subtotal' => 'required|numeric',
                'wo_discount' => 'required|numeric',
                'wo_tax_rate' => 'required|numeric',
                'wo_tax_total' => 'required|numeric',
                'wo_grand_total' => 'required|numeric',
            ],
            [
                'required' => 'The field is required.',
                'email' => 'The field must be a valid email.',
            ]
        );

        try {

            DB::beginTransaction();

            $work_order = WorkOrder::updateOrCreate(['id' => $request->wo_id], [
                'wo_title' => $request->wo_title,
                // 'wo_customer_name' => $request->wo_customer_name,
                'wo_bike_category' => $request->wo_bike_category,
                'wo_bike_model' => $request->wo_bike_model,
                'wo_bike_color' => $request->wo_bike_color,
                'wo_sr_no' => $request->wo_sr_no,
                'wo_po_no' => $request->wo_po_no,
                'wo_bike_warranty' => $request->wo_bike_warranty,
                'wo_return_date' => Carbon::parse($request->wo_return_date)->format('Y-m-d'),
                'wo_status' => $request->wo_status,
                'wo_notes' => $request->wo_notes,

                'wo_subtotal' => $request->wo_subtotal,
                'wo_discount' => $request->wo_discount,
                'wo_tax_rate' => $request->wo_tax_rate,
                'wo_tax_total' => $request->wo_tax_total,
                'wo_grand_total' => $request->wo_grand_total,
            ]);

            WorkOrderAddress::updateOrCreate(['wo_id' => $request->wo_id], [
                'wo_id' => $work_order->id,
                'wo_addr_customer_name' => $request->wo_addr_customer_name,
                'wo_addr_str_address' => $request->wo_addr_str_address,
                'wo_addr_city' => $request->wo_addr_city,
                'wo_addr_state' => $request->wo_addr_state,
                'wo_addr_zipcode' => $request->wo_addr_zipcode,
                'wo_addr_phone' => $request->wo_addr_phone,
                'wo_addr_email' => $request->wo_addr_email,
            ]);

            WorkOrderItem::where('wo_id', $work_order->id)->delete();
            foreach ($request->wo_items as $key => $item) {
                WorkOrderItem::create([
                    'wo_id' => $work_order->id,
                    'wo_item_category_id' => $item['wo_item_category_id'],
                    'wo_item_name' => $item['wo_item_name'],
                    'wo_item_hours' => $item['wo_item_hours'],
                    'wo_item_rate' => $item['wo_item_rate'],
                    'wo_item_line_total' => $item['wo_item_line_total'],
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
        }

        try {
            if (!$request->wo_id) {
                Mail::to($request->wo_addr_email)->send(new WorkOrderGeneratedMail($work_order));
            }

            $work_order_address = WorkOrderAddress::select('wo_addr_email as email')->where('wo_addr_email', $request->wo_addr_email)->first();
            Notification::send($work_order_address, new WorkOrderInvoiceNotification($work_order));
        } catch (\Throwable $th) {
            // throw $th;
        }

        return Redirect::route('work-order.index');
    }

    public function pdf($id)
    {
        $wo = WorkOrder::find($id);

        view()->share([
            'wo' => $wo,
        ]);

        $pdf = PDF::loadView('pdf.work-order');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('work-order.pdf');
    }

    public function complete(Request $request)
    {
        try {
            $wo = WorkOrder::find($request->wo_id);
            $wo->update([
                'wo_completed' => 1,
                'wo_completed_at' => Carbon::parse(Carbon::now()->format('Y-m-d')),
                'wo_completed_by' => auth()->id(),
                'wo_status' => 2,
            ]);

            Mail::to($wo->billing_address->wo_addr_email)->send(new WorkOrderCompletedMail($wo));
        } catch (\Throwable $th) {
            // throw $th;
        }
    }

    public function delete(Request $request)
    {
        try {
            $wo = WorkOrder::find($request->wo_id);
            $wo->delete();
        } catch (\Throwable $th) {
            // throw $th;
        }
    }

    public function label($id)
    {
        $wo = WorkOrder::find($id);

        view()->share([
            'wo' => $wo,
        ]);

        $pdf = PDF::loadView('pdf.wo-label');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('wo-label.pdf');
    }

    public function pending()
    {
        $work_orders = WorkOrder::orderBy('created_at', 'asc')->paginate(100);

        return Inertia::render('WorkOrder/Pending', [
            'work_orders' => $work_orders,
        ]);
    }

    public function detail($id)
    {
        $work_order = WorkOrder::with(['billing_address', 'items'])->find($id);

        return Inertia::render('WorkOrder/Detail', [
            'work_order' => $work_order,
            'edit_mode' => true,
        ]);
    }
}
