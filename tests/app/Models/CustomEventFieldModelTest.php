<?php

namespace Tests\App\Models;

use App\Entities\Calendar;
use App\Entities\User;
use App\Entities\UserInfo;
use App\Models\CalendarModel;
use App\Models\CustomEventFieldModel;
use App\Models\SubCalendarModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class CustomEventFieldModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";

    protected CustomEventFieldModel $customEventFieldModel;
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

        $this->customEventFieldModel = new CustomEventFieldModel();
        $this->calendarModel = new CalendarModel();
        $this->fabricator = new Fabricator($this->customEventFieldModel);
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

    public function testCustomEventFieldModel()
    {

        //add custom event field
        echo "Adding new custom event field ";
        $customEventField = $this->fabricator->make();
        $customEventField = $this->customEventFieldModel->addCustomEventField($this->testCalendar->getId(),$customEventField);
        $criteria = [
            'id' =>  $customEventField->getId(),
        ];
        $this->seeInDatabase($this->customEventFieldModel->table, $criteria);
        //get  custom event field by id
        echo "\nGet custom event field by id";
        $returnedCustomEventField = $this->customEventFieldModel->getCustomEventField($customEventField->getId());
        $this->assertEquals($customEventField->getId(),$returnedCustomEventField->getId());

        //get all custom event fields
        echo "\nGet all custom event fields ";
        $customEventFields = $this->customEventFieldModel->getCustomEventFields($this->testCalendar->getId());
        $this->seeNumRecords(count($customEventFields),$this->customEventFieldModel->table,['calendar_id' => $this->testCalendar->getId()]);

        //update custom event field
        echo "\nUpdate custom event field";
        $customEventField->setName('Name Updated');
        $updateResult = $this->customEventFieldModel->update($customEventField->getId(),$customEventField);
        $this->assertEquals(true,$updateResult);
        $updatedCustomEventField = $this->customEventFieldModel->getCustomEventField($customEventField->getId());
        $this->assertEquals($customEventField->getName(),$updatedCustomEventField->getName());

        //delete custom event field
        echo "\nDelete custom event field";
        $deleteResult = $this->customEventFieldModel->deleteCustomEventField($customEventField->getId());
        $this->assertEquals(true,$deleteResult);

        ob_flush();
    }






}
