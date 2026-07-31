<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSessionController extends Controller
{
    public function index(Request $request)
    {
        $currentId = $request->session()->get('device_session_id');

        $sessions = UserSession::active()
            ->where('user_id', Auth::id())
            ->orderByDesc('last_seen_at')
            ->get()
            ->map(function ($session) use ($currentId) {
                return [
                    'id' => $session->id,
                    'deviceName' => $session->device_name,
                    'ipAddress' => $session->ip_address,
                    'userAgent' => $session->user_agent,
                    'lastSeenAt' => $session->last_seen_at,
                    'createdAt' => $session->created_at,
                    'isCurrent' => $currentId !== null && (int) $currentId === (int) $session->id,
                ];
            });

        return response()->json([
            'sessions' => $sessions,
            'limit' => Auth::user()->device_limit,
        ]);
    }

    public function revoke(Request $request, $id)
    {
        $session = UserSession::where('user_id', Auth::id())->findOrFail($id);
        $session->update(['revoked_at' => now()]);

        $isCurrent = (int) $request->session()->get('device_session_id') === (int) $session->id;

        if ($isCurrent) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['loggedOut' => $isCurrent]);
    }
}
