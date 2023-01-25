<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    public function to() {
        return $this->belongsToMany(User::class, 'messages_users', 'message_id', 'user_id')->withPivot('read');
    }

    public function usersRead() {
        return $this->to()->wherePivot('read', '!=', null);
    }

    public function usersNotRead() {
        return $this->to()->wherePivot('read', null);
    }

    public function fromUser() {
        return $this->belongsTo(User::class, 'from');
    }

    public function status() {
        return $this->belongsTo(StatusMessage::class, 'status');
    }

}
