<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index(){
        return view('listings.index', [
            'listings' => Listing::latest()->filter
            (request(['tag', 'search']))->paginate(4)
        ]);
    }

    public function show(Listing $listing){
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    public function create(){
        return view('listings.create');
    }

    public function store(){
        $formFields = request()->validate([
            'title' => 'required',
            'tags' => 'required',
            //we are using the unique rule to ensure that the company name is unique in the database, checking against the table listings and the column company
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => ['required'],
            'email' => ['required', 'email'],
            'description' => 'required'
        ]);

        if(request()->hasFile('logo')){
            //store the file in the public disk under the logos directory
            $formFields['logo'] = request()->file('logo')->store('logos', 'public');
        }

        //add the user_id to the form fields array so we can associate the listing with the user that created it
        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfully!');
    }

    public function edit(Listing $listing){
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    public function update(Listing $listing){
        // Make sure logged in user is the owner of the listing
        if(auth()->id() !== $listing->user_id){
            abort(403);
        }

        $formFields = request()->validate([
            'title' => 'required',
            'tags' => 'required',
            //we no longer have to check for uniqueness since we are updating an existing record, it will never be unique
            'company' => 'required',
            'location' => 'required',
            'website' => ['required'],
            'email' => ['required', 'email'],
            'description' => 'required'
        ]);

        if(request()->hasFile('logo')){
            //store the file in the public disk under the logos directory
            $formFields['logo'] = request()->file('logo')->store('logos', 'public');
        }   

        $listing->update($formFields);

        return back()->with('message', 'Listing updated successfully!');
    }

    public function destroy(Listing $listing){
        // Make sure logged in user is the owner of the listing
        if(auth()->id() !== $listing->user_id){
            abort(403);
        }

        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfully!');
    }

    public function manage(){
        return view('listings.manage', [
            //intelliphense is not able to detect the relationship between the user and the listings, so it throws an error even though the code is correct
            'listings' => auth()->user()->listings()->get()
        ]);
    }
}

// Common Resource Routes:
// index - show all records
// show - show a single record
// create - show a form to create a new record
// store - save the new record
// edit - show a form to edit a record
// update - save the edited record
// destroy - delete a record
