<?php

namespace App\Models;

use App\Entities\User;
use App\Entities\UserInfo;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\FeatureTestTrait;


class UserInfoModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;


    protected $seed = "InitSystem";
    protected $seedOnce = true;
    //where the seeder files are located
    protected $basePath = "./app/Database";

    protected UserInfoModel $userInfoModel;
    protected Fabricator $fabricator;
    protected User $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Do something here....
        log_message("debug", "[UserInfoModelTest][setUp] --- setup" );
        //add test user
        $this->call("post","register",[
            'email' => 'test@malefiya.com',
            'username' => 'test',
            'password' => '377740art',
            'password_confirm' => '377740art'
        ]);

        $this->userInfoModel = new UserInfoModel();
        $this->fabricator = new Fabricator($this->userInfoModel);
        //check testUser
        $this->testUser = auth()->getUser();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Do something here....
    }

    public function testUserInfoModel()
    {
        //test getUserInfo -
        $result = $this->userInfoModel->getUserInfo($this->testUser->id);
        $this->assertEmpty($result);
        //add new userinfo
        $userInfo = $this->fabricator->make();
        $result = $this->userInfoModel->saveUserInfo($this->testUser->id,$userInfo);
        $this->assertEquals(true,$result);

        //update userInfo
        $userInfo->first_name = "admin";
        $userInfo->last_name = "admin";
        $result = $this->userInfoModel->saveUserInfo($this->testUser->id,$userInfo);
        $this->assertEquals(true,$result);
        $criteria = [
            'user_id' =>  $userInfo->user_id,
            'first_name' => $userInfo->first_name,
            'last_name' =>  $userInfo->last_name
        ];
        $this->seeInDatabase($this->userInfoModel->table, $criteria);
        //test getUserInfo -
        $result = $this->userInfoModel->getUserInfo($this->testUser->id);
        $this->assertNotEmpty($result);

    }






}
