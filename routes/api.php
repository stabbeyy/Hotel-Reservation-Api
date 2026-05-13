<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HotelController;

// 1. Mengambil semua tipe kamar
// Endpoint: http://127.0.0.1:8000/api/type-room
Route::get('/type-room', [HotelController::class, 'getRooms']);

// 2. Mengambil seluruh data reservasi
// Endpoint: http://127.0.0.1:8000/api/reservations
Route::get('/reservations', [HotelController::class, 'getAllReservations']);

// 3. Mengambil reservasi berdasarkan ID
// Endpoint: http://127.0.0.1:8000/api/reservations/{id}
Route::get('/reservations/{id}', [HotelController::class, 'getReservationById']);

// 4. Cek ketersediaan kamar 
// Endpoint: http://127.0.0.1:8000/api/rooms/availability
Route::get('/rooms/availability', [HotelController::class, 'checkAvailability']);

// 5. Mengambil seluruh data reservasi milik user tertentu (INI YG EROR brohh : 500 Internal Server Error)
// Endpoint: http://127.0.0.1:8000/api/users/{user_id}/reservations
Route::get('/users/{user_id}/reservations', [HotelController::class, 'getUserReservations']);

// 6. Membuat reservasi baru (INI YG EROR brohh : 422 Unprocessable Content)
// Endpoint: http://127.0.0.1:8000/api/reservations
Route::post('/reservations', [HotelController::class, 'storeReservation']);

// 7. Registrasi user
// Endpoint: http://127.0.0.1:8000/api/signup
Route::post('/signup', [HotelController::class, 'signup']);

// 8. Login user
// Endpoint: http://127.0.0.1:8000/api/auth/login
Route::post('/auth/login', [HotelController::class, 'login']);

// 9. Menghapus reservasi (Cancel Booking)
// Endpoint: http://127.0.0.1:8000/api/reservations/{id}
Route::delete('/reservations/{id}', [HotelController::class, 'deleteReservation']);

// 10. Menghapus user
// Endpoint: http://127.0.0.1:8000/api/users/{user_id}
Route::delete('/users/{user_id}', [HotelController::class, 'deleteUser']);

// 11. Update reservasi (PUT dan PATCH)
// Endpoint: http://127.0.0.1:8000/api/reservations/{id}
Route::put('/reservations/{id}', [HotelController::class, 'updateReservation']);
Route::patch('/reservations/{id}', [HotelController::class, 'patchReservation']);

// 12. Mengambil booking/reservasi terbaru
// Endpoint: http://127.0.0.1:8000/api/reservations/latest
Route::get('/reservations/latest', [HotelController::class, 'getLatestReservations']);