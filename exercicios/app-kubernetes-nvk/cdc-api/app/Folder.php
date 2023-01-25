<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function usersInFolders()
    {
        return $this->belongsToMany(User::class, 'users_in_folders');
    }

    public function files()
    {
        return $this->belongsToMany(
            File::class,
            'folder_file'
        );
    }
}
