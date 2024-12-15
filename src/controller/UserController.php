<?php

namespace Src\Controller;

class UserController extends BaseController{
    public function dashboard(){
        
        $content = [
            "title" => "hello",
            "head" => "../src/views/default_head.php",
            "body" => "../src/views/dashboard.view.php"
        ];

        $static = [
            "css" => ['css/global.css', 'css/theme.css'],
            "js"  => ['js/calendar.js', 'js/app.js']
        ];

        render_page($content, $static);
    }

    public function addSchedule(){
        // if(!Auth::user()) {
        //     echo 'failed';
        //     exit();
        // }
        echo 'success';
        //array_push(PublicController::$data, [$_POST['schedule_title'], '/uuid124123d12']);
    }

    public function fetchUserSchedules(){
        header('Content-Type: application/json');
        $response = array(
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => ''
        );

        echo json_encode($response);
        exit();
    }
}