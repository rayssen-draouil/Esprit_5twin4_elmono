<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        return match (strtolower((string) $request->user()->role)) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager', 'gestionnaire' => redirect()->route('manager.dashboard'),
            default => redirect()->route('citizen.dashboard'),
        };
    }

    public function manager(): View { return view('dashboards.manager'); }
    public function citizen(): View { return view('dashboards.citizen'); }
}
