<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    //we could use model::unguard() to allow mass assignment, but it is not recommended as it can be a security risk. this is why we use the fillable property to specify which fields are allowed to be mass assigned
    protected $fillable = [
        'title',
        'tags',
        'company',
        'location',
        'email',
        'website',
        'description',
        'logo',
        'user_id'
    ];

    public function scopeFilter($query, array $filters) {
        if($filters['tag'] ?? false){
            $query->where('tags', 'like', '%' . request('tag') . '%');
        }

        if($filters['search'] ?? false){
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%');
        }

    }

    //Relationship to user
    public function user(){
        //this will return the user that created the listing
        return $this->belongsTo(User::class, 'user_id');
    }
}
