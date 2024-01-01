<?php

/**
 * UserController class handles user-related operations.
 */
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole("admin")) {
            return redirect()
                ->route("home")
                ->with("error", "Unauthorized access: Admin role required.");
        }
    }

    /**
     * Display a listing of users based on filters.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $status = $request->input("status", "all");
        $role = $request->input("role", "all");
        $search = $request->input("search");

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = User::query();

        $query
            ->when($status !== "all", function ($query) use ($status) {
                return $query->where("status", $status);
            })
            ->when($role !== "all", function ($query) use ($role) {
                // Assuming you are using Spatie roles
                $roleModel = Role::where("name", $role)->first();
                if ($roleModel) {
                    return $query->whereHas("roles", function ($query) use (
                        $roleModel
                    ) {
                        $query->where("role_id", $roleModel->id);
                    });
                } else {
                    // Handle case where the specified role doesn't exist
                    // You might want to add some error handling or fallback logic here
                    return $query;
                }
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where("name", "like", "%" . $search . "%")
                        ->orWhere("email", "like", "%" . $search . "%")
                        ->orWhere("phone", "like", "%" . $search . "%");
                });
            });

        
        $roles = Role::all();
        $backendRoles = Role::where('name', '!=', 'customer')->get();

        $users = $query->paginate(100);

        return view("users.index", compact("users", "roles", "backendRoles"));
    }

    /**
     * Store a newly created user in the storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:20",
            "email" => "required|email|max:50|unique:users,email",
            "phone" => "required|string|max:10|unique:users,phone",
            "password" => "required|string|max:50",
            "status" => "required|in:ACTIVE,INACTIVE",
            "roles" => [
                "required",
                "array",
                function ($attribute, $value, $fail) {
                    // Check if 'customer' role is present in the array
                    if (in_array("customer", $value)) {
                        $fail(
                            "The $attribute field cannot include the 'customer' role."
                        );
                    }
                },
            ], // 'roles' should be an array
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route("users.index")
                ->with("failure", $validator->errors());
        }

        $hashedPassword = Hash::make($request->input("password"));
        $request->merge(["password" => $hashedPassword]);

        /** @var \App\Models\User $user */
        $user = User::create($request->all());

        // Attach roles to the user
        $user->syncRoles($request->input("roles"));

        return redirect()
            ->route("users.index")
            ->with("success", "User created successfully!");
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        
        $user = User::findOrFail($id);
        $roles = Role::all(); // Assuming you want to retrieve all roles for the edit form

        return view("users.edit", compact("user", "roles"));
    }

    /**
     * Update the specified user in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:20",
            "email" => "required|email|max:50|unique:users,email," . $id,
            "phone" => "required|string|max:10|unique:users,phone," . $id,
            "password" => "required|string|max:50",
            "status" => "required|in:ACTIVE,INACTIVE",
            "roles" => [
                "required",
                "array",
                function ($attribute, $value, $fail) {
                    // Check if 'customer' role is present in the array
                    if (in_array("customer", $value)) {
                        $fail(
                            "The $attribute field cannot include the 'customer' role."
                        );
                    }
                },
            ], // 'roles' should be an array
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route("users.index")
                ->with("failure", $validator->errors());
        }

        $hashedPassword = Hash::make($request->input("password"));
        $request->merge(["password" => $hashedPassword]);

        $user->update($request->all());

        $user->update($request->except("roles"));

        // Update user roles
        $user->roles()->sync($request->input("roles"));

        

        return redirect()
            ->route("users.index")
            ->with("success", "User updated successfully!");
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route("users.index")
            ->with("success", "User deleted successfully!");
    }
}

