<?php

namespace Tests\App\Models;

use App\Entities\Calendar;
use App\Entities\User;
use App\Entities\UserInfo;
use App\Models\AccessKeyModel;
use App\Models\CalendarModel;
use App\Models\SubCalendarModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class AccessKeyModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";

    protected AccessKeyModel $accessKeyModel;
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

        $this->accessKeyModel = new AccessKeyModel();
        $this->calendarModel = new CalendarModel();
        $this->fabricator = new Fabricator($this->accessKeyModel);
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

    public function testAccessKeyModel()
    {

        //add calendar
        echo "Adding new access key";
        $accessKey = $this->fabricator->make();
        $accessKey = $this->accessKeyModel->addAccessKey($this->testCalendar->getId(),$accessKey);
        $criteria = [
            'id' =>  $accessKey->getId(),
        ];
        $this->seeInDatabase($this->accessKeyModel->table, $criteria);
        //get  by id
        echo "\nGet access key by id";
        $returnedAccessKey = $this->accessKeyModel->getAccessKey($accessKey->getId());
        $this->assertEquals($accessKey->getName(),$returnedAccessKey->getName());

        //get all access keys
        echo "\nGet all access keys ";
        $accessKeys = $this->accessKeyModel->getAccessKeys($this->testCalendar->getId());
        $this->seeNumRecords(count($accessKeys),$this->accessKeyModel->table,['calendar_id' => $this->testCalendar->getId()]);
        //update access key
        echo "\nUpdate access key";
        $accessKey->setName('Name Updated');
        $accessKey->setActive(false);
        $updateResult = $this->accessKeyModel->updateAccessKey($accessKey->getId(),$accessKey);
        $this->assertEquals(true,$updateResult);
        $updatedAccessKey = $this->accessKeyModel->getAccessKey($accessKey->getId());
        $this->assertEquals($accessKey->getName(),$updatedAccessKey->getName());
        $this->assertEquals(false,$updatedAccessKey->isActive());

        //delete access key
        echo "\nDelete access key";
        $deleteResult = $this->accessKeyModel->deleteAccessKey($accessKey->getId());
        $this->assertEquals(true,$deleteResult);

        ob_flush();
    }






}
