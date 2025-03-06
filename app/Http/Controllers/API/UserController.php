<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

class UserController extends Controller
{
    public function create(MakeUserRequest $request)
    {
        // try {
        $user_id = User::makeUser($request->validated());
        return Response::json(['User created successfully with id: ' . $user_id]);
        // } catch (Throwable $e) {
        //     dd($e->getCode());
        //     return Response::json([$e->getMessage()], $e->getCode());
        // }
    }
    public function show($id)
    {
        return new UserResource(User::with('wallets')->findOrFail($id));
    }
}
