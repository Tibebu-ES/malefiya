<?php

namespace App\Models;

use App\Entities\Calendar;
use App\Entities\CustomEventField;
use App\Entities\CustomEventFieldValue;
use App\Entities\Event;
use CodeIgniter\Model;

class CustomEventFieldValueModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_custom_event_field_values';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = CustomEventFieldValue::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['custom_event_field_id','event_id','value'];

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
     * @param int $eventId
     * @param int $customEventFieldId
     * @return CustomEventFieldValue[]|null
     */
    public function getCustomEventFieldValue (int $eventId, int $customEventFieldId){
       return $this->where('custom_event_field_id',$customEventFieldId)->where('event_id',$eventId)->find();
    }

    /**
     * For only custom event field of type = text|s_select
     * Throws error if custom event field type = m_select
     * @param CustomEventFieldValue $customEventFieldValue
     * @return bool
     * @throws \ReflectionException
     */
    public function saveCustomEventFieldValue (CustomEventFieldValue $customEventFieldValue){
        $customField = (new CustomEventFieldModel())->getCustomEventField($customEventFieldValue->getCustomEventFieldId());
        if(empty($customEventFieldValue->getEventId())){
            throw new \Exception(__METHOD__." Event not given.");
        }
        if(empty($customEventFieldValue->getCustomEventFieldId())){
            throw new \Exception(__METHOD__." Custom Event Field not given.");
        }
        //if multiple select event field is given
        if($customField->getType() == CustomEventField::TYPE_MULTIPLE_SELECT){
            throw new \Exception(__METHOD__." Invalid Custom Event Field type. Multiple select custom event field is not supported");
        }
        //for single select field - check if the value is supported - set of the option
        //for the single select field, the value is the id of the corresponding option
        if($customField->getType() == CustomEventField::TYPE_SINGLE_SELECT){
            $customFieldOption = (new CustomEventFieldOptionModel())->getCustomEventFieldOption($customEventFieldValue->getValue());
            if(empty($customFieldOption)){
                throw new \Exception(__METHOD__." Invalid Custom Event Field Value.");
            }
        }
        //check if there is old value
        $oldCustomFieldValues = $this->getCustomEventFieldValue($customEventFieldValue->getEventId(),$customEventFieldValue->getCustomEventFieldId());
        if(!empty($oldCustomFieldValues)){
            $customEventFieldValue->setId($oldCustomFieldValues[0]->getId());
        }
        return $this->save($customEventFieldValue);
    }


    /**
     * save customEventField for m_select fields - remove old values and save new values
     * @param int $eventId
     * @param int $customEventFieldId
     * @param array $values
     * @return void
     * @throws \ReflectionException
     */
    public function saveCustomEventFieldValues (int $eventId, int $customEventFieldId, array $values){
        $customField = (new CustomEventFieldModel())->getCustomEventField($customEventFieldId);
        //if multiple select event field is given
        if($customField->getType() !== CustomEventField::TYPE_MULTIPLE_SELECT){
            throw new \Exception(__METHOD__." Invalid Custom Event Field type. Only multiple select custom event field is supported");
        }
        //check if all values are valid
        foreach ($values as $value){
            $customFieldOption = (new CustomEventFieldOptionModel())->getCustomEventFieldOption($value);
            if(empty($customFieldOption)){
                throw new \Exception(__METHOD__." Invalid Custom Event Field Value = ".$value);
            }
        }
        //remove old values
        $this->deleteCustomEventFieldValue($eventId,$customEventFieldId);
        //add values
        foreach ($values as $value){
            $customEventFieldValue = new CustomEventFieldValue();
            $customEventFieldValue->setEventId($eventId);
            $customEventFieldValue->setCustomEventFieldId($customEventFieldId);
            $customEventFieldValue->setValue($value);
            $this->insert($customEventFieldValue);
        }

    }

    /**
     * delete custom event field value -
     * if the custom field type is multiple select - all values will be removed
     * @param int $eventId
     * @param int $customEventFieldId
     * @return bool|\CodeIgniter\Database\BaseResult
     */
    public function deleteCustomEventFieldValue (int $eventId, int $customEventFieldId){
        return $this->where('custom_event_field_id',$customEventFieldId)->where('event_id',$eventId)->delete();
    }

}
