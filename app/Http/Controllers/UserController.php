<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'user'    => new UserResource($user),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified user.
     */
    public function show(string $id): UserResource
    {
        return new UserResource(User::with('role')->findOrFail($id));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'user'    => new UserResource($user),
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], Response::HTTP_OK);
    }
}
