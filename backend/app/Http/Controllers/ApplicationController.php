<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'min:10', 'max:20'],
            'email' => ['required', 'email', 'max:150'],
            'message' => ['nullable', 'string', 'max:2000'],
            'agreement' => ['accepted'],
        ]);

        $application = Application::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'message' => $validated['message'] ?? null,
            'agreement' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Заявка успешно отправлена',
            'data' => $application,
        ], 201);
    }
}