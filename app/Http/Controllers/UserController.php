<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //Show register/create form
    public function create()
    {
        return view('users.register');
    }

    //Create new user
    public function store(){
        $formFields = request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            // using the confirmed rule to ensure that the password and password_confirmation fields match. 
            // this will automatically look for a field named password_confirmation and compare it to the password field
            'password' => 'required|confirmed|min:6'
        ]);

        //hash the password before storing it in the database
        $formFields['password'] = bcrypt($formFields['password']);

        //create the user 
        $user = User::create($formFields);

        //log the user in
        auth()->login($user);

        //redirect the user to the home page with a success message
        return redirect('/')->with('message', 'User created and logged in successfully!');
    }

    //Log out
    public function logout(){
        //destroy the session
        auth()->logout();

        //invalidate the session and regenerate the session ID. this is a security measure to prevent session fixation attacks
        request()->session()->invalidate();

        //regenerate the session ID
        return redirect('/')->with('message', 'User logged out successfully!');
    }

    //Show login form
    public function login(){
        return view('users.login');
    }

    //Log in user
    public function authenticate(){
        $formFields = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)){
            //if the user is successfully authenticated, regenerate the session ID.
            //we do this because the user is now logged in and we want to prevent session fixation attacks
            request()->session()->regenerate();

            return redirect('/')->with('message', 'User logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput();
    }


}
