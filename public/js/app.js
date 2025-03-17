let _states = {
    current_calendar : {
        idx : 0,
        id : 0
    },
    current_date : {
        day : 0,
        month : 0,
        year : 1975
    }
};

(() => {

    
    let toggleNewScheduleModal = () => {
        //clear all input
        let schedule_title_inp = document.getElementById('schedule-title-inp');
        let schedule_desc_inp = document.getElementById('schedule-desc-inp');
        let schedule_starttime_inp = document.getElementById('schedule-starttime-inp');
        let schedule_endtime_inp = document.getElementById('schedule-endtime-inp');
        let maxchars = document.getElementById('max-chars');

        schedule_title_inp.value = "";
        schedule_desc_inp.value = "";
        schedule_starttime_inp.value = "";
        schedule_endtime_inp.value = "";
        maxchars.innerHTML = "";

        let modal = document.getElementById('new-schedule-modal');
        
        modal.setAttribute('toggle', (modal.getAttribute('toggle') == 'off') ? 'on' : 'off');

    }

    let updateCalendarList = () => {
        
        let calendar_list = document.getElementById('calendar-list');

        $.post("/fetchusercalendars", null, (response, status) => {

            if(status === 'success'){

                calendar_list.innerHTML = "";
                _states.user_calendars = response.data['calendars'];
                response.data['calendars'].forEach(e => {

                    let calendarItem = document.createElement('a');
                    calendarItem.setAttribute("href", "#");
                    calendarItem.setAttribute("class", "calendar-item");
                    calendarItem.setAttribute('calendar-idx', calendar_list.children.length);
                    calendarItem.setAttribute('selected', 'false');
                    calendarItem.onclick = () => {
                        calendar_list.childNodes[_states.current_calendar.idx].setAttribute('selected', false);
                        selectCalendar(calendarItem);
                        calendarItem.setAttribute('selected', 'true');
                        _states.updateDisplayDate()
                    }

                    calendarItem.innerHTML = e['calendar_name'];

                    calendar_list.appendChild(calendarItem);

                    if(calendar_list.children.length == 1) selectCalendar(calendarItem);
                });
            }
        });
    }

    let selectCalendar = (calendarItem) => {
        _states.current_calendar.id = parseInt(calendarItem.getAttribute('calendar-idx'));
        _states.current_calendar.idx = calendarItem.getAttribute('calendar-idx');
        _states.current_calendar.name = calendarItem.innerHTML;
    }

    //add new schedule
    let newScheduleBtn = document.getElementById('add-calendar-btn');
    newScheduleBtn.onclick = () => {

        if(newScheduleBtn.hasAttribute('creating')) return;
        newScheduleBtn.toggleAttribute("creating");

        let calendarList = document.getElementById('calendar-list');
        let calendar_name_inp = document.createElement('input'); //schedule name input
        calendar_name_inp.setAttribute('id', 'new-calendar-inp')
        calendar_name_inp.setAttribute("class", "new-calendar-inp");
        calendar_name_inp.setAttribute("type", "text");
        calendarList.appendChild(calendar_name_inp);
        let idx = calendarList.children.length - 1;
        calendar_name_inp.focus();
        
        
        calendar_name_inp.addEventListener('focusout', function handler()  {
            
            calendarList.removeChild(calendar_name_inp);
            newScheduleBtn.toggleAttribute("creating");
        });

        calendar_name_inp.addEventListener("keypress", (evt) => {
            if(evt.key !== 'Enter') return;

            $.post("/addcalendar", {
                'calendar_name' :  calendar_name_inp.value
            }, (data, status) => {
                //alert("Data: " + data + "\nStatus: " + status);
                
                updateCalendarList();
            });

           

        });
    }

    updateCalendarList();

    let new_schedule_button = document.getElementById('add-new-schedule');

    new_schedule_button.onclick = () => {
        toggleNewScheduleModal();
    }

    let new_schedule_exit = document.getElementById('new-schedule-exit');
    new_schedule_exit.onclick = () => {
        toggleNewScheduleModal();
        _states.updateDisplayDate();
    }


    let new_schedule_submit = document.getElementById('create-schedule');
    new_schedule_submit.onclick = () => {

        //clear all input
        let schedule_title_inp = document.getElementById('schedule-title-inp');
        let schedule_desc_inp = document.getElementById('schedule-desc-inp');
        let schedule_starttime_inp = document.getElementById('schedule-starttime-inp');
        let schedule_endtime_inp = document.getElementById('schedule-endtime-inp');
        let maxchars = document.getElementById('max-chars');
        let model_color_slider = document.getElementById('modal-color-slider');

        $.post("/add-schedule", {
            'schedule_title' : schedule_title_inp.value,
            'schedule_description' : schedule_desc_inp.value,
            'schedule_start' : schedule_starttime_inp.value,
            'schedule_end' : schedule_endtime_inp.value,
            'schedule_type' : 0,
            'color_hue' : model_color_slider.value,
            'calendar_id' : _states.user_calendars[_states.current_calendar.id]['id']
        }, (data, status) => {
            console.log("SCHEDULE UPLOAD STATUS: " + status);
            console.log("DATA: " + data);
        });

        toggleNewScheduleModal();
        _states.updateDisplayDate();
    }

    let model_color_slider = document.getElementById('modal-color-slider');
    model_color_slider.addEventListener('input', ()=>{
        let color_out = document.getElementById('color-out');
        color_out.style.backgroundColor = `hsl(${model_color_slider.value}, 55%, 70%)`;
    });

    //limit desc chars
    let schedule_desc_inp = document.getElementById('schedule-desc-inp');
    schedule_desc_inp.addEventListener('input', () => {
        console.log('test');
        let maxChars = 200;

        if (schedule_desc_inp.value.length >= maxChars) {
            schedule_desc_inp.value = schedule_desc_inp.value.substring(0, maxChars); // Prevent extra input
        }

        let maxchars = document.getElementById('max-chars');

        maxchars.innerHTML = `Character Limit: ${schedule_desc_inp.value.length} / ${maxChars}`;
    });


})();