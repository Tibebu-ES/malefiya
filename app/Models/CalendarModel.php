<?php

namespace App\Models;

use App\Entities\Calendar;
use CodeIgniter\Model;
use Faker\Generator;

class CalendarModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_calendars';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = Calendar::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id','name','active','about','timezone','locale'];

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
     * @return Calendar
     */
    public function fake (Generator &$faker)
    {
        return new Calendar([
            'name' =>$faker->company(),
            'active' => true,
            'about' => $faker->paragraph(),
            'timezone' => $faker->timezone,
            'locale' => $faker->locale
        ]);
    }


    /**
     * @param int $id
     * @return Calendar|null
     */
    public function getCalendar (int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $userId
     * @return Calendar[]
     */
    public function getCalendars (int $userId)
    {
        return $this->where('user_id',$userId)->findAll();
    }

    /**
     *  add new calendar
     * @param int $userid
     * @param Calendar $calendar
     * @return Calendar
     * @throws \ReflectionException
     */
    public function addCalendar (int $userid, Calendar $calendar)
    {
        $calendar->user_id = $userid;
        $insertId = $this->insert($calendar);
        if($insertId){
            $calendar->id = $insertId;
        }else{
            throw new \Exception("Error on adding calendar");
        }
        return $calendar;
    }

    /**
     * @param int $calendarId
     * @param Calendar $calendar
     * @return bool
     * @throws \ReflectionException
     */
    public function updateCalendar (int $calendarId, Calendar $calendar)
    {
        //todo prevent updating user_id
        if($this->update($calendarId,$calendar)){
            return true;
        }else{
            throw new \Exception("Error on updating calendar");
        }

    }

    /**
     * @param int $calendarId
     * @return bool
     * @throws \Exception
     */
    public function deleteCalendar (int $calendarId)
    {
        if($this->delete($calendarId)){
            return true;
        }else{
            throw new \Exception("Error on deleting calendar");
        }
    }


}
