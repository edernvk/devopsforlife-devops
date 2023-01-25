<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'description',
        'path',
        'status',
        'accepted',
        'deadline',
        'hashcode'
    ];

    protected $hidden = [
        'path'
    ];

    protected $casts = [
        'accepted' => 'boolean'
    ];

    protected $appends = [
        'file_url'
    ];

    public function getFileUrlAttribute()
    {
        return Storage::url($this->attributes['path']);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }
    public function folders() {
        return $this->belongsToMany(Folder::class, 'folder_file');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function signature()
    {
        return $this->hasOne(FileSignature::class);
    }
}
