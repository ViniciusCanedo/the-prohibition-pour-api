<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final class UserService
{
    /**
     * Summary of list
     *
     * @return Collection<int, User>
     */
    public function list(): Collection
    {
        return User::all();
    }

    public function show(string $id): User
    {
        return User::with('role')->findOrFail($id);
    }

    public function create(CreateUserDTO $createUserDTO): User
    {
        $user = new User();
        $user->fill(get_object_vars($createUserDTO));
        $user->save();

        return $user;
    }

    public function update(UpdateUserDTO $updateUserDTO, string $id): User
    {
        $user = User::findOrFail($id);
        $user->fill(get_object_vars($updateUserDTO));
        $user->save();

        return $user;
    }

    public function delete(string $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
