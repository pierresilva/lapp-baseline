<?php

namespace App\Models;

use pierresilva\CouchDB\Eloquent\Model as Eloquent;
use pierresilva\CouchDB\Eloquent\SoftDeletes;

class Publisher extends Eloquent
{
    //
    use SoftDeletes;

    protected $connection = 'couchdb';
    protected $collection = 'publishers_collection';
    protected $fillable = [
        'name',
        'country',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Books relationship
     *
     * @return HasMany
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, null, 'publisher_ids', 'book_ids');
    }
}
