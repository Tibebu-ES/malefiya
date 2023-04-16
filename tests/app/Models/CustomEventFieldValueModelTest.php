<?php

namespace Tests\App\Models;

use App\Entities\Calendar;
use App\Entities\CustomEventField;
use App\Entities\CustomEventFieldValue;
use App\Entities\Event;
use App\Entities\SubCalendar;
use App\Entities\User;
use App\Entities\UserInfo;
use App\Models\CalendarModel;
use App\Models\CustomEventFieldModel;
use App\Models\CustomEventFieldOptionModel;
use App\Models\CustomEventFieldValueModel;
use App\Models\EventModel;
use App\Models\SubCalendarModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class CustomEventFieldValueModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";


    protected CustomEventFieldValueModel $customEventFieldValueModel;
    protected CustomEventFieldOptionModel $customEventFieldOptionModel;
    protected CustomEventFieldModel $customEventFieldModel;
    protected CalendarModel $calendarModel;
    protected SubCalendarModel $subCalendarModel;
    protected EventModel $eventModel;
    protected User $testUser;
    protected Calendar $testCalendar;
    protected SubCalendar $testSubCalendar;
    protected Event $testEvent;
    protected CustomEventField $testCustomEventTextField,$testCustomEventSSelectField,$testCustomEventMSelectField;

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

        $this->customEventFieldValueModel = new CustomEventFieldValueModel();

        $this->customEventFieldOptionModel = new CustomEventFieldOptionModel();
        $this->customEventFieldModel = new CustomEventFieldModel();
        $this->calendarModel = new CalendarModel();
        $this->subCalendarModel = new SubCalendarModel();
        $this->eventModel = new EventModel();
        //check testUser
        $this->testUser = auth()->getUser();
        //add test calendar
        $testCalendar = (new Fabricator($this->calendarModel))->make();
        $this->testCalendar = $this->calendarModel->addCalendar($this->testUser->id,$testCalendar);
        $testSubCalendar = (new Fabricator($this->subCalendarModel))->make();
        $this->testSubCalendar = $this->subCalendarModel->addSubCalendar($this->testCalendar->getId(),$testSubCalendar);
        $testEvent = (new Fabricator($this->eventModel))->make();
        $testEvent->setSubCalendarId($this->testSubCalendar->getId());
        $this->testEvent = $this->eventModel->addEvent($testEvent);
        //add three custom fields
        $testCustomEventTextField = new CustomEventField();
        $testCustomEventTextField->setName("Text CEF");
        $testCustomEventTextField->setType(CustomEventField::TYPE_TEXT);
        $this->testCustomEventTextField = $this->customEventFieldModel->addCustomEventField($this->testCalendar->getId(),$testCustomEventTextField);
        $testCustomEventSSelectField = new CustomEventField();
        $testCustomEventSSelectField->setName("S Select CEF");
        $testCustomEventSSelectField->setType(CustomEventField::TYPE_SINGLE_SELECT);
        $this->testCustomEventSSelectField = $this->customEventFieldModel->addCustomEventField($this->testCalendar->getId(),$testCustomEventSSelectField);
        $testCustomEventMSelectField = new CustomEventField();
        $testCustomEventMSelectField->setName("M Select CEF");
        $testCustomEventMSelectField->setType(CustomEventField::TYPE_MULTIPLE_SELECT);
        $this->testCustomEventMSelectField = $this->customEventFieldModel->addCustomEventField($this->testCalendar->getId(),$testCustomEventMSelectField);
        //add options for the last two custom fields
        $testSSelectCustomFieldOptions = (new Fabricator($this->customEventFieldOptionModel))->make(5);
        foreach ($testSSelectCustomFieldOptions as $testSSelectCustomFieldOption){
            $this->customEventFieldOptionModel->addCustomEventFieldOption($this->testCustomEventSSelectField->getId(),$testSSelectCustomFieldOption);
        }
        $testMSelectCustomFieldOptions = (new Fabricator($this->customEventFieldOptionModel))->make(10);
        foreach ($testMSelectCustomFieldOptions as $testMSelectCustomFieldOption){
            $this->customEventFieldOptionModel->addCustomEventFieldOption($this->testCustomEventMSelectField->getId(),$testMSelectCustomFieldOption);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Do something here....
    }

    public function testCustomEventFieldValueModel()
    {

        //add custom event field value - for text field
        echo "\n\rAdding new custom event text field value";
        $customTextFieldValue = new CustomEventFieldValue();
        $customTextFieldValue->setEventId($this->testEvent->getId());
        $customTextFieldValue->setCustomEventFieldId($this->testCustomEventTextField->getId());
        $customTextFieldValue->setValue("Test text value");
        $this->customEventFieldValueModel->saveCustomEventFieldValue($customTextFieldValue);
        $criteria = [
            'event_id' =>  $customTextFieldValue->getEventId(),
            'custom_event_field_id' => $customTextFieldValue->getCustomEventFieldId(),
            'value' => $customTextFieldValue->getValue()
        ];
        $this->seeInDatabase($this->customEventFieldValueModel->table, $criteria);
        echo "\n\rUpdate custom event text field value";
        $customTextFieldValue->setValue("Test text value updated");
        $this->customEventFieldValueModel->saveCustomEventFieldValue($customTextFieldValue);
        $criteria['value'] = $customTextFieldValue->getValue();
        $this->seeInDatabase($this->customEventFieldValueModel->table, $criteria);

        //add custom event field value - for single select field
        echo "\n\rAdding new custom event single select field value";
        $customSSelectFieldValue = new CustomEventFieldValue();
        $customSSelectFieldValue->setEventId($this->testEvent->getId());
        $customSSelectFieldValue->setCustomEventFieldId($this->testCustomEventSSelectField->getId());
        $customSSelectFieldValue->setValue(2);
        $this->customEventFieldValueModel->saveCustomEventFieldValue($customSSelectFieldValue);
        $criteria = [
            'event_id' =>  $customSSelectFieldValue->getEventId(),
            'custom_event_field_id' => $customSSelectFieldValue->getCustomEventFieldId(),
            'value' => $customSSelectFieldValue->getValue()
        ];
        $this->seeInDatabase($this->customEventFieldValueModel->table, $criteria);
        echo "\n\rUpdate custom event single select field value";
        $customSSelectFieldValue->setValue(3);
        $this->customEventFieldValueModel->saveCustomEventFieldValue($customSSelectFieldValue);
        $criteria['value'] = $customSSelectFieldValue->getValue();
        $this->seeInDatabase($this->customEventFieldValueModel->table, $criteria);
        /*echo "\n\rAdding new custom event single select field value - invalid value";
        $this->expectException(\Exception::class);
        $customSSelectFieldValue->setValue(20);
        $this->customEventFieldValueModel->saveCustomEventFieldValue($customSSelectFieldValue);
        */
        //add custom event field value - for multiple select field
        echo "\n\rSave custom event multiple select field values -initially no value";
        $values = [1,2,3];
        $this->customEventFieldValueModel->saveCustomEventFieldValues($this->testEvent->getId(),$this->testCustomEventMSelectField->getId(),$values);
        $this->seeNumRecords(count($values),$this->customEventFieldValueModel->table,[ 'event_id' =>$this->testEvent->getId(), 'custom_event_field_id' =>$this->testCustomEventMSelectField->getId()]);

        //update custom event field value - for multiple select field
        echo "\n\rSave custom event multiple select field values -when there are old values";
        $values = [4,5];
        $this->customEventFieldValueModel->saveCustomEventFieldValues($this->testEvent->getId(),$this->testCustomEventMSelectField->getId(),$values);
        $this->seeNumRecords(count($values),$this->customEventFieldValueModel->table,[ 'event_id' =>$this->testEvent->getId(), 'custom_event_field_id' =>$this->testCustomEventMSelectField->getId()]);
        echo "\n\rGet custom event multiple select field values ";
        $customEventMSelectValues = $this->customEventFieldValueModel->getCustomEventFieldValue($this->testEvent->getId(),$this->testCustomEventMSelectField->getId());
        $this->seeNumRecords(count($customEventMSelectValues),$this->customEventFieldValueModel->table,[ 'event_id' =>$this->testEvent->getId(), 'custom_event_field_id' =>$this->testCustomEventMSelectField->getId()]);
        echo "\n\rDelete custom event multiple select field values ";
        $deleteResult = $this->customEventFieldValueModel->deleteCustomEventFieldValue($this->testEvent->getId(),$this->testCustomEventMSelectField->getId());
        $this->assertEquals(true,$deleteResult);
        $customEventMSelectValues = $this->customEventFieldValueModel->getCustomEventFieldValue($this->testEvent->getId(),$this->testCustomEventMSelectField->getId());
        $this->assertEmpty($customEventMSelectValues);


        ob_flush();
    }






}
