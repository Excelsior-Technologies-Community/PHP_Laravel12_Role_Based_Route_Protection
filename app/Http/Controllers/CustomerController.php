<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        return view('customer.dashboard', compact('user'));
    }
}