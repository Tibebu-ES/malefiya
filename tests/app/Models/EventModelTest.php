<?php

namespace Tests\App\Models;

use App\Entities\Calendar;
use App\Entities\SubCalendar;
use App\Entities\User;
use App\Entities\UserInfo;
use App\Models\CalendarModel;
use App\Models\EventModel;
use App\Models\SubCalendarModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class EventModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";

    protected EventModel $eventModel;
    protected SubCalendarModel $subCalendarModel;
    protected CalendarModel $calendarModel;
    protected Fabricator $fabricator;
    protected User $testUser;
    protected Calendar $testCalendar;
    protected SubCalendar $testSubCalendar;

    protected function setUp(): void
    {
        parent::setUp();

        // Do something here....
        log_message("debug", __METHOD__." --- setup" );
        //add test user
        $this->call("post","register",[
            'email' => 'test@malefiya.com',
            'username' => 'test',
            'password' => '377740art',
            'password_confirm' => '377740art'
        ]);

        $this->eventModel = new EventModel();
        $this->subCalendarModel = new SubCalendarModel();
        $this->calendarModel = new CalendarModel();
        $this->fabricator = new Fabricator($this->eventModel);
        //check testUser
        $this->testUser = auth()->getUser();
        //add test calendar
        $testCalendar = (new Fabricator($this->calendarModel))->make();
        $this->testCalendar = $this->calendarModel->addCalendar($this->testUser->id,$testCalendar);
        $testSubCalendar = (new Fabricator($this->subCalendarModel))->make();
        $this->testSubCalendar = $this->subCalendarModel->addSubCalendar($this->testCalendar->getId(),$testSubCalendar);


    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Do something here....
    }

    public function testEventModel()
    {

        //add event
        echo "Adding new  event";
        $event = $this->fabricator->make();
        $event->setSubCalendarId($this->testSubCalendar->getId());
        $event = $this->eventModel->addEvent($event);
        $criteria = [
            'id' =>  $event->getId(),
        ];
        $this->seeInDatabase($this->eventModel->table, $criteria);
        //get  event by id
        echo "\nGet event by id";
        $returnedEvent = $this->eventModel->getEvent($event->getId());
        $this->assertEquals($event->getId(),$returnedEvent->getId());
        //get  events
        echo "\nGet events ";
        $events = $this->eventModel->getEvents([$this->testSubCalendar->getId()]);
        $this->seeNumRecords(count($events),$this->eventModel->table,['sub_calendar_id' => $this->testCalendar->getId()]);
        echo "\nUpdate event";
        $event->setTitle("Title Updated");
        $updateResult = $this->eventModel->updateEvent($event->getId(),$event);
        $this->assertEquals(true,$updateResult);
        $updatedEvent = $this->eventModel->getEvent($event->getId());
        $this->assertEquals($event->getTitle(),$updatedEvent->getTitle());
        //delete event
        echo "\nDelete event";
        $deleteResult = $this->eventModel->deleteEvent($event->getId());
        $this->assertEquals(true,$deleteResult);

        //delete events
        //add 5 events
        foreach ($this->fabricator->make(5) as  $event){
            $event->setSubCalendarId($this->testSubCalendar->getId());
            $this->eventModel->addEvent($event);
        }
        echo "\nDelete events";
        $multipleDeleteResult = $this->eventModel->deleteEvents([$this->testSubCalendar->getId()]);
        $this->assertEquals(true,$multipleDeleteResult);
        ob_flush();
    }






}
