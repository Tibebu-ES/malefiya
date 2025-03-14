<?php

namespace App\Models;

use App\Entities\User;
use App\Entities\UserInfo;
use CodeIgniter\Model;
use Faker\Generator;

class UserInfoModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_user_infos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = UserInfo::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id','first_name','last_name','timezone','locale'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    //get userinfo

    /**
     * @param $userId
     * @return UserInfo|null
     */
    public function getUserInfo($userId) : ?UserInfo
    {
       return $this->where('user_id',$userId)->first();
    }

    /**
     * save user info - add/update user info
     * @param $userId
     * @param UserInfo $userInfo
     * @return bool
     * @throws \ReflectionException
     */
    public function saveUserInfo ($userId, UserInfo $userInfo)
    {
        $oldUserInfo = $this->getUserInfo($userId);
        if(!empty($oldUserInfo)){
            //update
            $userInfo->id = $oldUserInfo->id;
        }else{
            //create
            $userInfo->user_id = $userId;
        }

       return $this->save($userInfo);
    }



    /**
     * @param Generator $faker
     * @return UserInfo
     */
    public function fake(Generator &$faker)
    {
        return new UserInfo([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'timezone' => $faker->timezone,
            'locale' => $faker->locale
        ]);
    }

}
