<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class WebhookController extends Controller
{
    public function ssoLogout(Request $request)
    {
        $ssoId = $request->input('sso_id');

        if (!$ssoId) {
            return response()->json(['message' => 'SSO ID is required'], 400);
        }

        $user = User::where('sso_id', $ssoId)->first();

        if ($user) {
            // Delete all active sessions for this user
            DB::table('sessions')->where('user_id', $user->id)->delete();
            return response()->json(['message' => 'Sessions destroyed']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }
}
