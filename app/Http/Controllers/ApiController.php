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
        } catch (\Exception $e) {
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
        \Log::info('Deleting service ID: ' . $id);
        try {
            $service = Service::find($id);
            if ($service) $service->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error deleting service: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function addOns()
    {
        return response()->json(AddOn::all());
    }

    public function storeAddOn(Request $request)
    {
        $request->validate(['name' => 'required', 'price' => 'required']);
        $addon = AddOn::create($request->all());
        return response()->json($addon);
    }

    public function deleteAddOn($id)
    {
        \Log::info('Deleting add-on ID: ' . $id);
        try {
            $addon = AddOn::find($id);
            if (!$addon) {
                \Log::warning('Add-on not found: ' . $id);
                return response()->json(['message' => 'Not found'], 404);
            }
            $addon->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error deleting add-on: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
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
            'pending_orders' => Order::whereIn('status', ['Waiting', 'Received', 'In Progress'])->count(),
            'completed_orders' => Order::where('status', 'Completed')->count(),
            'recent_orders' => Order::latest()->take(5)->get(),
        ];
        
        return response()->json($stats);
    }
}
