<?php

namespace App\Models;

use App\Entities\CustomEventField;
use CodeIgniter\Model;
use Faker\Generator;

class CustomEventFieldModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_custom_event_fields';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = CustomEventField::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['calendar_id','name','type'];

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
     * @return CustomEventField
     */
    public function fake (Generator &$faker)
    {
        return new CustomEventField([
            'name' => $faker->name
        ]);
    }

    /**
     * @param int $id
     * @return CustomEventField|null
     */
    public function getCustomEventField (int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $calendarId
     * @return CustomEventField[]
     */
    public function getCustomEventFields (int $calendarId)
    {
        return $this->where('calendar_id',$calendarId)->findAll();
    }

    /**
     * @param int $calendarId
     * @param CustomEventField $customEventField
     * @return CustomEventField
     * @throws \ReflectionException
     */
    public function addCustomEventField (int $calendarId, CustomEventField $customEventField)
    {
        $customEventField->setCalendarId($calendarId);
        $insertId = $this->insert($customEventField);
        if($insertId){
            $customEventField->setId($insertId);
        }else{
            throw new \Exception("Error on adding custom event field");
        }
        return $customEventField;
    }

    /**
     * @param int $customEventFieldId
     * @param CustomEventField $customEventField
     * @return bool
     * @throws \ReflectionException
     */
    public function updateCustomEventField (int $customEventFieldId, CustomEventField $customEventField)
    {
        if($this->update($customEventFieldId,$customEventField)){
            return true;
        }else{
            throw new \Exception("Error on updating custom event field");
        }
    }

    /**
     * @param int $customEventFieldId
     * @return bool
     * @throws \Exception
     */
    public function deleteCustomEventField (int $customEventFieldId)
    {
        if($this->delete($customEventFieldId)){
            return true;
        }else{
            throw new \Exception("Error on deleting custom event field");
        }
    }


}
