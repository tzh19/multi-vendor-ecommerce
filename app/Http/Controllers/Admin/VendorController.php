<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Inertia\Inertia;
use App\Models\User;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::latest()->paginate(10)->through(fn ($vendor) => [
                    'id' => $vendor->id,
                    'store_name' => $vendor->store_name,
                    'store_description' => $vendor->store_description,
                    'active' => $vendor->active,
                    'created_at' => $vendor->created_at->format('d M, Y'),
                ]);
        $user = new User();
        $vendorUsers = $user->getUserByRole('vendor');

        return Inertia::render('Admin/Vendors/Index', [
            'vendors' => $vendors,
            'vendorUsers' => $vendorUsers
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User();
        $vendorUsers = $user->getUserByRole('vendor');
        return Inertia::render('Admin/Vendors/Create', [
            'vendorUsers' => $vendorUsers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'store_name' => 'required|string|max:255|unique:vendors,store_name',
                'store_description' => 'nullable|string',
                'active' => 'boolean',
                'user_id' => 'required|exists:users,id',
            ],
            [
                'store_name.required' => 'Vendor name is required.',
                'store_name.unique' => 'Vendor name has already been taken.',
            ]
        );

        $vendor = new Vendor();
        $vendor->store_name = $request->store_name;
        $vendor->store_description = $request->store_description;
        $vendor->user_id = $request->user_id;
        $vendor->active = $request->active;

        $vendor->save();

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vendor = Vendor::findOrFail($id);

        $user = new User();
        $vendorUsers = $user->getUserByRole('vendor');

        return Inertia::render('Admin/Vendors/Edit', [
            'vendor' => $vendor,
            'vendorUsers' => $vendorUsers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate(
            [
                'store_name' => 'required|string|max:255|unique:vendors,store_name,' . $id,
                'store_description' => 'nullable|string',
                'active' => 'boolean',
                'user_id' => 'required|exists:users,id',
            ],
            [
                'store_name.required' => 'Vendor name is required.',
                'store_name.unique' => 'Vendor name has already been taken.',
            ]
        );

        $vendor->update($validated);

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully');

    }
}
