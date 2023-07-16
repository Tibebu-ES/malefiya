<?php

namespace App\Database\Seeds;

use App\Entities\SubCalendarPermission;
use CodeIgniter\Shield\Entities\User;
use App\Models\AccessKeyModel;
use App\Models\CalendarModel;
use App\Models\EventModel;
use App\Models\SubCalendarModel;
use App\Models\SubCalendarPermissionModel;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\HTTP\Request;
use CodeIgniter\Test\Fabricator;
use function CodeIgniter\Config\Services;

class InitSystem extends Seeder
{


    public function run()
    {
        helper('setting');
        log_message('info','[InitSystem] Initializing System');
        $migrate = \Config\Services::migrations();
        $migrate->regress(1);
        //init shield library
        log_message('info','[InitSystem] Initializing Codeigniter Shield');
        command("shield:setup -f");

        //add test user
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        $user = new User([
            'username' => 'admin',
            'email'    => 'admin@example.com',
            'password' => '377740art',
        ]);
        $users->save($user);

        // To get the complete user object with ID, we need to get from the database
        $user = $users->findById($users->getInsertID());


        // Add to default group
        $users->addToDefaultGroup($user);
        $users->activate($user);

        //create a calendar
        $calendarModel = new CalendarModel();
        $calendar = (new Fabricator($calendarModel))->make();
        $calendar = $calendarModel->addCalendar($users->getInsertID(),$calendar);

        //add 50 sub-calendars
        $subCalendarModel = new SubCalendarModel();
        $subCalendars = (new Fabricator($subCalendarModel))->make(50);
        foreach ($subCalendars as $subCalendar){
            $subCalendarModel->addSubCalendar($calendar->getId(),$subCalendar);
        }
        $subCalendars = $subCalendarModel->getSubCalendars($calendar->getId());

        //add 10 events to each sub-calendar
        $eventModel = new EventModel();
        $eventFabricator = new Fabricator($eventModel);
        foreach ($subCalendars as $subCalendar){
            foreach ($eventFabricator->make(10) as $event){
                $event->setSubCalendarId($subCalendar->getId());
                $eventModel->addEvent($event);
            }
        }

        //create 10 access keys
        $accessKeyModel = new AccessKeyModel();
        $accessKeys = (new Fabricator($accessKeyModel))->make(10);
        foreach ($accessKeys as $accessKey){
            $accessKeyModel->addAccessKey($calendar->getId(),$accessKey);
        }
        $accessKeys = $accessKeyModel->getAccessKeys($calendar->getId());

        //add sub calendar permissions for each access key
        $subCalendarPermissionModel = new SubCalendarPermissionModel();
        $subCalendarPermissionFabricator = new Fabricator($subCalendarPermissionModel);
        foreach ($accessKeys as $accessKey){
            foreach ($subCalendars as $subCalendar){
                $subCalendarPermission = $subCalendarPermissionFabricator->make();
                $subCalendarPermission->setSubCalendarId($subCalendar->getId());
                $subCalendarPermission->setAccessKeyId($accessKey->getId());
                $subCalendarPermissionModel->addSubCalendarPermission($subCalendarPermission);
            }
        }

    }
}
