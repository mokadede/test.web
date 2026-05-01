<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $orders = auth()->user()->orders()->latest()->get();
        return view('customer.dashboard', compact('services', 'orders'));
    }
}
