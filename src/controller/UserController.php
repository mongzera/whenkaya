<?php

namespace Src\Controller;

use Src\Middleware\Auth;
use Src\Model\CalendarModel;
use Src\Model\CalendarUserModel;
use Src\Model\ScheduleModel;

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

    public function addSchedule(){
        if(!isPost()) return;

        $schedule_name =  cleanRequest($_POST['schedule_title']);
        $schedule_description = cleanRequest($_POST['schedule_description']);
        $schedule_start =  cleanRequest($_POST['schedule_start']);
        $schedule_end =  cleanRequest($_POST['schedule_end']);
        $schedule_type =  cleanRequest($_POST['schedule_type']);
        $color_hue =  cleanRequest($_POST['color_hue']);
        $calendar_id = cleanRequest($_POST['calendar_id']);
        
    
        //if any one of these is null
        if(
        !$schedule_name || 
        !$schedule_description ||
        !$schedule_start ||
        !$schedule_end ||
        !$schedule_type ||
        !$color_hue || 
        !$calendar_id) echo 'failed';

        $schedule = new ScheduleModel();

        $status = $schedule->insert([
            'schedule_title' => $schedule_name,
            'schedule_description' => $schedule_description,
            'schedule_start' => $schedule_start,
            'schedule_end' => $schedule_end,
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
    
    public function fetchUserCalendars(){
        header('Content-Type: application/json');

        $userCalendarAssoc = new CalendarUserModel();

        $response = array(
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => [
                'calendars' => $userCalendarAssoc->getAllFromRelatedModel('tb_calendar_model', 'calendar_id', 'user_id', Auth::getUserId(), $select = 'tb_calendar_model.id, calendar_name')
            ]
        );

        $out = json_encode($response);


        echo $out;
        exit();
    }



    public function requestUserSchedules(){
        if(!isPost()) return;

        $calendar_id = cleanRequest($_POST['calendar_id']);

        $calendarUserAssoc = new CalendarUserModel();
        $canView = false;
        $results = $calendarUserAssoc->getAllWithColumn('user_id', Auth::getUserId());

        foreach($results as $row){
            if($row['calendar_id'] == $calendar_id){
                $canView = ($row['privilage_level'] <= 1);
                break;
            }
        }

        if(!$canView) exit();

        $scheduleModel = new ScheduleModel();

        header('Content-Type: application/json');
        $response = array(
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => [
                'user_schedules' => $scheduleModel->getAllWithColumn('calendar_id', $calendar_id),
                'help' => 'test'
            ]
        );

        echo json_encode($response);
        exit();
    }
}
?>
