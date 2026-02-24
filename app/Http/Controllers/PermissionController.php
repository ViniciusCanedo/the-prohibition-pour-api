<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Services\Permission\PermissionService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class PermissionController extends Controller
{
    public function __construct(
        private PermissionService $permissionService
    ) {
        //
    }

    /**
     * Display a listing of the permissions.
     */
    public function index(): AnonymousResourceCollection
    {
        $permissions = $this->permissionService->list();

        return PermissionResource::collection($permissions);
    }
}
