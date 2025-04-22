<?php
namespace Src\Controller;


use Src\Model\CalendarUserModel;
use Src\Middleware\Auth;
use Src\Model\CalendarModel;
use Src\Model\CalendarLinkShareModel;
use Src\Model\ReminderModel;
use Src\Model\ScheduleModel;
use Src\Model\UserModel;
use Src\Model\NoteModel;

class CalendarShareController extends BaseController{

    public function joinCalendar($link){
        if(!Auth::user()) redirect("login_account_get");

        //echo $link;
        
        $calendarShareModel = new CalendarLinkShareModel();

        $calendarShareRow = $calendarShareModel->getColumn('link', $link);

        if($calendarShareRow == null) {
            echo "Broken link. Link does not exist!";
            exit();
        }

        $calendarId = $calendarShareRow['calendar_id'];

        $calendarUserAssoc = new CalendarUserModel();

        $alreadyJoinedTheCalendar = false;

        foreach($calendarUserAssoc->getAllWithColumn('user_id', Auth::getUserId()) as $calendarAssoc){
            if($calendarAssoc['calendar_id'] == $calendarId){
                $alreadyJoinedTheCalendar = true;
                break;
            }
        }



        if(!$alreadyJoinedTheCalendar){
            $calendarUserAssoc->insert([
            
                'user_id' => Auth::getUserId(),
                'calendar_id' => $calendarId,
                'privilage_level' => $calendarShareRow['privilage_level']
                
            ]);

            addMessage('Succesfully joined the calendar!', 'success');
        }else{
            addMessage('You are already in the calendar!', $status = 'error');
            addMessage('You are already in the calendar!', $status = 'error');
            addMessage('You are already in the calendar!', $status = 'error');
            addMessage('You are already in the calendar!', $status = 'error');
        }

        redirect('dashboard');
    }

    public function createCalendarLink(){
        if(!Auth::user() || !isPost()) redirect("login_account_get");

    
        $userId = Auth::getUserId();

        

        $calendarUserAssoc = new CalendarUserModel();

        $result_set = $calendarUserAssoc->getAllWithColumn('user_id', $userId);

        $calendar_id = cleanRequest($_POST['calendar_id']);
        $privilage_level  = cleanRequest($_POST['privilage_level'] );

        //check if user has permission before creating a link
        $hasPermission = false;
        foreach($result_set as $row){
            if($row['calendar_id'] == $calendar_id && $row['user_id'] == $userId){
                $hasPermission = $row['privilage_level'] <= 1;
                break;
            }
        }

        if(!$hasPermission) {
            echo "Cannot share this calendar..."; //return if has no permission
            exit();
        }

        $calendarLinkShareModel = new CalendarLinkShareModel();

        $row = [
            'user_id' => $userId,
            'link' => generateUUID(),
            'calendar_id' => $calendar_id,
            'privilage_level' => $privilage_level,
            'duration' => 5
        ];

        
        $calendarLinkShareModel->insert($row);

        $lastInsertRow = $calendarLinkShareModel->lastInsertRow;

        echo '/joincalendar/' . $lastInsertRow['link'];
    }


}

?>