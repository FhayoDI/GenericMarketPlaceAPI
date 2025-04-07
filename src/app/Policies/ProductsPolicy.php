<?php

namespace App\Policies;

use App\Models\Products;
use App\Models\User;

class ProductsPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Products $products): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Products $products): bool
    {
        // Exemplo de regra real:
        return $products->user_id === $user->id;
    }

    public function delete(User $user, Products $products): bool
    {
        return $products->user_id === $user->id;
    }

    public function restore(User $user, Products $products): bool
    {
        return false;
    }

    public function forceDelete(User $user, Products $products): bool
    {
        return false;
    }
}
