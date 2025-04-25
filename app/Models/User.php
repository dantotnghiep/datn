<?php

namespace App\Models;

use App\Mail\CustomVerifyEmail;
use App\Mail\ThemeMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'gender',
        'birthday',
        'avatar',
        'locked_at',
        'email_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }



    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isStaff()
    {
        return $this->role === 'staff';
    }
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }
    public function sendEmailVerificationNotificationCustom()
    {
        $verificationUrl = $this->createCustomVerificationUrl();
        $dataMail = [
            'name' => $this->name ?? NULL,
            'veirfyLink' => $verificationUrl ?? NULL,
            'email' => $this->email ?? NULL,
            'phone' => $this->phone ?? NULL,
        ];

        Mail::to($this->email)
            ->send((new ThemeMail($dataMail, 'verify'))->subject('Xác minh tài khoản'));
    }
    protected function createCustomVerificationUrl()
    {
        $hashedId = Crypt::encryptString($this->getKey());
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $hashedId, 'hash' => sha1($this->getEmailForVerification())]
        );
    }

    public function markEmailAsVerifiedCustom()
    {
        return $this->update([
            'email_verified' => 1
        ]);
    }
}
