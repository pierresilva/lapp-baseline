<?php

namespace App\Models;

use pierresilva\CouchDB\Eloquent\Model as Eloquent;
use pierresilva\CouchDB\Eloquent\SoftDeletes;

class Book extends Eloquent
{
    //
    use SoftDeletes;

    protected $connection = 'couchdb';
    protected $collection = 'books_collection';
    protected $fillable = [
        'title',
        'code',
        'synopsis',
        'cover',
        'author_id',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Authors relationship
     *
     * @return BelongsToMany
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Publisher relationship
     *
     * @return BelongsTo
     */
    public function publisher()
    {
        return $this->belongsToMany(Publisher::class);
    }
}
