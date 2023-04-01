<?php

namespace Tests\App\Models;

use App\Entities\Calendar;
use App\Entities\User;
use App\Entities\UserInfo;
use App\Models\CalendarModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class CalendarModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";

    protected CalendarModel $calendarModel;
    protected Fabricator $fabricator;
    protected User $testUser;

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

        $this->calendarModel = new CalendarModel();
        $this->fabricator = new Fabricator($this->calendarModel);
        //check testUser
        $this->testUser = auth()->getUser();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Do something here....
    }

    public function testCalendarModel()
    {

        //add calendar
        echo "Adding new calendar";
        $calendar = $this->fabricator->make();
        $calendar = $this->calendarModel->addCalendar($this->testUser->id,$calendar) ;
        $criteria = [
            'id' =>  $calendar->getId(),
        ];
        $this->seeInDatabase($this->calendarModel->table, $criteria);
        //get  calendar by id
        echo "\nGet calendar by id";
        $returnedCalendar = $this->calendarModel->getCalendar($calendar->getId());
        $this->assertEquals($calendar->getName(),$returnedCalendar->getName());

        //update calendar
        echo "\nUpdate calendar";
        $calendar->setName('Name Updated');
        $calendar->setActive(false);
        $updateResult = $this->calendarModel->update($calendar->getId(),$calendar);
        $this->assertEquals(true,$updateResult);
        $updatedCalendar = $this->calendarModel->getCalendar($calendar->getId());
        $this->assertEquals($calendar->getName(),$updatedCalendar->getName());
        $this->assertEquals(false,$updatedCalendar->isActive());

        //update calendar
        echo "\nDelete calendar";
        $deleteResult = $this->calendarModel->deleteCalendar($calendar->getId());
        $this->assertEquals(true,$deleteResult);

        ob_flush();
    }






}
