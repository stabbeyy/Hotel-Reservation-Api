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
    // KODINGAN PUNYA E BROH ISAT 
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

    public function storeReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
        $checkIn    = Carbon::parse($request->check_in);
        $checkOut   = Carbon::parse($request->check_out);
        $nights     = $checkIn->diffInDays($checkOut);
        $totalPrice = $nights * $room->price_per_night;

        $reservation = Reservation::create([
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
    // TAMBAHAN API SESUAI LAPORAN LKPD (TUGAS PRABOWOkuyy)
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

    public function getReservationById($id)
    {
        $reservation = Reservation::find($id);
        if(!$reservation) return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);

        return response()->json([
            'status'  => true,
            'message' => 'Detail reservasi',
            'data'    => $reservation
        ], 200);
    }

    public function checkAvailability(Request $request)
    {
        // Catatan buat broh Isat: Logika query ketersediaan kamar bisa diatur lebih lanjut di sini
        return response()->json([
            'status'  => true,
            'message' => 'Kamar tersedia',
            'data'    => [
                [
                    'room_id' => 101,
                    'type'    => 'Single Room',
                    'status'  => 'available'
                ]
            ]
        ], 200);
    }

    public function getUserReservations($user_id)
    {
        // Catatan buat kang Isat: Ubah query ini sesuai relasi User ke Reservasi nantinya
        $reservations = Reservation::where('user_id', $user_id)->get();
        return response()->json([
            'status'  => true,
            'message' => 'Reservasi user',
            'data'    => $reservations
        ], 200);
    }

    public function signup(Request $request)
    {
        // Catatan buat bro-kang Isat: Tambahkan logika insert User ke database di sini
        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil',
            'data'    => [
                'name'  => $request->name,
                'email' => $request->email
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        // Catatan buat mas Isat: Tambahkan logika Auth/Sanctum di sini
        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'token'   => 'abc123token'
        ], 200);
    }

    public function deleteReservation($id)
    {
        $reservation = Reservation::find($id);
        if($reservation) $reservation->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Reservasi berhasil dibatalkan'
        ], 200);
    }

    public function deleteUser($user_id)
    {
        // Catatan buat mas Isat: Tambahkan User::find($user_id)->delete()
        return response()->json([
            'status'  => true,
            'message' => 'User berhasil dihapus'
        ], 200);
    }

    public function updateReservation(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if(!$reservation) return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);

        $reservation->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Reservasi berhasil diupdate',
            'data'    => $reservation
        ], 200);
    }

    public function patchReservation(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if(!$reservation) return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);

        $reservation->fill($request->all())->save();

        return response()->json([
            'status'  => true,
            'message' => 'Reservasi berhasil diupdate (PATCH)',
            'data'    => $reservation
        ], 200);
    }

    public function getLatestReservations()
{
    $reservations = Reservation::latest()->take(5)->get();

    return response()->json([
        'status'  => true,
        'message' => 'List booking terbaru',
        'data'    => $reservations
    ], 200);
}
}
