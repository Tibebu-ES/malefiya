<?php

namespace Tests\App\Models;

use App\Entities\Calendar;
use App\Entities\User;
use App\Entities\UserInfo;
use App\Models\CalendarModel;
use App\Models\SubCalendarModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class SubCalendarModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";

    protected SubCalendarModel $subCalendarModel;
    protected CalendarModel $calendarModel;
    protected Fabricator $fabricator;
    protected User $testUser;
    protected Calendar $testCalendar;

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

        $this->subCalendarModel = new SubCalendarModel();
        $this->calendarModel = new CalendarModel();
        $this->fabricator = new Fabricator($this->subCalendarModel);
        //check testUser
        $this->testUser = auth()->getUser();
        //add test calendar
        $testCalendar = (new Fabricator($this->calendarModel))->make();
        $this->testCalendar = $this->calendarModel->addCalendar($this->testUser->id,$testCalendar);


    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Do something here....
    }

    public function testCalendarModel()
    {

        //add calendar
        echo "Adding new sub calendar";
        $subCalendar = $this->fabricator->make();
        $subCalendar = $this->subCalendarModel->addSubCalendar($this->testCalendar->getId(),$subCalendar);
        $criteria = [
            'id' =>  $subCalendar->getId(),
        ];
        $this->seeInDatabase($this->subCalendarModel->table, $criteria);
        //get  sub-calendar by id
        echo "\nGet sub-calendar by id";
        $returnedSubCalendar = $this->subCalendarModel->getSubCalendar($subCalendar->getId());
        $this->assertEquals($subCalendar->getName(),$returnedSubCalendar->getName());

        //get all sub-calendars
        echo "\nGet all sub-calendars ";
        $subCalendars = $this->subCalendarModel->getSubCalendars($this->testCalendar->getId());
        $this->seeNumRecords(count($subCalendars),$this->subCalendarModel->table,['calendar_id' => $this->testCalendar->getId()]);
        //update sub-calendar
        echo "\nUpdate sub-calendar";
        $subCalendar->setName('Name Updated');
        $subCalendar->setActive(false);
        $updateResult = $this->subCalendarModel->update($subCalendar->getId(),$subCalendar);
        $this->assertEquals(true,$updateResult);
        $updatedSubCalendar = $this->subCalendarModel->getSubCalendar($subCalendar->getId());
        $this->assertEquals($subCalendar->getName(),$updatedSubCalendar->getName());
        $this->assertEquals(false,$updatedSubCalendar->isActive());

        //update calendar
        echo "\nDelete sub-calendar";
        $deleteResult = $this->subCalendarModel->deleteSubCalendar($subCalendar->getId());
        $this->assertEquals(true,$deleteResult);
        ob_flush();
    }






}
