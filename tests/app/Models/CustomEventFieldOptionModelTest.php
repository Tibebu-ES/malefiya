<?php

namespace Tests\App\Models;

use App\Entities\Calendar;
use App\Entities\CustomEventField;
use App\Entities\User;
use App\Entities\UserInfo;
use App\Models\CalendarModel;
use App\Models\CustomEventFieldModel;
use App\Models\CustomEventFieldOptionModel;
use App\Models\SubCalendarModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class CustomEventFieldOptionModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";

    protected CustomEventFieldOptionModel $customEventFieldOptionModel;
    protected CustomEventFieldModel $customEventFieldModel;
    protected CalendarModel $calendarModel;
    protected Fabricator $fabricator;
    protected User $testUser;
    protected Calendar $testCalendar;
    protected CustomEventField $testCustomEventField;

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

        $this->customEventFieldOptionModel = new CustomEventFieldOptionModel();
        $this->customEventFieldModel = new CustomEventFieldModel();
        $this->calendarModel = new CalendarModel();
        $this->fabricator = new Fabricator($this->customEventFieldOptionModel);
        //check testUser
        $this->testUser = auth()->getUser();
        //add test calendar
        $testCalendar = (new Fabricator($this->calendarModel))->make();
        $this->testCalendar = $this->calendarModel->addCalendar($this->testUser->id,$testCalendar);
        $testCustomEventField = (new Fabricator($this->customEventFieldModel))->make();
        $testCustomEventField->setType(CustomEventField::TYPE_SINGLE_SELECT);
        $this->testCustomEventField = $this->customEventFieldModel->addCustomEventField($this->testCalendar->getId(),$testCustomEventField);


    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Do something here....
    }

    public function testCustomEventFieldOptionModel()
    {

        //add custom event field option
        echo "Adding new custom event field option";
        $customEventFieldOption = $this->fabricator->make();
        $customEventFieldOption = $this->customEventFieldOptionModel->addCustomEventFieldOption($this->testCustomEventField->getId(),$customEventFieldOption);
        $criteria = [
            'id' =>  $customEventFieldOption->getId(),
        ];
        $this->seeInDatabase($this->customEventFieldOptionModel->table, $criteria);

        //get  custom event field option by id
        echo "\nGet custom event field option by id";
        $returnedCustomEventFieldOption = $this->customEventFieldOptionModel->getCustomEventFieldOption($customEventFieldOption->getId());
        $this->assertEquals($customEventFieldOption->getId(),$returnedCustomEventFieldOption->getId());

        //get all custom event field options
        echo "\nGet all custom event field options";
        $customEventFieldOptions = $this->customEventFieldOptionModel->getCustomEventFieldOptions($this->testCustomEventField->getId());
        $this->seeNumRecords(count($customEventFieldOptions),$this->customEventFieldOptionModel->table,['custom_event_field_id' => $this->testCustomEventField->getId()]);

        //update custom event field option
        echo "\nUpdate custom event field option";
        $customEventFieldOption->setName('Name Updated');
        $updateResult = $this->customEventFieldOptionModel->updateCustomEventFieldOption($customEventFieldOption->getId(),$customEventFieldOption);
        $this->assertEquals(true,$updateResult);
        $updatedCustomEventFieldOption = $this->customEventFieldOptionModel->getCustomEventFieldOption($customEventFieldOption->getId());
        $this->assertEquals($customEventFieldOption->getName(),$updatedCustomEventFieldOption->getName());

        //delete custom event field option
        echo "\nDelete custom event field option";
        $deleteResult = $this->customEventFieldOptionModel->deleteCustomEventFieldOption($updatedCustomEventFieldOption->getId());
        $this->assertEquals(true,$deleteResult);
        ob_flush();
    }






}
