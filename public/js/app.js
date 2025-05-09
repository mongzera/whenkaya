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

    let calendarItemPopups = [];
    const modals = ['new-schedule-modal', 'new-notes-modal'];
    //toggling of schedule modal and notes modal
    window.onclick = (evt) => {
        toggleModals(evt);
        togglePopups(evt);
    }

    let toggleModals = (evt) => {
        console.log('running');
        const mouseX = evt.clientX;
        const mouseY = evt.clientY;

        

        modals.forEach(element => {
            const box = document.getElementById(element);
            const rect = box.getBoundingClientRect();

            const isInside =
                mouseX >= rect.left &&
                mouseX <= rect.right &&
                mouseY >= rect.top &&
                mouseY <= rect.bottom;

            if(!isInside){
                box.setAttribute('toggle', 'off');
            }
        });
    }

    let togglePopups = (evt) => {
        console.log('running');
        const mouseX = evt.clientX;
        const mouseY = evt.clientY;


        calendarItemPopups.forEach(element => {
            const box = document.getElementById(element);
            const rect = box.getBoundingClientRect();

            const isInside =
                mouseX >= rect.left &&
                mouseX <= rect.right &&
                mouseY >= rect.top &&
                mouseY <= rect.bottom;

            if(!isInside){
                box.setAttribute('hide', true);
            }
        });
    }
    
    let toggleNewScheduleModal = (evt) => {
        evt.stopPropagation();

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

        //close other modal
        if(modal.getAttribute('toggle') == 'on'){
            if(document.getElementById(modals[1]).getAttribute('toggle') == 'on') toggleNewNotesModal(evt);
        }


        _states.updateAll();
    }

    let toggleNewNotesModal = (evt) => {
        evt.stopPropagation();
        
        //clear all input
        let notes_title_inp = document.getElementById('notes-title-inp');
        let notes_desc_inp = document.getElementById('notes-desc-inp');

        notes_title_inp.value = "";
        notes_desc_inp.value = "";


        let modal = document.getElementById('new-notes-modal');
        
        modal.setAttribute('toggle', (modal.getAttribute('toggle') == 'off') ? 'on' : 'off');

        //close other modal
        if(modal.getAttribute('toggle') == 'on'){
            if(document.getElementById(modals[0]).getAttribute('toggle') == 'on') toggleNewScheduleModal(evt);
        }

        _states.updateAll();
    }



    let updateCalendarList = () => {
        
        let calendar_list = document.getElementById('calendar-list');

        $.post("/fetchusercalendars", null, (response, status) => {

            if(status === 'success'){

                calendar_list.innerHTML = "";
                _states.user_calendars = response.data['calendars'];
                _states.user_calendars = response.data['calendars'];
                response.data['calendars'].forEach(e => {

                    let calendarItem = document.createElement('div');
                    let chldIdx = calendar_list.children.length;
                    //calendarItem.setAttribute("href", "#");
                    calendarItem.setAttribute("class", "calendar-item flex flex-row justify-between align-center calendar-item-"+chldIdx);
                    calendarItem.setAttribute('calendar-name', e['calendar_name']);
                    calendarItem.setAttribute('calendar-idx', chldIdx);
                    calendarItem.setAttribute('selected', 'false');

                    let shareBtn = '<svg class="calendar-share-btn" hide="true" id="calendar-idx-' + chldIdx + '" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools --> <title>ic_fluent_share_24_filled</title> <desc>Created with Sketch.</desc> <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="ic_fluent_share_24_filled" fill="#212121" fill-rule="nonzero"> <path d="M6.746704,4 L10.2109085,4 C10.625122,4 10.9609085,4.33578644 10.9609085,4.75 C10.9609085,5.12969577 10.6787546,5.44349096 10.312679,5.49315338 L10.2109085,5.5 L6.746704,5.5 C5.55584001,5.5 4.58105908,6.42516159 4.50189481,7.59595119 L4.496704,7.75 L4.496704,17.25 C4.496704,18.440864 5.42186559,19.4156449 6.59265519,19.4948092 L6.746704,19.5 L16.247437,19.5 C17.438301,19.5 18.4130819,18.5748384 18.4922462,17.4040488 L18.497437,17.25 L18.497437,16.752219 C18.497437,16.3380054 18.8332234,16.002219 19.247437,16.002219 C19.6271328,16.002219 19.940928,16.2843728 19.9905904,16.6504484 L19.997437,16.752219 L19.997437,17.25 C19.997437,19.2542592 18.4250759,20.8912737 16.4465956,20.994802 L16.247437,21 L6.746704,21 C4.74244483,21 3.10543026,19.4276389 3.00190201,17.4491586 L2.996704,17.25 L2.996704,7.75 C2.996704,5.74574083 4.56906505,4.10872626 6.54754543,4.00519801 L6.746704,4 L10.2109085,4 L6.746704,4 Z M14.5006976,6.54430631 L14.5006976,3.75 C14.5006976,3.12602964 15.20748,2.7899466 15.6876724,3.13980165 L15.7698701,3.20874226 L21.7644714,8.95874226 C22.0442311,9.22708681 22.0696965,9.65811353 21.8408438,9.95607385 L21.7645584,10.0411742 L15.7699571,15.7930263 C15.3196822,16.2250675 14.5877784,15.9476738 14.5078455,15.3589039 L14.5006976,15.2518521 L14.5006976,12.4499835 L14.1799379,12.4437673 C11.5224061,12.4359053 9.2508447,13.5269198 7.31506917,15.745002 C6.81945864,16.3128907 5.88979801,15.876896 6.00952162,15.1327229 C6.83651469,9.99233371 9.60859008,7.08827771 14.1987622,6.57442791 L14.5006976,6.54430631 L14.5006976,3.75 L14.5006976,6.54430631 Z" id="🎨-Color"> </path> </g> </g> </g></svg>';
                    //console.log(e);
                    calendarItem.innerHTML = '<p>' + e['calendar_name'] + '</p>' + (e['privilage_level'] < 2 ? shareBtn : "");

                    calendarItem.onclick = () => {
                        let shareBtn = document.getElementById('calendar-idx-'+_states.current_calendar.idx);
                        //console.log("SHARE BTN: " + shareBtn);
                        if(shareBtn != null){
                            shareBtn.setAttribute('hide', 'true');
                            //console.log('true');
                        } 

                        let lastCalendarItem = document.querySelector(".calendar-item-"+_states.current_calendar.idx);

                        //if the pressed calendarItem was not the previous calendarItem..
                        if(calendarItem != lastCalendarItem )lastCalendarItem.querySelector('.share-calendar-popup')?.setAttribute('hide', true);
                        calendar_list.childNodes[_states.current_calendar.idx].setAttribute('selected', false);
                        selectCalendar(calendarItem);
                        //_states.updateAll();
                    }

                    calendar_list.appendChild(calendarItem);

                    
                });

                if(calendar_list.children.length > 0) selectCalendar(calendar_list.children[0]);
            }
        });
    }

    _states.updateCalendars = updateCalendarList;

    let selectCalendar = (calendarItem) => {
        _states.current_calendar.id = parseInt(calendarItem.getAttribute('calendar-idx'));
        _states.current_calendar.idx = calendarItem.getAttribute('calendar-idx');
        _states.current_calendar.name = calendarItem.getAttribute('calendar-name');
        _states.current_calendar.canEdit = _states.user_calendars[_states.current_calendar.id].privilage_level < 2;

        //clear schedule and notes
        document.getElementById('cards_container').innerHTML = "";
        document.getElementById('notes-container').innerHTML = "";

        _states.updateAll();

        let addSched = document.getElementById('add-new-schedule');
        addSched.style.display = "block";

            let addNote = document.getElementById('add-new-note');
        addNote.style.display = "block";

        if(!_states.current_calendar.canEdit){
            addSched.style.display = "none";
            addNote.style.display = "none";
        }

        calendarItem.setAttribute('selected', 'true');

        let shareBtn = document.getElementById('calendar-idx-'+_states.current_calendar.idx);
                        shareBtn?.setAttribute('hide', 'false');
                        shareBtn?.setAttribute('tabindex', "0");

        let hasPopupModal = calendarItem.querySelector('.share-calendar-popup') !== null;
        let popup = hasPopupModal ? calendarItem.querySelector('.share-calendar-popup') : document.createElement('div');
        
        if(!hasPopupModal) { 
            popup.style.transform = "translateX(200px)";
            popup.setAttribute('class', 'share-calendar-popup flex flex-row align-center justify-around');
            popup.setAttribute('id', 'popup-'+_states.current_calendar.idx);
            popup.setAttribute('hide', true);
            calendarItemPopups.push(popup.id);

            let copyLinkBtn = document.createElement('button');
            copyLinkBtn.setAttribute('class', 'form-submit');
            copyLinkBtn.innerText = "Copy Link";
            copyLinkBtn.onclick = (evt) => {
                let privilage_level = document.getElementById(`privilageLvl-${_states.current_calendar.idx}`).value;

                $.post('/createcalendarlink', {
                    'calendar_id' : _states.user_calendars[_states.current_calendar.id].id,
                    'privilage_level' : privilage_level
                }, (data, status) => {
                    console.log(data);
                    console.log(status);

                    navigator.clipboard.writeText(data)
                    .then(() => {
                        console.log('Text copied!');
                    })
                    .catch(err => {
                        console.error('Failed to copy: ', err);
                    });
                });

                // $.post("/add-schedule", {
                //     'schedule_title' : schedule_title_inp.value,
                //     'schedule_description' : schedule_desc_inp.value,
                //     'schedule_start' : schedule_starttime_inp.value,
                //     'schedule_end' : schedule_endtime_inp.value,
                //     'schedule_type' : 0,
                //     'schedule_date' : date + " " + time,
                //     'color_hue' : model_color_slider.value,
                //     'calendar_id' : _states.user_calendars[_states.current_calendar.id]['id']
                // }, (data, status) => {
                //     console.log("SCHEDULE UPLOAD STATUS: " + status);
                //     console.log("DATA: " + data);
                // });
            }

            let popupInner = `
                <div><label for='privilage_level'>Privilage Level:</label><select id='privilageLvl-${_states.current_calendar.idx}' name='privilage_level'>
                    <option value="1">Can Edit</option>
                    <option value="2">Can View Only</option>
                </select></div>
            `;

            popup.innerHTML = popupInner;

            popup.appendChild(copyLinkBtn);

            calendarItem.appendChild(popup); 
        }   


        // window.onclick = (evt) => {
        //     const mouseX = evt.clientX;
        //     const mouseY = evt.clientY;

        //     const box = popup;
        //     const rect = box.getBoundingClientRect();

        //     const isInside =
        //         mouseX >= rect.left &&
        //         mouseX <= rect.right &&
        //         mouseY >= rect.top &&
        //         mouseY <= rect.bottom;

        //     if(!isInside){
                
        //     }
        // }

        shareBtn?.addEventListener('click', (evt) => {
            popup.setAttribute('hide', false);
            evt.stopPropagation();
        });

       
          
    }

    //add new schedule
    let newCalendarBtn = document.getElementById('add-calendar-btn');
        newCalendarBtn.onclick = () => {
        if (newCalendarBtn.hasAttribute('creating')) return;
        newCalendarBtn.toggleAttribute("creating");
    
        let calendarList = document.getElementById('calendar-list');
        let calendar_name_inp = document.createElement('input'); // Calendar name input
        calendar_name_inp.setAttribute('id', 'new-calendar-inp');
        calendar_name_inp.setAttribute("class", "new-calendar-inp");
        calendar_name_inp.setAttribute("type", "text");
        calendarList.appendChild(calendar_name_inp);
        let idx = calendarList.children.length - 1;
        calendar_name_inp.focus();
    
        calendar_name_inp.addEventListener('focusout', function handler() {
            calendarList.removeChild(calendar_name_inp);
            newCalendarBtn.toggleAttribute("creating");
        });
    
        calendar_name_inp.addEventListener("keypress", (evt) => {
            if (evt.key !== 'Enter') return;
    
            // Validation: Check for empty value
            if (!calendar_name_inp.value.trim()) {
                alert("Calendar name cannot be empty. Please provide a valid name.");
                return;
            }
    
            // Validation: Check for special characters
            const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/g;
            if (specialCharPattern.test(calendar_name_inp.value)) {
                alert("Special characters are not allowed in the calendar name.");
                return;
            }
    
            $.post("/addcalendar", {
                'calendar_name': calendar_name_inp.value
            }, (data, status) => {
                updateCalendarList();
            });
        });
    };

    updateCalendarList();

    /////////////////////////////
    let new_schedule_button = document.getElementById('add-new-schedule');

    new_schedule_button.onclick = (evt) => {
        toggleNewScheduleModal(evt);
    };
        

    let new_schedule_exit = document.getElementById('new-schedule-exit');
    new_schedule_exit.onclick = (evt) => {
        toggleNewScheduleModal(evt);
    }

    let new_schedule_submit = document.getElementById('create-schedule');
        new_schedule_submit.onclick = (evt) => {
        // Clear all input
        let schedule_title_inp = document.getElementById('schedule-title-inp');
        let schedule_desc_inp = document.getElementById('schedule-desc-inp');
        let schedule_starttime_inp = document.getElementById('schedule-starttime-inp');
        let schedule_endtime_inp = document.getElementById('schedule-endtime-inp');
        let maxchars = document.getElementById('max-chars');
        let model_color_slider = document.getElementById('modal-color-slider');
    
        // Validation: Check for empty values
        if (!schedule_title_inp.value.trim() || !schedule_desc_inp.value.trim() || 
            !schedule_starttime_inp.value.trim() || !schedule_endtime_inp.value.trim()) {
            alert("All fields are required. Please fill out all fields.");
            return;
        }
    
        // Validation: Check for special characters
        const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/g;
        if (specialCharPattern.test(schedule_title_inp.value) || specialCharPattern.test(schedule_desc_inp.value)) {
            alert("Special characters are not allowed in the title or description.");
            return;
        }
    
        let date = `${_states.current_date.year}-${_states.current_date.month + 1}-${_states.current_date.day}`;
        let time = new Date().toTimeString().split(' ')[0];
    
        // Send the data to the server
        $.post("/add-schedule", {
            'schedule_title': schedule_title_inp.value,
            'schedule_description': schedule_desc_inp.value,
            'schedule_start': schedule_starttime_inp.value,
            'schedule_end': schedule_endtime_inp.value,
            'schedule_type': 0,
            'schedule_date': date + " " + time,
            'color_hue': model_color_slider.value,
            'calendar_id': _states.user_calendars[_states.current_calendar.id]['id']
        }, (data, status) => {
            console.log("SCHEDULE UPLOAD STATUS: " + status);
            console.log("DATA: " + data);
        });
    
        toggleNewScheduleModal(evt);
    };


    let new_notes_button = document.getElementById('add-new-note');
    new_notes_button.onclick = (evt) => {
        toggleNewNotesModal(evt);
    }
        
    let new_notes_exit = document.getElementById('new-notes-exit');
    new_notes_exit.onclick = (evt) => {
        toggleNewNotesModal(evt);
    }
        
    let new_notes_submit = document.getElementById('create-note');
        new_notes_submit.onclick = (evt) => {
        // Clear all input
        let notes_title_inp = document.getElementById('notes-title-inp');
        let notes_desc_inp = document.getElementById('notes-desc-inp');
        let modal = document.getElementById('new-notes-modal');
    
        // Validation: Check for empty values
        if (!notes_title_inp.value.trim() || !notes_desc_inp.value.trim()) {
            alert("All fields are required. Please fill out all fields.");
            return;
        }
    
        // Validation: Check for special characters
        const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/g;
        if (specialCharPattern.test(notes_title_inp.value) || specialCharPattern.test(notes_desc_inp.value)) {
            alert("Special characters are not allowed in the title or description.");
            return;
        }
    
        let date = `${_states.current_date.year}-${_states.current_date.month + 1}-${_states.current_date.day}`;
        let time = new Date().toTimeString().split(' ')[0];
    
        // Send the data to the server
        $.post("/add-note", {
            'note_title': notes_title_inp.value,
            'note_description': notes_desc_inp.value,
            'note_date': date + " " + time,
            'color_hue': model_color_slider.value,
            'calendar_id': _states.user_calendars[_states.current_calendar.id]['id']
        }, (data, status) => {
            console.log("NOTE UPLOAD STATUS: " + status);
            console.log("DATA: " + data);
        });
    
        toggleNewNotesModal(evt);
    };


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