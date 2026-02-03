<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Admin can do everything
     */
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
    * Vendor can view only their products
    */
    public function view(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    /**
    * Vendor can create products
    */
    public function create(User $user): bool
    {
        return $user->isVendor();
    }

    /**
     * Vendor can update only their products
     */

    public function update(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    /**
     * Vendor can delete only their products
     */
    public function delete(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return false;
    }

    private function ownsProduct(User $user, Product $product)
    {
        return $user->isVendor() && $user->vendor && $product->vendor_id === $user->vendor->id;
    }
}
