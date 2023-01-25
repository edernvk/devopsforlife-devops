<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Videocast extends Model
{
    protected $table = 'videocasts';

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'date',
        'trackeable'
    ];

    protected $casts = [
        'trackeable' => 'boolean'
    ];

    /**
     * Set videocast's date field to datetime format
     *
     * @param  string  $value
     * @return void
     */
    public function setDateAttribute($value)
    {
        // formats to `AAAA-MM-DD hh:mm:ss`
        $this->attributes['date'] = \Carbon\Carbon::createFromFormat('d/m/Y - H:i \h\r\s', $value)->toDateTimeString();
    }

    /**
     * Get videocast's date field as user-friendly format
     *
     * @param  string  $value
     * @return string
     */
    public function getDateAttribute($value)
    {
        // formats to `DD/MM/AAAA hh:mm hrs`
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y - H:i \h\r\s');
    }

    /**
     * Get videocast's video_url field if trackeble false
     *
     * @param  string $value
     * @return string
     */
    // public function getVideoUrlAttribute($value)
    // {
    //     if ($this->attributes['trackeable'] == false) {
    //         return $value;
    //     }

    //     return null;
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'videocast_user')
        ->withPivot('read');
    }
}
