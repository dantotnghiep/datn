<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'password',
        'is_active',
        'email_verified_at',

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
        'deleted_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get fields configuration for admin panel
     *
     * @return array
     */
    public static function getFields()
    {
        return [
            'name' => [
                'label' => 'Tên',
                'sortable' => true,
            ],
            'email' => [
                'label' => 'Email',
                'sortable' => true,
            ],
            'role_id' => [
                'label' => 'Vai trò',
                'sortable' => true,
                'filterable' => true,
                'filter_options' => self::getRoleOptions(),
                'formatter' => function($value, $user) {
                    return $user->role ? $user->role->name : '-';
                }
            ],
            'is_active' => [
                'label' => 'Trạng thái',
                'sortable' => true,
                'filterable' => true,
                'filter_options' => [
                    '1' => 'Active',
                    '0' => 'Inactive'
                ],
                'formatter' => function($value) {
                    return $value ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                }
            ],
            'created_at' => [
                'label' => 'Ngày tạo',
                'sortable' => true,
                'formatter' => function($value) {
                    return $value ? $value->format('Y-m-d') : '-';
                }
            ],
        ];
    }

    /**
     * Get searchable fields
     *
     * @return array
     */
    public static function getSearchableFields()
    {
        return ['name', 'email', 'phone'];
    }

    /**
     * Get validation rules
     *
     * @param int|null $id
     * @return array
     */
    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => $id ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:user_roles,id',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get role options for filter
     *
     * @return array
     */
    private static function getRoleOptions()
    {
        $roles = UserRole::pluck('name', 'id')->toArray();
        return $roles;
    }

    public function role()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function usedpromotion(){
        return $this->hasMany(Promotion::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
