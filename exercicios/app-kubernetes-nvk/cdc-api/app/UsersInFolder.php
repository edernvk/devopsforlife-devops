<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UsersInFolder extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'folder_id',
        'created_at',
        'updated_at'
    ];

    public function userInFolders(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function foldersWithUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            Folder::class,
            'id',
            'name',
        )->withPivot(['amount', 'size']);
    }
}
