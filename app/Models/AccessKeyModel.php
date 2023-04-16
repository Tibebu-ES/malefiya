<?php

namespace App\Models;

use App\Entities\AccessKey;
use CodeIgniter\Model;
use Faker\Generator;

class AccessKeyModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_access_keys';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = AccessKey::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['calendar_id','name','key','active','has_password','password'];

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

    /**
     * @param Generator $faker
     * @return AccessKey
     */
    public function fake (Generator &$faker)
    {
        return new AccessKey([
            'name' => $faker->name(),
            'key' => $faker->unique()->bothify('?????##?????###???'),
            'active' => $faker->boolean(),
            'has_password' => $faker->boolean(),
            'password' => $faker->password(8)
        ]);
    }

    /**
     * @param int $id
     * @return AccessKey|null
     */
    public function getAccessKey (int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $calendarId
     * @return AccessKey[]
     */
    public function getAccessKeys (int $calendarId)
    {
        return $this->where('calendar_id',$calendarId)->findAll();
    }

    /**
     * @param int $calendarId
     * @param AccessKey $accessKey
     * @return AccessKey
     * @throws \ReflectionException
     */
    public function addAccessKey (int $calendarId, AccessKey $accessKey)
    {
        $accessKey->setCalendarId($calendarId);
        $insertId = $this->insert($accessKey);
        if($insertId){
            $accessKey->setId($insertId);
        }else{
            throw new \Exception("Error on adding access key");
        }
        return $accessKey;
    }

    /**
     * @param int $accessKeyId
     * @param AccessKey $accessKey
     * @return bool
     * @throws \ReflectionException
     */
    public function updateAccessKey (int $accessKeyId, AccessKey $accessKey)
    {
        if($this->update($accessKeyId,$accessKey)){
            return true;
        }else{
            throw new \Exception("Error on updating access key");
        }
    }

    /**
     * @param int $accessKeyId
     * @return bool
     * @throws \Exception
     */
    public function deleteAccessKey (int $accessKeyId)
    {
        if($this->delete($accessKeyId)){
            return true;
        }else{
            throw new \Exception("Error on deleting access key");
        }
    }

}
