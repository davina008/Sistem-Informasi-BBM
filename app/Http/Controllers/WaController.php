<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'target' => 'required',
            'message' => 'required',
        ]);

        $token = config('services.fonnte.token');

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target'  => $request->target,
            'message' => $request->message,
        ]);

        if ($response->successful()) {
            return back()->with('success', '✅ Pesan WhatsApp berhasil dikirim!');
        } else {
            return back()->with('error', '❌ Gagal mengirim pesan WhatsApp!');
        }
    }
}
