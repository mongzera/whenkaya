<?php

use Src\Middleware\Auth;

?>
<!-- new-schedule-modal-->
<div class="new-schedule-modal" id="new-schedule-modal" toggle='off'>
    <div class="top flex justify-between align-center">
        <h4 class='regular'>Add Schedule</h4>
        <button class='new-schedule-exit modal-exit' id='new-schedule-exit'>x</button>
    </div><br>
    <div class="content flex flex-col">
        <input id='schedule-title-inp' class='basic-inp schedule-title-inp' type="text" placeholder="Title"/>
        <textarea id='schedule-desc-inp' class='basic-inp schedule-desc-inp' type="text" placeholder="Description" rows='1'></textarea>
        <p style='font-size: 0.7em' id='max-chars'></p>
        <div class="time-set flex flex-row justify-between">
            <div class="start-time-container">
                <label>Start Time: </label>
                <input id='schedule-starttime-inp' type="time"/>
            </div>
            <div class="end-time-container">
                <label>End Time: </label>
                <input id='schedule-endtime-inp' type="time"/>
            </div>
        </div>
        <div class="footer flex flex-row justify-between align-center">
            <div class="color flex flex-col justify-center">
            <span><label>Card Color: </label><input id='modal-color-slider' type="range" min='0' max='360' value='0'/></span><div style='padding: 10px 5px;' id="color-out"></div>
            </div>
            <div class="submit-container">
                <input type="submit" value="Create" class="form-submit create-schedule" id='create-schedule'/>
            </div>
        </div>
    </div>
</div>

<!--ends here-->

<!--new notes modal-->


<div class="new-notes-modal" id="new-notes-modal" toggle='off'>
    <div class="top flex justify-between align-center">
        <h4 class='regular'>Add Notes</h4>
        <button class='new-notes-exit modal-exit' id='new-notes-exit'>x</button>
    </div><br>
    <div class="content flex flex-col">
        <input id='notes-title-inp' class='basic-inp notes-title-inp' type="text" placeholder="Note title"/>
        <textarea id='notes-desc-inp' class='basic-inp schedule-desc-inp' type="text" placeholder="Notes..." rows='1'></textarea>
        <p style='font-size: 0.7em' id='max-chars'></p>
        
        <div class="footer flex flex-row justify-between align-center">
            <input type="submit" value="Add Note" class="form-submit create-note" id='create-note'/>
        </div>
    </div>
</div>

<!--ends here-->

<div class="container flex flex-row " id='container'>
        <div class="sidebar px-1 border-right">
            <div class="profile flex align-center">
                <h3><?php echo Auth::getUserName() ?></h3>
            </div>
            <div class="calendars">
                <span class="flex flex-row justify-between align-center"><h3>Calendars</h3><button class='calendar-button' id='add-calendar-btn'>+</button></span>
                <div id="calendar-list" class="calendar-list flex flex-col"></div>
            </div>
        </div>

        <div class="mainview flex flex-row">
            <div class="calendar-note-section border-right">
                <div class="calendar container-padding">
                    <h3>Calendar</h3>
                    <div class="calendar-view flex flex-col align-center justify-center">
                        <span style="align-items: center; justify-content: space-between; width: 100%;" class="flex flex-row "><button class="no-decor calendar-button" id="prev_month_btn"><</button><h3 id='month_name'></h3><button class="no-decor calendar-button" id="next_month_btn">></button></span>
                        <div class="calendar-container" id="calendar-container"></div>
                    </div>
                </div>
                <div class="daily-notes-view container-padding flex flex-row justify-between">
                    <h3>Daily Notes</h3>
                    <button class="add-button" id="add-new-note">+</button>
                </div>
                <br>

                <div id='notes-container' class='notes-container flex flex-col justify-center align-center' style="text-align: center; margin-top: 4px;">
                    
                </div>
            

            </div>
            
            <div id='schedules-view' class="daily-scheduler container-padding flex flex-col">
                <p id='daily-scheduler-title' class='daily-scheduler-title'></p>
                <br>
                <div class="flex flex-row justify-between">
                    <p class='current-date' id="current_date"></p>
                    <button class="add-button" id="add-new-schedule">+</button>
                </div>
                <br>
                <hr style="border: none; border-bottom: 0.5px solid black">

                <div class='calendar-button-container flex justify-center'>
                    
                </div>

                <!-- card containers here -->

                <div id='cards_container' class="cards-container">
                    
                </div>

            
                <!-- ends here-->
                  
           
                
                <!-- put a <h4> here, display the targeted date, year, month, day e.g 2024 November 14-->
            </div>
        </div>
    </div>


