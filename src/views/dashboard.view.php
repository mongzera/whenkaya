<?php

use Src\Middleware\Auth;

?>

<div class="container flex flex-row">
        <div class="sidebar px-1 border-right">
            <div class="profile flex align-center">
                <h3><?php echo Auth::getUserName() ?></h3>
            </div>
            <div class="schedules">
            <span class="flex flex-row justify-between align-center"><h3>Schedules</h3><button class='calendar-button' id='add-schedule-btn'>+</button></span>
                <hr>
                <div class="schedule-list flex flex-col" id='schedule-list'>
                </div>
            </div>
        </div>

        <div class="mainview flex flex-row">
            <div class="left-section border-right">
                <div class="calendar container-padding">
                    <h3>Calendar</h3>
                    <div class="calendar-view flex flex-col align-center justify-center">
                        <span style="align-items: center; justify-content: space-between; width: 100%;" class="flex flex-row "><button class="no-decor calendar-button" id="prev_month_btn"><</button><h3 id='month_name'></h3><button class="no-decor calendar-button" id="next_month_btn">></button></span>
                        <div class="calendar-container" id="calendar-container"></div>
                    </div>
                </div>
                <div class="daily-notes-view container-padding">
                    <h3>Daily Notes</h3>
                </div>
            </div>
            <div class="daily-scheduler container-padding">
                <h3>Day Schedule</h3>

                <!-- put a <h4> here, display the targeted date, year, month, day e.g 2024 November 14-->
            </div>
        </div>
    </div>
