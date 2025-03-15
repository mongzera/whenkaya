<?php

namespace Src\Controller;

use Src\Middleware\Auth;
use Src\Model\CalendarModel;
use Src\Model\CalendarUserModel;

class UserController extends BaseController{
    
    public function dashboard(){
        
        Auth::redirectIfNotLoggedIn();

        $content = [
            "title" => "hello",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/dashboard.view.php"
        ];

        $static = [
            "css" => ['css/global.css', 'css/theme.css', 'css/dashboard.css'],
            "js"  => ['js/calendar.js', 'js/app.js']
        ];

        render_page($content, $static);
    }

    public function addCalendar(){
        // if(!Auth::user()) {
        //     echo 'failed';
        //     exit();
        // }
        
        if(!isPost()) return;
        echo 'success';
        
        $calendar_name = cleanRequest($_POST['calendar_name']);
        if(!$calendar_name) echo 'failed';
        echo Auth::getUserId();

        $calendar = new CalendarModel();
        $calendar->insert([
            'calendar_name' => $calendar_name
        ]);

        $calendarUserAssoc = new CalendarUserModel();
        $calendarUserAssoc->insert([
            'user_id' => Auth::getUserId(),
            'calendar_id' => $calendar->get('id'),
            'privilage_level' => 0
        ]);



        //insertNewCalendar($schedTitle, Auth::getUserId(), 0);

        //array_push(PublicController::$data, [$_POST['schedule_title'], '/uuid124123d12']);
    }

    public function fetchUserCalendars(){
        header('Content-Type: application/json');

        $userCalendarAssoc = new CalendarUserModel();

        $response = array(
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => [
                'calendars' => $userCalendarAssoc->getAllFromRelatedModel('tb_calendar_model', 'calendar_id', 'user_id', Auth::getUserId(), $select = 'calendar_name')
            ]
        );

        $out = json_encode($response);


        echo $out;
        exit();
    }

    public function requestUserCards(){
        if(!isPost()) return;

        $date = cleanRequest($_POST['date']);

        requestCardsForTheDate($date, Auth::getUserId());
    }
}
?>
