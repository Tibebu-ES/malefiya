<?php

namespace App\Models;

use App\Entities\SubCalendarPermission;
use CodeIgniter\Model;
use Faker\Generator;

class SubCalendarPermissionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_sub_calendar_permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = SubCalendarPermission::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['sub_calendar_id','access_key_id','access_type'];

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
     * @return SubCalendarPermission
     */
    public function fake (Generator &$faker)
    {
        return new SubCalendarPermission([
            'access_type' => $faker->randomElement([SubCalendarPermission::ACCESS_TYPE_READ_ONLY,SubCalendarPermission::ACCESS_TYPE_MODIFY])
        ]);
    }

    /**
     * @param int $id
     * @return SubCalendarPermission|null
     */
    public function getSubCalendarPermission (int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $accessKeyId
     * @return SubCalendarPermission []
     */
    public function getSubCalendarPermissions (int $accessKeyId)
    {
        return $this->where('access_key_id',$accessKeyId)->findAll();
    }

    /**
     * @param SubCalendarPermission $subCalendarPermission
     * @return SubCalendarPermission
     * @throws \ReflectionException
     */
    public function addSubCalendarPermission (SubCalendarPermission $subCalendarPermission)
    {
        $insertId = $this->insert($subCalendarPermission);
        if($insertId){
            $subCalendarPermission->setId($insertId);
        }else{
            throw new \Exception("Error on adding SubCalendarPermission");
        }

        return $subCalendarPermission;
    }

    /**
     * @param int $subCalendarPermissionId
     * @param SubCalendarPermission $subCalendarPermission
     * @return bool
     * @throws \ReflectionException
     */
    public function updateSubCalendarPermission (int $subCalendarPermissionId, SubCalendarPermission $subCalendarPermission)
    {
        if($this->update($subCalendarPermissionId,$subCalendarPermission)){
            return true;
        }else{
            throw new \Exception("Error on updating SubCalendarPermission");
        }
    }

    /**
     * @param int $subCalendarPermissionId
     * @return bool
     * @throws \Exception
     */
    public function deleteSubCalendarPermission (int $subCalendarPermissionId)
    {
        if($this->delete($subCalendarPermissionId)){
            return true;
        }else{
            throw new \Exception("Error on deleting SubCalendarPermission");
        }
    }


}
