<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSessionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($this->buildSessionsResponse(Auth::user(), $request));
    }

    public function revoke(Request $request, $id)
    {
        return $this->revokeSession($request, Auth::id(), $id);
    }

    public function indexForUser(Request $request, $userId)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($userId);

        return response()->json($this->buildSessionsResponse($user, $request));
    }

    public function revokeForUser(Request $request, $userId, $sessionId)
    {
        $this->authorizeAdmin();

        return $this->revokeSession($request, $userId, $sessionId);
    }

    private function authorizeAdmin()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }
    }

    private function buildSessionsResponse(User $user, Request $request)
    {
        $currentId = $request->session()->get('device_session_id');

        $sessions = UserSession::active()
            ->where('user_id', $user->id)
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

        return [
            'sessions' => $sessions,
            'limit' => $user->hasRole('admin') ? null : $user->device_limit,
            'userName' => $user->name,
        ];
    }

    private function revokeSession(Request $request, $userId, $sessionId)
    {
        $session = UserSession::where('user_id', $userId)->findOrFail($sessionId);
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
