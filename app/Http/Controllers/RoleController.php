<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\Role\RoleService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RoleController extends Controller
{
    public function __construct(
        private RoleService $roleService
    ) {
        //
    }

    /**
     * Display a listing of the roles.
     */
    public function index(): AnonymousResourceCollection
    {
        $data = $this->roleService->list();

        return RoleResource::collection($data);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(CreateRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->create($request->toDTO());

        return response()->json([
            'message' => 'Função criada com sucesso',
            'role'    => new RoleResource($role),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified role.
     */
    public function show(string $id): RoleResource
    {
        $role = $this->roleService->show($id);

        return new RoleResource($role);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(UpdateRoleRequest $request, string $id): JsonResponse
    {
        $role = $this->roleService->update($request->toDTO(), $id);

        return response()->json([
            'message' => 'Função atualizada com sucesso',
            'role'    => new RoleResource($role),
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->roleService->delete($id);

        return response()->json([
            'message' => 'Função excluída com sucesso',
        ], Response::HTTP_OK);
    }
}
