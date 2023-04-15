<?php

namespace App\Models;

use App\Entities\CustomEventFieldOption;
use CodeIgniter\Model;
use Faker\Generator;

class CustomEventFieldOptionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_cef_options';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = CustomEventFieldOption::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['custom_event_field_id','name'];

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
     * @return CustomEventFieldOption
     */
    public function fake (Generator &$faker)
    {
        return new CustomEventFieldOption([
            'name' => $faker->name
        ]);
    }

    /**
     * @param int $id
     * @return CustomEventFieldOption|null
     */
    public function getCustomEventFieldOption (int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $customEventFieldId
     * @return CustomEventFieldOption[]
     */
    public function getCustomEventFieldOptions (int $customEventFieldId)
    {
        return $this->where('custom_event_field_id',$customEventFieldId)->findAll();
    }

    /**
     * @param int $customEventFieldId
     * @param CustomEventFieldOption $customEventFieldOption
     * @return CustomEventFieldOption
     * @throws \ReflectionException
     */
    public function addCustomEventFieldOption (int $customEventFieldId, CustomEventFieldOption $customEventFieldOption)
    {
        $customEventFieldOption->setCustomEventFieldId($customEventFieldId);
        $insertId = $this->insert($customEventFieldOption);
        if($insertId){
            $customEventFieldOption->setId($insertId);
        }else{
            throw new \Exception("Error on adding custom event field option");
        }
        return $customEventFieldOption;
    }

    /**
     * @param int $customEventFieldOptionId
     * @param CustomEventFieldOption $customEventFieldOption
     * @return bool
     * @throws \ReflectionException
     */
    public function updateCustomEventFieldOption (int $customEventFieldOptionId, CustomEventFieldOption $customEventFieldOption)
    {
        if($this->update($customEventFieldOptionId,$customEventFieldOption)){
            return true;
        }else{
            throw new \Exception("Error on updating custom event field option");
        }
    }

    /**
     * @param int $customEventFieldOptionId
     * @return bool
     * @throws \Exception
     */
    public function deleteCustomEventFieldOption (int $customEventFieldOptionId)
    {
        if($this->delete($customEventFieldOptionId)){
            return true;
        }else{
            throw new \Exception("Error on deleting custom event field option");
        }
    }

}
