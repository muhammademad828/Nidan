<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'max:254'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['email'][0] ?? 'Invalid email.',
            ], 422);
        }

        $existing = Subscriber::where('email', $request->email)->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => App::getLocale() === 'ar'
                    ? 'أنت مشترك بالفعل!'
                    : 'You are already subscribed!',
            ]);
        }

        Subscriber::create([
            'email'  => strtolower(trim($request->email)),
            'source' => $request->input('source', 'footer'),
            'locale' => App::getLocale(),
        ]);

        return response()->json([
            'success' => true,
            'message' => App::getLocale() === 'ar'
                ? 'تم الاشتراك بنجاح! شكراً لانضمامك إلى عائلة NIDAN.'
                : 'Subscribed! Thank you for joining the NIDAN family.',
        ]);
    }
}
