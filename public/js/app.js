(() => {

    let updateCalendarList = () => {
        
        let calendar_list = document.getElementById('calendar-list');

        $.post("/fetchusercalendars", null, (response, status) => {

            if(status === 'success'){

                calendar_list.innerHTML = "";
                response.data['calendars'].forEach(e => {

                    let calenderItem = document.createElement('a');
                    calenderItem.setAttribute("href", "#");
                    calenderItem.setAttribute("class", "schedule-item");

                    let text = document.createElement('h5');
                    text.innerHTML = e['calendar_name'];
                    calenderItem.appendChild(text);

                    calendar_list.appendChild(calenderItem);
                });
            }
        });
    }

    //add new schedule
    let newScheduleBtn = document.getElementById('add-calendar-btn');
    newScheduleBtn.onclick = () => {
        if(newScheduleBtn.hasAttribute('creating')) return;
        newScheduleBtn.toggleAttribute("creating");
        let calendarList = document.getElementById('calendar-list');

        let calendar_name_inp = document.createElement('input'); //schedule name input
        calendar_name_inp.setAttribute("class", "new-schedule-inp");
        calendar_name_inp.setAttribute("type", "text");
        calendarList.appendChild(calendar_name_inp);
        calendar_name_inp.focus();

        calendarList.addEventListener("keypress", (evt) => {
            if(evt.key !== 'Enter') return;
            //xmlhttprequest
            let calendar_name = calendar_name_inp.value;
            newScheduleBtn.removeAttribute('creating');
            calendarList.removeChild(calendar_name_inp);
            //console.log(schedTitle);
            $.post("/addcalendar", {
                'calendar_name' : calendar_name
            }, (data, status) => {
                //alert("Data: " + data + "\nStatus: " + status);
                
                updateCalendarList();
            });

           

        });
    }

    updateCalendarList();


})();