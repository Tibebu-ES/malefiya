<?php

namespace App\Models;

use App\Entities\SubCalendar;
use CodeIgniter\Model;
use Faker\Generator;

class SubCalendarModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_sub_calendars';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = SubCalendar::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['calendar_id','name','active','overlap','color'];

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
     * @return string[]
     */
    public function getAllowedFields(): array
    {
        return $this->allowedFields;
    }

    /**
     * @param Generator $faker
     * @return SubCalendar
     */
    public function fake (Generator &$faker)
    {
        return new SubCalendar([
            'name' => $faker->name(),
            'active' => $faker->boolean(),
            'overlap' => $faker->boolean(),
            'color' => $faker->hexColor()
        ]);
    }

    /**
     * @param int $id
     * @return SubCalendar|null
     */
    public function getSubCalendar (int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $calendarId
     * @return SubCalendar[]
     */
    public function getSubCalendars (int $calendarId)
    {
        return $this->where('calendar_id',$calendarId)->findAll();
    }

    /**
     * @param int $calendarId
     * @param SubCalendar $subCalendar
     * @return SubCalendar
     * @throws \ReflectionException
     */
    public function addSubCalendar (int $calendarId, SubCalendar $subCalendar)
    {
        $subCalendar->setCalendarId($calendarId);
        $insertId = $this->insert($subCalendar);
        if($insertId){
            $subCalendar->setId($insertId);
        }else{
            throw new \Exception("Error on adding sub calendar");
        }
        return $subCalendar;
    }

    /**
     * @param int $subCalendarId
     * @param SubCalendar $subCalendar
     * @return bool
     * @throws \ReflectionException
     */
    public function updateSubCalendar (int $subCalendarId, SubCalendar $subCalendar)
    {
        if($this->update($subCalendarId,$subCalendar)){
            return true;
        }else{
            throw new \Exception("Error on updating sub calendar");
        }
    }

    /**
     * @param int $subCalendarId
     * @return bool
     * @throws \Exception
     */
    public function deleteSubCalendar (int $subCalendarId)
    {
        if($this->delete($subCalendarId)){
            return true;
        }else{
            throw new \Exception("Error on deleting sub calendar");
        }
    }
}
