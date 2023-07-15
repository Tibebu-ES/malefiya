<?php

namespace Tests\App\Models;

use App\Entities\AccessKey;
use App\Entities\Calendar;
use App\Entities\SubCalendar;
use App\Entities\SubCalendarPermission;
use App\Entities\User;
use App\Entities\UserInfo;
use App\Models\AccessKeyModel;
use App\Models\CalendarModel;
use App\Models\SubCalendarModel;
use App\Models\SubCalendarPermissionModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class SubCalendarPermissionModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";

    protected SubCalendarModel $subCalendarModel;
    protected CalendarModel $calendarModel;
    protected AccessKeyModel $accessKeyModel;
    protected SubCalendarPermissionModel $subCalendarPermissionModel;
    protected Fabricator $fabricator;
    protected User $testUser;
    protected Calendar $testCalendar;
    protected SubCalendar $testSubCalendar;
    protected AccessKey $testAccessKey;

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
        $this->subCalendarPermissionModel = new SubCalendarPermissionModel();
        $this->accessKeyModel = new AccessKeyModel();
        $this->fabricator = new Fabricator($this->subCalendarPermissionModel);
        //check testUser
        $this->testUser = auth()->getUser();
        //add test calendar
        $testCalendar = (new Fabricator($this->calendarModel))->make();
        $this->testCalendar = $this->calendarModel->addCalendar($this->testUser->id,$testCalendar);
        $testSubCalendar = (new Fabricator($this->subCalendarModel))->make();
        $this->testSubCalendar = $this->subCalendarModel->addSubCalendar($this->testCalendar->getId(),$testSubCalendar);
        $testAccessKey = (new Fabricator($this->accessKeyModel))->make();
        $this->testAccessKey = $this->accessKeyModel->addAccessKey($this->testCalendar->getId(),$testAccessKey);


    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Do something here....
    }

    public function testSubCalendarPermissionModel()
    {

        //add sub calendar permission
        echo "\nAdding new sub calendar permission";
        $subCalendarPermission = $this->fabricator->make();
        $subCalendarPermission->setSubCalendarId($this->testSubCalendar->getId());
        $subCalendarPermission->setAccessKeyId($this->testAccessKey->getId());
        $subCalendarPermission = $this->subCalendarPermissionModel->addSubCalendarPermission($subCalendarPermission);
        $criteria = [
            'id' =>  $subCalendarPermission->getId(),
        ];
        $this->seeInDatabase($this->subCalendarPermissionModel->table, $criteria);
        //get  sub calendar permission by id
        echo "\nGet sub calendar permission by id";
        $returnedSubCalendarPermission = $this->subCalendarPermissionModel->getSubCalendarPermission($subCalendarPermission->getId());
        $this->assertEquals($subCalendarPermission->getAccessType(),$returnedSubCalendarPermission->getAccessType());

        //get sub calendar permissions
        echo "\nGet sub calendar permissions of a given access key ";
        $subCalendarPermissions = $this->subCalendarPermissionModel->getSubCalendarPermissions($this->testAccessKey->getId());
        $this->seeNumRecords(count($subCalendarPermissions),$this->subCalendarPermissionModel->table,['access_key_id' => $this->testAccessKey->getId()]);

        //update sub calendar permission
        echo "\nUpdate sub-calendar permission";
        $subCalendarPermission->setAccessType(SubCalendarPermission::ACCESS_TYPE_MODIFY);
        $updateResult = $this->subCalendarPermissionModel->updateSubCalendarPermission($subCalendarPermission->getId(),$subCalendarPermission);
        $this->assertEquals(true,$updateResult);
        $updatedSubCalendarPermission = $this->subCalendarPermissionModel->getSubCalendarPermission($subCalendarPermission->getId());
        $this->assertEquals($subCalendarPermission->getAccessType(),$updatedSubCalendarPermission->getAccessType());

        echo "\nDelete sub-calendar permission";
        $deleteResult = $this->subCalendarPermissionModel->deleteSubCalendarPermission($subCalendarPermission->getId());
        $this->assertEquals(true,$deleteResult);
        ob_flush();
    }






}
