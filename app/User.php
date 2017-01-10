<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class User.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Address[] $addresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Discount[] $discounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @property-read \App\Company $company
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $username
 * @property string $company_id
 * @property string $email
 * @property bool $active
 * @property bool $isAdmin
 * @property bool $manager
 * @property string $password
 * @property string $favorites
 * @property string $cart
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereManager($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFavorites($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
class User extends Model implements
AuthenticatableContract,
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
     * Non mass-assignable fields.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'isAdmin', 'manager', 'cart', 'favorites'];

    /**
     * Get the list of addresses that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'User_id', 'company_id');
    }

    /**
     * Get the user's discounts.
     *
     * @param string|null $type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discounts($type = null)
    {
        $query = $this->hasMany(Discount::class, 'User_id', 'company_id');
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
     * Get the users orders.
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
        return $this->belongsTo(Company::class, 'company_id', 'login');
    }

    /**
     * Get the sub accounts that have the same parent company as
     * the current account.
     *
     * @param int $paginate
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function subAccounts($paginate = 15)
    {
        return self::whereCompanyId($this->company_id)->paginate($paginate);
    }

    /**
     * Check if this is the main account.
     *
     * @return bool
     */
    public function isMain()
    {
        return $this->company_id === $this->username;
    }
}
