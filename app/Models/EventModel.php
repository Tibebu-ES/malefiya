<?php

namespace App\Models;

use App\Entities\Event;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use Faker\Generator;

class EventModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'malefiya_events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = Event::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['sub_calendar_id','title','all_day','start_date','end_date','duration','rrule','about','where','who'];

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

    //

    /**
     * @param Generator $faker
     * @return Event
     * @throws \Exception
     */
    public function fake (Generator &$faker)
    {
        $startDate = $faker->dateTimeBetween('-1 week', '+4 week');
        $startDate = new Time($startDate->format('Y-m-d H:i:s'));
        $endDate = $startDate->addHours(2);
        return new Event([
           'title' => $faker->sentence(4),
           'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    //get event by id

    /**
     * @param int $id
     * @return Event|null
     */
    public function getEvent (int $id)
    {
        return $this->find($id);
    }

    /**
     * @param array $subCalendarIds
     * @return Event[]
     */
    public function getEvents (array $subCalendarIds)
    {
        return $this->whereIn('sub_calendar_id',$subCalendarIds)->findAll();
    }

    /**
     * @param Event $event
     * @return Event
     * @throws \ReflectionException
     */
    public function addEvent (Event $event)
    {
        $insertId = $this->insert($event);
        if($insertId){
            $event->setId($insertId);
        }else{
            throw new \Exception("Error on adding event");
        }
        return $event;
    }

    /**
     * @param int $id
     * @param Event $event
     * @return bool
     * @throws \ReflectionException
     */
    public function updateEvent (int $id, Event $event)
    {
        if($this->update($id,$event)){
            return true;
        }else{
            throw new \Exception("Error on updating event");
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteEvent (int $id)
    {
        if($this->delete($id)){
            return true;
        }else{
            throw new \Exception("Error on deleting event");
        }
    }

    /**
     * Delete all events in the given subcalendars
     * @param array $subCalendarIds
     * @return bool
     */
    public function deleteEvents (array $subCalendarIds)
    {
       if($this->whereIn('sub_calendar_id',$subCalendarIds)->delete()){
           return true;
       }else{
           throw new \Exception("Error on deleting events");
       }
    }
}
