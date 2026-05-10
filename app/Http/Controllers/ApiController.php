<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\AddOn;
use App\Models\Voucher;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok.'],
            ]);
        }

        $token = $user->createToken($request->device_name ?? 'mobile_app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function services()
    {
        return response()->json(Service::all());
    }

    public function addOns()
    {
        return response()->json(AddOn::all());
    }

    public function vouchers()
    {
        return response()->json(Voucher::where('is_active', true)->get());
    }
    
    public function dashboard(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['Waiting', 'Received', 'In Progress'])->count(),
            'completed_orders' => Order::where('status', 'Completed')->count(),
            'recent_orders' => Order::latest()->take(5)->get(),
        ];
        
        return response()->json($stats);
    }
}
