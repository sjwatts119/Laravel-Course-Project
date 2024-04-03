<?php

use App\Models\Listing;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

// All listings
Route::get('/', [ListingController::class, 'index'])->name('home');

// Show create form for a new listing
// We are using the middleware('auth') to ensure that only authenticated users can access the create form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

// Store Listing Data
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// Show User's Listings for Management
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// Show Edit Form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// Update Listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// Delete Listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

// Single listing
Route::get('/listings/{listing}', [ListingController::class, 'show']);


//show register form
//usage of middleware('guest') to prevent logged in users from accessing the registration form
//it will redirect them to the page name home
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

//Create new user
Route::post('/users', [UserController::class, 'store']);

// Log out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// Show Login Form. We name this route login because we are using the Laravel auth package which expects the login route to be named login.
// if you want to use a different name, you will have to update the auth.php config file
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Log in User
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

// Common Resource Routes:
// index - show all records
// show - show a single record
// create - show a form to create a new record
// store - save the new record
// edit - show a form to edit a record
// update - save the edited record
// destroy - delete a record

