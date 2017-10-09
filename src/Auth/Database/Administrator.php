<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\AdminBuilder;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Administrator extends Model implements AuthenticatableContract
{
    use Authenticatable, AdminBuilder, AdminPermission;

    protected $fillable = ['username', 'password', 'name', 'avatar'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.users_table'));

        parent::__construct($attributes);
    }

    /**
     * xxl get the name.
     *
     * @param $permission
     *
     * @return bool
     */
    public static function hasName($name)
    {
        $count = (new static)->newQuery()->where("name","=",$name)->count();
        return $count;
    }

    /**
     * xxl get the name.
     *
     * @param $permission
     *
     * @return bool
     */
    public static function getTypeFromName($name)
    {
        $obj = (new static)->newQuery()->get(['*'])->where("name","=",$name)->first();
        return $obj->type;
    }

    /**
     * xxl get the name.
     *
     * @param $permission
     *
     * @return bool
     */
    public static function getUsersFromType($type)
    {
        $ids = (new static)->newQuery()->get(['*'])->where("type","=",$type)->pluck('id');
        return $ids;
    }
}
