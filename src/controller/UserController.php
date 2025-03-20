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

<<<<<<< Updated upstream
=======
    public function addSchedule(){
        if(!isPost()) return;

        $schedule_name =  cleanRequest($_POST['schedule_title']);
        $schedule_description = cleanRequest($_POST['schedule_description']);
        $schedule_start =  cleanRequest($_POST['schedule_start']);
        $schedule_end =  cleanRequest($_POST['schedule_end']);
        $schedule_date = cleanRequest($_POST['schedule_date']);
        $schedule_type =  cleanRequest($_POST['schedule_type']);
        $color_hue =  cleanRequest($_POST['color_hue']);
        $calendar_id = cleanRequest($_POST['calendar_id']);
        
        
    
        //if any one of these is null
        if(
        !$schedule_name || 
        !$schedule_description ||
        !$schedule_start ||
        !$schedule_end ||
        !$schedule_date ||
        !$schedule_type ||
        !$color_hue || 
        !$calendar_id) echo 'failed';

        $schedule = new ScheduleModel();

        $status = $schedule->insert([
            'schedule_title' => $schedule_name,
            'schedule_description' => $schedule_description,
            'schedule_start' => $schedule_start,
            'schedule_end' => $schedule_end,
            'schedule_date' => $schedule_date,
            'schedule_type' => $schedule_type,
            'color_hue' => $color_hue,
            'calendar_id' => $calendar_id
        ]);

        var_dump($status);

        
        // $calendarUserAssoc = new CalendarUserModel();
        // $calendarUserAssoc->insert([
        //     'user_id' => Auth::getUserId(),
        //     'calendar_id' => $calendar->get('id'),
        //     'privilage_level' => 0
        // ]);
    }
    
>>>>>>> Stashed changes
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
