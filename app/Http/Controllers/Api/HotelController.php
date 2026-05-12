<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HotelController extends Controller
{
    // ==========================================
    // GET ALL ROOM TYPES
    // ==========================================
    public function getRooms()
    {
        $rooms = RoomType::all();

        return response()->json([
            'status'  => true,
            'message' => 'List tipe kamar',
            'data'    => $rooms
        ], 200);
    }

    // ==========================================
    // CREATE RESERVATION
    // ==========================================
    public function storeReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|exists:users,id',
            'room_type_id'   => 'required|exists:room_types,id',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'check_in'       => 'required|date|after_or_equal:today',
            'check_out'      => 'required|date|after:check_in',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $room = RoomType::find($request->room_type_id);

        if (!$room) {
            return response()->json([
                'status'  => false,
                'message' => 'Room tidak ditemukan'
            ], 404);
        }

        $checkIn  = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        $nights = $checkIn->diffInDays($checkOut);

        $totalPrice = $nights * $room->price_per_night;

        $reservation = Reservation::create([
            'user_id'        => $request->user_id,
            'room_type_id'   => $request->room_type_id,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'check_in'       => $request->check_in,
            'check_out'      => $request->check_out,
            'total_price'    => $totalPrice,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Reservasi berhasil dibuat',
            'data'    => $reservation
        ], 201);
    }

    // ==========================================
    // GET ALL RESERVATIONS
    // ==========================================
    public function getAllReservations()
    {
        $reservations = Reservation::all();

        return response()->json([
            'status'  => true,
            'message' => 'List reservasi',
            'data'    => $reservations
        ], 200);
    }

    // ==========================================
    // GET RESERVATION BY ID
    // ==========================================
    public function getReservationById($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail reservasi',
            'data'    => $reservation
        ], 200);
    }

    // ==========================================
    // GET USER RESERVATIONS
    // ==========================================
    public function getUserReservations($user_id)
    {
        $reservations = Reservation::where('user_id', $user_id)->get();

        if ($reservations->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'Reservasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Reservasi user berhasil diambil',
            'data'    => $reservations
        ], 200);
    }

    // ==========================================
    // DELETE RESERVATION
    // ==========================================
    public function deleteReservation($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $reservation->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Reservasi berhasil dibatalkan'
        ], 200);
    }

    // ==========================================
    // UPDATE RESERVATION (PUT)
    // ==========================================
    public function updateReservation(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $reservation->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Reservasi berhasil diupdate',
            'data'    => $reservation
        ], 200);
    }

    // ==========================================
    // UPDATE RESERVATION (PATCH)
    // ==========================================
    public function patchReservation(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $reservation->fill($request->all());
        $reservation->save();

        return response()->json([
            'status'  => true,
            'message' => 'Reservasi berhasil diupdate (PATCH)',
            'data'    => $reservation
        ], 200);
    }
}