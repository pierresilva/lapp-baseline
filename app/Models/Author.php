<?php

namespace App\Models;

use pierresilva\CouchDB\Eloquent\Model as Eloquent;
use pierresilva\CouchDB\Eloquent\SoftDeletes;

class Author extends Eloquent
{
    //
    use SoftDeletes;

    protected $connection = 'couchdb';
    protected $collection = 'authors_collection';
    protected $fillable = [
        'first_name',
        'last_name',
        'bio',
        'photo',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Books relationship
     *
     * @return BelongsToMany
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'author_id', '_id');
    }
}
