<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function whereIn(): JsonResponse
    {
        $roles = ['admin', 'super_admin'];

        $users = User::query()
            ->whereIn('role', $roles)
            ->get();

        return response()->json([$users]);
    }

    public function whereNotIn(): JsonResponse
    {
        $roles = ['admin', 'super_admin'];

        $users = User::query()
            ->whereIn('role', $roles)
            ->get();

        return response()->json([$users]);
    }
}
