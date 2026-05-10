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
use Illuminate\Support\Facades\Log;

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
        try {
            $services = Service::all();
            Log::info('Fetching all services. Count: ' . count($services));
            return response()->json($services);
        } catch (\Throwable $e) {
            Log::error('Error fetching services: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function storeService(Request $request)
    {
        \Log::info('Storing service: ', $request->all());
        try {
            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'category' => 'nullable|string',
                'estimated_days' => 'nullable|string',
            ]);

            $service = Service::create($request->all());
            return response()->json($service);
        } catch (\Throwable $e) {
            \Log::error('Error storing service: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) return response()->json(['message' => 'Not found'], 404);
        $service->update($request->all());
        return response()->json($service);
    }

    public function deleteService($id)
    {
        Log::info('Deleting service ID: ' . $id);
        try {
            $deleted = Service::where('id', $id)->delete();
            Log::info('Deleted service count: ' . $deleted);
            return response()->json(['success' => true, 'deleted' => $deleted]);
        } catch (\Throwable $e) {
            Log::error('Error deleting service: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function addOns()
    {
        try {
            $addons = AddOn::all();
            Log::info('Fetching all add-ons. Count: ' . count($addons));
            return response()->json($addons);
        } catch (\Throwable $e) {
            Log::error('Error fetching add-ons: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function storeAddOn(Request $request)
    {
        $request->validate(['name' => 'required', 'price' => 'required']);
        $addon = AddOn::create($request->all());
        return response()->json($addon);
    }

    public function deleteAddOn($id)
    {
        Log::info('Attempting to delete add-on ID: ' . $id);
        try {
            $deleted = AddOn::where('id', $id)->delete();
            Log::info('Deleted count: ' . $deleted);
            return response()->json(['success' => true, 'deleted' => $deleted]);
        } catch (\Throwable $e) {
            Log::error('CRITICAL ERROR deleting add-on: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
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
            'pending_orders' => Order::where('status', 'pending')->count(),
            'proses_orders' => Order::whereIn('status', ['waiting', 'proses'])->count(),
            'unpaid_orders' => Order::where('payment_status', 'unpaid')->count(),
            'recent_orders' => Order::latest()->take(10)->get(),
        ];
        
        return response()->json($stats);
    }
}
