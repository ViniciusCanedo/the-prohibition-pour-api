<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
        //
    }

    /**
     * Display a listing of the users.
     */
    public function index(): AnonymousResourceCollection
    {
        $data = $this->userService->list();

        return UserResource::collection($data);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->toDTO());

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user'    => new UserResource($user),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified user.
     */
    public function show(string $id): UserResource
    {
        $user = $this->userService->show($id);

        return new UserResource($user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = $this->userService->update($request->toDTO(), $id);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'user'    => new UserResource($user),
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->userService->delete($id);

        return response()->json([
            'message' => 'Usuário excluído com sucesso',
        ], Response::HTTP_OK);
    }
}
