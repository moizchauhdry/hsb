<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Mail\BookingMail;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PDF;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($booking) => [
                'id' => $booking->id,
                'user_name' => $booking->user_name,
                'user_email' => $booking->user_email,
                'user_phone' => $booking->user_phone,
                'pickup' => $booking->pickup,
                'destination' => $booking->destination,
                'price' => $booking->price,
                'booking_date' => Carbon::parse($booking->booking_date)->format('d-m-Y, h:i:A'),
            ]);

        return Inertia::render('Booking/Index', [
            'bookings' => $bookings,
        ]);
    }

    public function create()
    {
        return Inertia::render('Booking/Edit', [
            'booking' => null,
            'edit_mode' => false,
        ]);
    }

    public function edit($id)
    {
        $booking = Booking::find($id);

        return Inertia::render('Booking/Edit', [
            'booking' => $booking,
            'edit_mode' => true,
        ]);
    }

    public function update(BookingRequest $request)
    {
        $booking = Booking::updateOrCreate(['id' => $request->booking_id], $request->validated());

        try {
            if (!$request->booking_id) {
                Mail::to($booking->user_email)->send(new BookingMail($booking));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return Redirect::route('booking.index');
    }

    public function pdf($id)
    {
        $booking = Booking::find($id);

        view()->share([
            'booking' => $booking,
        ]);

        $pdf = PDF::loadView('pdf.booking');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('booking.pdf');
    }
}
