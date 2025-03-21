<?php
namespace Src\Controller;

use Src\Model\CalendarUserModel;
use Src\Middleware\Auth;
use Src\Model\CalendarModel;
use Src\Model\EventModel;
use Src\Model\ReminderModel;
use Src\Model\ScheduleModel;
use Src\Model\UserModel;

class PublicController extends BaseController{

    public function index(){
        if(Auth::user()) redirect("dashboard");
        redirect("login_account_get");
    }

    public function migrate(){
        $user = new UserModel();
        $user->migrate();

        $calendar = new CalendarModel();
        $calendar->migrate();

        $userCalendarAssoc = new CalendarUserModel();
        $userCalendarAssoc->migrate();

        $scheduleModel = new ScheduleModel();
        $scheduleModel->migrate();


        //var_dump($userCalendarAssoc->getAllFromRelatedModel('tb_calendar_model', 'calendar_id', 'user_id', Auth::getUserId()));
    }

}

?>