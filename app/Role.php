<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Role extends Model
{
    protected $table = 'tbl_roles';
    // protected $primaryKey = 'role_id';
    protected $primaryKey = 'roleId';

    public $timestamps = false;

    protected $guarded = ['id'];
    private $rules = array(
        'name' => 'required|unique:tbl_roles',
    );
    private $errors;

    public function validate($data)
    {
        $validator = Validator::make($data, $this->rules);
        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get Role Id By Providing Role Alias
     */
    public static function getNameById($id)
    {
        $response = "";
        if (isset($id) && $id) {
            $result = self::where('roleId', $id)->pluck('role')->first();
            if ($result) {
                $response = $result;
            }
        }
        return $response;
    }

    /**
     * Get Role Id By Providing Role Alias
     */
    public static function getIdFromAlias($alias)
    {
        $response = "";
        if (isset($alias) && $alias) {
            $result = self::where('alias', $alias)->pluck('roleId');
            if (count($result) > 0) {
                $response = $result[0];
            }

        }

        return $response;
    }

    /**
     * Get Role Alias By Providing Role Id
     */
    public static function getAliasFromId($id)
    {
        $response = "";
        if (isset($id) && $id) {
            $result = self::where('roleId', $id)->pluck('alias');
            if (count($result) > 0) {
                $response = $result[0];
            }

        }

        return $response;
    }
}
