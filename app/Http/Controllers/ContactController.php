<?php

/**
 * ContactController class handles contact-related operations.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Constructor to ensure only admin or staff users have access.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __construct()
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole("admin") || !$user->hasRole("staff")) {
            return redirect()
                ->route("home")
                ->with(
                    "error",
                    "Unauthorized access: Admin or Staff role required."
                );
        }
    }

    /**
     * Display a listing of contacts based on filters.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $status = $request->input("status", "all");
        $search = $request->input("search");

        $query = Contact::query();

        $query
            ->when($status !== "all", function ($query) use ($status) {
                return $query->where("status", $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where("name", "like", "%" . $search . "%")
                        ->orWhere("email", "like", "%" . $search . "%")
                        ->orWhere("phone", "like", "%" . $search . "%");
                });
            });

        $contacts = $query->paginate(100);

        return view("contacts.index", compact("contacts"));
    }

    /**
     * Show the form for creating a new contact.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("contact.create");
    }

    /**
     * Store a newly created contact in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "phone" => "nullable|string|max:10",
            "email" => "nullable|email|max:255",
            "website" => "nullable|url|max:255",
            "status" => "nullable|in:ACTIVE,INACTIVE",
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route("contacts.index")
                ->with("failure", $validator->errors());
        }

        Contact::create($request->all());

        return redirect()
            ->route("contacts.index")
            ->with("success", "Contact added successfully!");
    }

    /**
     * Display the specified contact.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Contact $contact)
    {
        return view("contacts.show", compact("contact"));
    }

    /**
     * Show the form for editing the specified contact.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Contact $contact)
    {
        return view("contact.edit", compact("contact"));
    }

    /**
     * Update the specified contact in the database.
     *
     * @param Request $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "phone" => "nullable|string|max:10",
            "email" => "nullable|email|max:255",
            "website" => "nullable|url|max:255",
            "status" => "nullable|in:ACTIVE,INACTIVE",
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route("contacts.index")
                ->with("failure", $validator->errors());
        }

        $contact->update($request->all());

        return redirect()
            ->route("contacts.index")
            ->with("success", "Contact updated successfully!");
    }

    /**
     * Remove the specified contact from the database.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route("contacts.index")
            ->with("success", "Contact deleted successfully!");
    }
}
