<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Registration;
use App\Http\Requests\StoreRegistrationRequest;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $registrations = Registration::latest()->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $registrations
            ]);
        }

        return view('registrations.index', compact('registrations'));
    }

    public function create()
    {
        return view('registrations.create');
    }

    public function store(StoreRegistrationRequest $request)
    {
        $registration = Registration::create($request->validated());

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil disimpan',
                'data' => $registration
            ], 201);
        }

        return redirect()->route('registrations.index')->with('success', 'Pendaftaran berhasil disimpan');
    }

    public function show(Request $request, Registration $registration)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $registration
            ]);
        }

        return view('registrations.show', compact('registration'));
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $request->validate([
            'status' => 'required|in:Pending,Verified,Rejected'
        ]);

        $registration->update([
            'status' => $request->status
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Status pendaftaran berhasil diperbarui',
                'data' => $registration
            ]);
        }

        return redirect()->route('registrations.index')->with('success', 'Status pendaftaran berhasil diperbarui');
    }
}
