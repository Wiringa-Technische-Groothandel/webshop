<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class User
 * @package App
 */
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    /**
     * Non mass-assignable fields
     *
     * @var array
     */
    protected $guarded = ['id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    /**
     * Get the list of addresses that belong to the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'User_id', 'company');
    }

    /**
     * Get the user's discounts
     *
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discounts($type = null)
    {
        $query = $this->hasMany(Discount::class, 'User_id', 'company');
        $query->where('group_desc', '!=', 'Vervallen');

        switch ($type) {
            case 'group':
                $query->where('table', 'VA-220');
                break;

            case 'product':
                $query->where('table', 'VA-260');
                break;

            case 'product-default':
                $query->where('table', 'VA-261');
                break;

            case 'group-default':
                $query->where('table', 'VA-221');
                break;
        }

        return $query;
    }

    /**
     * Get the users orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'User_id', 'login');
    }

    /**
     * The company the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'login', 'company');
    }

}
