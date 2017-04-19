<?php

namespace App;

use App\Scopes\StatusScope;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait, HasApiTokens;

    const STATUS_NORMAL = 1;

    /**
     * 需要被转换成日期的属性(软删除)
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nickname', 'avatar',
        'mobile', 'status', 'description', 'email_notify_enabled',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pivot'
    ];

    /**
     * 额外添加属性 role_names为用户角色显示名称
     * @var array
     */
    // protected $appends = ['roles'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
        static::creating(function ($user){
          $user->activation_token = str_random(30);
          $identicon = new \Identicon\Identicon();
          $user->avatar = $identicon->getImageDataUri($user->name,80);
        });
    }

    public function menus()
    {
        $menus = [];
        //获取当前用户的角色组
        $roles = $this->roles()->get();
        foreach($roles as $role) {
            //获取每个角色对应的可视菜单组
            $menus[] = $role->menus;
        }
        //将多个数组合并成为一个数组并清除重复的键值对
        $menus = array_unique(array_collapse($menus));
        return $menus;
    }

    public function permissions()
    {
        $permissions = [];
        //获取当前用户的角色组
        $roles = $this->roles()->get();
        foreach($roles as $role) {
            //获取每个角色对应的可视菜单组
            $permissions[] = $role->permissions;
        }
        //将多个数组合并成为一个数组并清除重复的键值对
        $permissions = array_unique(array_collapse($permissions));
        return $permissions;
    }

    public function roles()
    {
        return $this->belongsToMany(
          config('entrust.role'),
           config('entrust.role_user_table'),
            config('entrust.user_foreign_key'),
             config('entrust.role_foreign_key')
              )->withTimestamps();
    }

    /**
     * Get the avatar and return the default avatar if the avatar is null.
     *
     * @param string $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        return isset($value) ? $value : config('lnc.default_avatar');
    }

    /**
     * 用于加密密码
     * @param [type] $password [description]
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function findForPassport($username)
    {
        return $this->where('email',$username)
                      // ->orWhere('name',$username)
                        ->first();
    }

    public function scopeNormal($query)
    {
        return $query->where('status',self::STATUS_NORMAL);
    }
}
