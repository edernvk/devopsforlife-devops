<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cpf',
        'email',
        'password',
        'registration',
        'mobile',
        'birth_date',
        'received_notification_birthday',
        'city_id',
        'team_id',
        'avatar', // nullable
        'approved',
        'first_time',
        'office',
        'allow_terms',
        'last_login_at',
        'last_login_ip',
        'vcard_enable'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'received_notification_birthday' => 'boolean',
        'vcard_enable' => 'boolean',
    ];


    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved');
    }

    public function scopeWithoutGhosts($query)
    {
        return $query->whereNotIn('cpf', [
            99999999990,
            99999999991,
            99999999992,
            99999999993,
            99999999994,
            99999999995,
            99999999996,
            99999999997,
            99999999998,
            99999999999,
            12312312312,
            12345678910
        ]);
    }

    /**
     * Scope a query to only include users which accessed the platform at least once.
     *  Includes disapproved users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLoggedOnce($query)
    {
        return $query->whereNotNull('first_time')->orWhereNotNull('last_login_at');
    }


    /**
     * Find the user instance for the given registration number.
     * @param  string  $registration
     * @return \App\User
     */
    public function findForPassport($cpf)
    {
        return $this->where('cpf', $cpf)->first();
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles) {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) || abort(403, 'This action is forbidden.');
        }

        return $this->hasRole($roles) || abort(403, 'This action is forbidden.');
    }

    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles) {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role) {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function paycheck_access() {
        return $this->hasOne(PaycheckAccess::class);
    }

    public function christmas_token() {
        return $this->hasOne(ChristmasToken::class);
    }

    public function burguesa_jacket() {
        return $this->hasOne(BurguesaJacketCampaign::class);
    }

    public function vaccine_survey() {
        return $this->hasOne(VaccineSurveyCampaign::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function messages() {
        return $this->belongsToMany(Message::class, 'messages_users', 'user_id', 'message_id')->withPivot('read');
    }

    public function sentMessages() {
        return $this->hasMany(Message::class);
    }

    public function readMessages() {
        return $this->messages()->wherePivot('read', '!=', null);
    }

    public function unreadMessages() {
        return $this->messages()->wherePivot('read', null);
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function OauthAccessToken() {
        return $this->hasMany(OauthAccessToken::class);
    }

    public function messageOut() {
        return $this->hasMany(Message::class);
    }

    public function activeTicket() {
        return $this->hasOne(Ticket::class);
    }

    public function videocastsTrackeds() {
        return $this->hasMany(UserVideocastTracked::class);
    }

    public function drawingContestVotes() {
        return $this->hasMany(DrawingContestVote::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class);
    }

    public function foldersUsers()
    {
        return $this->belongsToMany(Folder::class, 'users_in_folders');
    }

    public function groupUser() {
        return $this->belongsToMany(Group::class, 'user_group', 'user_id', 'group_id');
    }

    public function userComment()
    {
      return $this->hasMany(Comment::class);
    }

//    public function tickets() {
//        return $this->hasMany(Ticket::class);
//    }

        /**
     * The user has been authenticated
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     */
    public function asAuthenticated(Request $request)
    {
        $this->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);
    }
}
