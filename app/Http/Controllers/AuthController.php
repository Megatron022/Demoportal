<?php

/**
 * AuthController class handles authentication-related operations.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Display the login form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("auth.login");
    }

    protected $redirectTo = '/home';

    /**
     * Handle user login.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the user's input
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        // Attempt to log in the user
        $credentials = $request->only("email", "password");
        if (Auth::attempt($credentials)) {
            // Authentication passed
            return redirect()->intended($this->redirectTo); // Change '/dashboard' to the desired redirect path after successful login
        }

        // Authentication failed
        return redirect()
            ->route("login")
            ->with("error", "Invalid credentials");
    }

    /**
     * Display the home page after successful login.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        return view("home");
    }

    /**
     * Log out the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        \Session::flush();
        \Auth::logout();
        return redirect("login");
    }
}
