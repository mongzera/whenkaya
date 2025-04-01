(() => {

    let cardFactory = (timeStart, timeEnd, title, desc, idx) => {
        let cardHTML = 
        `
                    <div class="card flex flex-row align-center" id='calendar-card-${idx}'>
                        <div class="time flex flex-col">
                            <div class="start-time flex justify-center">
                                ${timeStart}
                            </div>
                           
                            <div class="end-time  flex justify-center">
                                ${timeEnd}
                            </div>
                        </div>

                        <div class="info flex flex-col">
                            <div class="title">
                                ${title}
                            </div>

                            <div class="desc">
                                ${desc}
                            </div>
                        </div>
                    </div>
        `;

        return cardHTML;
    }

    let noteFactory = (title, desc, idx) => {
        title = title.trim();
        desc = desc.trim();
        console.log(title);
        console.log(desc);
        let noteHTML = 
        `
                    <div class="note flex flex-row align-center" id='calendar-note-${idx}'>
                        <div class='handle flex align-center'>
                            <svg fill="#555555" width="48px" height="48px" viewBox="-87.04 -87.04 430.08 430.08" id="Flat" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="15.872"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="2.56"></g><g id="SVGRepo_iconCarrier"> <path d="M100,60.0001a8,8,0,1,1-8-8A8.00008,8.00008,0,0,1,100,60.0001Zm64,8a8,8,0,1,0-8-8A8.00008,8.00008,0,0,0,164,68.0001Zm-72,52a8,8,0,1,0,8,8A7.99977,7.99977,0,0,0,92,120.0001Zm72,0a8,8,0,1,0,8,8A7.99977,7.99977,0,0,0,164,120.0001Zm-72,68a8,8,0,1,0,8,8A7.99977,7.99977,0,0,0,92,188.0001Zm72,0a8,8,0,1,0,8,8A7.99977,7.99977,0,0,0,164,188.0001Z"></path> </g></svg>
                            </div>
                        <div class="info flex flex-col">
                            <div class="note-title flex align-end"><p class='p-title text-left'>${title}</p></div>
                            <div class="note-desc flex align-start"><p class='text-left'>${desc}</p></div>
                        </div>
                    </div>
        `;

        return noteHTML;
    }

    let cldnrCtn = document.getElementById('calendar-container');
    let canvas = document.createElement('canvas');
    canvas.setAttribute("width", "400px");
    canvas.setAttribute("height", "400px");
    canvas.setAttribute("id", "calendar");
    cldnrCtn.appendChild(canvas);
    let ctx = canvas.getContext('2d');
    ctx.imageSmoothingEnabled = true;
    let dt = 0;

    let measureText = (ctx, text) => {
        const width = ctx.measureText(text).width;
        const fontSizeMatch = ctx.font.match(/\d+(\.\d+)?px/); 
        const fontSize = fontSizeMatch ? parseFloat(fontSizeMatch[0]) : 16; 
        const height = fontSize;
        return { width, height };
    }

    let getDaysInMonth = (targetYear, targetMonth) => {
       return new Date(targetYear, targetMonth+1, 0).getDate();
    }

    let mousePosition = {
        x : 0,
        y : 0,
        wasInsideCalendar : false
    }

    class CalendarState{
        

        constructor(){
            this.days = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
            this.months = [
                'January',
                'Febuary',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];

            this.monthName = document.getElementById("month_name");
            this.nextMonthBtn = document.getElementById("next_month_btn");
            this.prevMonthBtn = document.getElementById("prev_month_btn");

            this.prevMonthBtn.onclick = () => {
                this.prevMonth();
                _states.updateAll();
            }

            this.nextMonthBtn.onclick = () => {
                this.nextMonth();
                _states.updateAll();
            }

            this.setTargetDate(new Date());

            this.shouldAnimateHoverBorder = false;
        }

        

        _targetDate = {
            day : 1,
            month : 0,
            year : 1975,
            dateObject : null
        };

        //implement lerping for this, make it smooooth
        _hoverData = {

            //listenToHover : true,
            outlinePad : 20,
            currentX : 0,
            currentY : 0,
            oldX : 0,
            oldY : 0,
            isHoveredDayInTargetMonth : false, //if it is false, if the user clicked this day, set the target month on it.
            dateInfo : null
        }

        setTargetDate(dateObject){

            if(dateObject == null) return;

            this._targetDate.day = dateObject.getDate();
            this._targetDate.month = dateObject.getMonth();
            this._targetDate.year = dateObject.getFullYear();
            this._targetDate.dateObject = dateObject;

            console.log(this._targetDate);

            //this._hoverData.listenToHover = false;

        }

        isDayBeingHovered = (textX, textY, textWidth, textHeight, withinTargetMonth) => {
            this._hoverData.isHoveredDayInTargetMonth = withinTargetMonth;
            return (mousePosition.x >= textX && mousePosition.x <= textX + textWidth) && (mousePosition.y >= textY && mousePosition.y <= textY + textHeight);
        };

        prevMonth = () => {
            this._targetDate.month--;
            if(this._targetDate.month < 0){
                this._targetDate.year--;
                this._targetDate.month = 11;
            } 

            //updateDate(calendarObj.targetYear, calendarObj.targetMonth);
        };

        nextMonth = () => {
            this._targetDate.month++;
            if(this._targetDate.month > 11){
                this._targetDate.year++;
                this._targetDate.month = 0;
            } 
            //updateDate(calendarObj.targetYear, calendarObj.targetMonth);
            //console.log(calendarObj);   
        };

        isTargetDate = (_targetDate, dateObj) => {
            //console.log(_targetDate);
            //console.log(dateObj.getFullYear());
            return dateObj.getDate() == _targetDate.day && dateObj.getMonth() == _targetDate.month && dateObj.getFullYear() == _targetDate.year;
        }

        _lerpAnimation = (ctx) => {

            if(!this.shouldAnimateHoverBorder) return;

            //animate date hovering
            let lerpFactor = 0.10;

            this._hoverData.oldX += (this._hoverData.currentX - this._hoverData.oldX) * lerpFactor;
            this._hoverData.oldY += (this._hoverData.currentY - this._hoverData.oldY) * lerpFactor;

            ctx.strokeStyle = "#B8B8B8";
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.roundRect(this._hoverData.oldX - this._hoverData.outlinePad, this._hoverData.oldY - this._hoverData.outlinePad, 2 * this._hoverData.outlinePad, 2 * this._hoverData.outlinePad, 5);
            ctx.stroke();
        
        }

        drawCardCircleColor = (midX, midY, outlinePad, hue) => {
            ctx.fillStyle = `hsl(${hue}, 55%, 70%)`;
            //ctx.fillStyle = "red";
            ctx.beginPath();
            ctx.arc(midX - outlinePad + 5, midY - outlinePad + 2 * outlinePad  - 5, 2.5, 0,  2 * Math.PI);
            ctx.fill();

        }   

        draw = () => {
            //console.log('update');]
            ctx.imageSmoothingEnabled = false;
            //ctx.imageSmoothingQuality

            this.shouldAnimateHoverBorder = mousePosition.wasInsideCalendar;
            
            let selectedDate = new Date(this._targetDate.year, this._targetDate.month, 1);
    
            let monthDays = getDaysInMonth(this._targetDate.year, this._targetDate.month);
            let dayOffset = -selectedDate.getDay() + 1;

            let w = canvas.width;
            let h = canvas.height;
            let daySpacing = w / this.days.length;

            //set month_name
            this.monthName.innerHTML = this.months[selectedDate.getMonth()] + " " + this._targetDate.year;
            
            ctx.clearRect(0, 0, w, h);

            this._lerpAnimation(ctx);

            ctx.font = "700 16px Inter"; //make it thiccc
            for(let i = 0; i < this.days.length; i++){
                ctx.fillStyle="#000";
                if(i == 0) ctx.fillStyle = "#FF0000";
                ctx.fillText(this.days[i], i * daySpacing + daySpacing*0.5 - 8, 30);
            }

            ctx.font = "400 16px Inter"; //set it back to thin
            for(let i = 0; i < 6 * 7; i++){
                
                let day = new Date(this._targetDate.year, this._targetDate.month, dayOffset + i);
                let textDim = measureText(ctx, day.getDate());
                let x = i % this.days.length;
                let y = Math.floor(i/this.days.length);

                let pad = 15;
                let textX = x * daySpacing + 40 - textDim.width;
                let textY = y * daySpacing + 80;
                let textW = textDim.width;
                let textH = textDim.height;

                let midX = textX + textW*0.5;
                let midY = textY - textH*0.5;
                let outlinePad = 20;

                //check for schedules on a day
                let schedExists = typeof _states.user_schedules?.[_states.user_calendars?.[_states.current_calendar?.id]?.id]?.[_states.current_date?.year]?.[_states.current_date?.month]?.[day.getDate()] !== 'undefined';

                //console.log(schedExists);
                
                ctx.fillStyle = "#000";
                let isDayWitinTargetMonth = true;
                if(dayOffset + i < 1 || dayOffset + i > monthDays) {
                    ctx.fillStyle = "#B8B8B8";
                    isDayWitinTargetMonth = false;
                }

                ctx.fillText(day.getDate(), textX , textY);

                if(schedExists && isDayWitinTargetMonth){
                    let schedules = _states.user_schedules[_states.user_calendars[_states.current_calendar.id].id][_states.current_date.year][_states.current_date.month][day.getDate()];

                    let schedCount = Object.keys(schedules).length;
                        

                    let circleCount = schedCount >= 5 ? 5 : schedCount;

                    for(let j = 0; j < circleCount; j++){
                        let schedColor = Object.values(schedules)[j]['color_hue'];
                        this.drawCardCircleColor(midX + j * 6, midY, outlinePad, schedColor);
                    }
                }

                //this.drawCardCircleColor(midX, midY, outlinePad, 234);

                

                

                if( this.isDayBeingHovered(midX - outlinePad, midY - outlinePad, 2 * outlinePad, 2 * outlinePad) ){
                    this._hoverData.currentX = midX;
                    this._hoverData.currentY = midY;
                    this._hoverData.isHoveredDayInTargetMonth = isDayWitinTargetMonth;
                    this._hoverData.dateInfo = day;
                    //console.log("DAY IS HOVERED!");

                }

                //if target date, draw a red outline
                if(this.isTargetDate(this._targetDate, day)){
                    if(!this.shouldAnimateHoverBorder){
                        this._hoverData.newX = midX;
                        this._hoverData.newY = midY;
                        this._hoverData.oldX = midX;
                        this._hoverData.oldY = midY;
                        
                    }

                    ctx.strokeStyle = "#FF0000";
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.roundRect(midX - outlinePad, midY - outlinePad, 2 * outlinePad, 2 * outlinePad, 5);
                    ctx.stroke();
                }
            }
            
        }

    }

    let calendarState = new CalendarState();

    let extractDate = (colname, card) => {
        return card[colname].split(' ')[0].split('-');
    }

    let extractNoteDate = (note) => {
        return extractDate('note_date', note);
    }

    let extractCardDate = (card) => {
        return extractDate('schedule_date', card);
    }

    //this is responsible for fetching and updating of schedules contained in the calendar
    let updateCurrentDateCards = (currentDate) => {
        let cardsContainer = document.getElementById('cards_container');
        //clear
        cardsContainer.innerHTML = "";

        if(_states.user_calendars === undefined || _states.user_calendars.length == 0) return;

        let checkDate = (currentDate, card) => {
            
            let date_arr = extractCardDate(card);
            let year = parseInt(date_arr[0]);
            let month = parseInt(date_arr[1]) - 1; //Javascript Date-Month is based 0 [0] = "January"
            let day = parseInt(date_arr[2]);

            return currentDate.year == year && currentDate.month == month && currentDate.day == day; 
        }

        let _statesUpdateUserSchedule = (card) => {
            let card_date = extractCardDate(card);
            let year = parseInt(card_date[0]);
            let month = parseInt(card_date[1]) - 1; //Javascript Date-Month is based 0 [0] = "January"
            let day = parseInt(card_date[2]);

            let id = card['calendar_id'];

            if(typeof _states.user_schedules == 'undefined'){
                _states.user_schedules = {};
            }

            if(typeof _states.user_schedules[id] == 'undefined'){
                _states.user_schedules[id] = {};
            }

            if(typeof _states.user_schedules[id][year] == 'undefined'){
                _states.user_schedules[id][year] = {};
            }

            if(typeof _states.user_schedules[id][year][month] == 'undefined'){
                _states.user_schedules[id][year][month] = {};
            }

            if(typeof _states.user_schedules[id][year][month][day] == 'undefined'){
                _states.user_schedules[id][year][month][day] = {};
            }

           
            _states.user_schedules[id][year][month][day][card.id] = card;
        }

        
        $.post("/requestuserschedules", {
            'calendar_id' : _states.user_calendars[_states.current_calendar.id]['id']
        }, (response, status) => {
            if(status !== 'success') return;

            console.log("REQUESTED CURRENT SCHEDULES");

            response.data["user_schedules"].forEach((element, i) => {

                _statesUpdateUserSchedule(element);
                
                if(element.calendar_id != _states.user_calendars[_states.current_calendar.id].id) return; //check date
                if(!checkDate(_states.current_date, element)) return;
                
                
                cardsContainer.innerHTML += cardFactory(element['schedule_start'], element['schedule_end'], element['schedule_title'], element['schedule_description'], i);
                let card = document.getElementById('calendar-card-' + i);
                card.style.backgroundColor = `hsl(${element['color_hue']}, 55%, 70%)`;
            });
        });

        
        // for(let i = 0; i < 5; i++){
        //     cardsContainer.innerHTML += cardFactory("4:10 AM", "11:00 PM", "TEST TITLE", "TEST DESCRIPTION");
        // }
    }

    let updateCurrentNoteCards = (currentDate) => {

        
        let notesContainer = document.getElementById('notes-container');
        //clear
        notesContainer.innerHTML = "";
        
        if(_states.user_calendars === undefined || _states.user_calendars.length == 0) return;

        let checkDate = (currentDate, card) => {
            
            let date_arr = extractNoteDate(card);
            let year = parseInt(date_arr[0]);
            let month = parseInt(date_arr[1]) - 1; //Javascript Date-Month is based 0 [0] = "January"
            let day = parseInt(date_arr[2]);

            return currentDate.year == year && currentDate.month == month && currentDate.day == day; 
        }

        let _statesUpdateUserNote = (note) => {
            let note_date = extractNoteDate(note);
            let year = parseInt(note_date[0]);
            let month = parseInt(note_date[1]) - 1; //Javascript Date-Month is based 0 [0] = "January"
            let day = parseInt(note_date[2]);

            let id = note['calendar_id'];

            if(typeof _states.user_notes == 'undefined'){
                _states.user_notes = {};
            }

            if(typeof _states.user_notes[id] == 'undefined'){
                _states.user_notes[id] = {};
            }

            if(typeof _states.user_notes[id][year] == 'undefined'){
                _states.user_notes[id][year] = {};
            }

            if(typeof _states.user_notes[id][year][month] == 'undefined'){
                _states.user_notes[id][year][month] = {};
            }

            if(typeof _states.user_notes[id][year][month][day] == 'undefined'){
                _states.user_notes[id][year][month][day] = {};
            }

           
            _states.user_notes[id][year][month][day][note.id] = note;
        }

        
        $.post("/requestusernotes", {
            'calendar_id' : _states.user_calendars[_states.current_calendar.id]['id']
        }, (response, status) => {

            if(status !== 'success') return;
            
            response.data["user_notes"].forEach((element, i) => {
                
                if(element.calendar_id != _states.user_calendars[_states.current_calendar.id].id) return; //check date
                if(!checkDate(_states.current_date, element)) return;

                _statesUpdateUserNote(element);
                notesContainer.innerHTML += noteFactory(element['note_title'], element['note_description'], i);
                
            });
        });

        
        // for(let i = 0; i < 5; i++){
        //     cardsContainer.innerHTML += cardFactory("4:10 AM", "11:00 PM", "TEST TITLE", "TEST DESCRIPTION");
        // }
    }

    
    let updateDisplayDate = () => {
        let dashboard_date_display = document.getElementById('current_date');
        let dashboard_daily_sched_title = document.getElementById('daily-scheduler-title');

        //update the global _states here...
        _states.current_date.day = calendarState._targetDate.day;
        _states.current_date.month = calendarState._targetDate.month;
        _states.current_date.year = calendarState._targetDate.year;
        let calendar_list = document.getElementById('calendar-list');

        if(_states.current_calendar.name === undefined && calendar_list.childNodes.length > 0){
            
            let calendarItem = calendar_list.childNodes[0];
            _states.current_calendar.idx = calendarItem.getAttribute('calendar-idx');
            _states.current_calendar.name = calendarItem.innerHTML;
        }

        dashboard_daily_sched_title.innerHTML = "Day schedule for " +  "<b>" + _states.current_calendar.name +  "</b>";
        dashboard_date_display.innerHTML = calendarState.months[_states.current_date.month] + " " + _states.current_date.day + ", " + _states.current_date.year;
        
        //show schedules view if there are calendars
        if(_states.user_calendars !== undefined && _states.user_calendars.length > 0) showSchedulesView();
        
        updateCurrentDateCards(_states.current_date);
        updateCurrentNoteCards(_states.current_date);
    }

    let showAll = () => {
        let container = document.getElementById("container");
        container.style.display = "flex";
    }

    let showSchedulesView = () => {
        let schedules_view = document.getElementById("schedules-view");
        schedules_view.style.display = "flex";
    }


    window.onload = () =>{
        
        
        setInterval(() => {
            calendarState.draw()
            
        }, 1000 / 144); //apparently, u have to put it in anon-function for it to be called ¯\_(ツ)_/¯

        _states.updateDisplayDate = updateDisplayDate;

        _states.updateAll = () => {
            _states.updateDisplayDate();
        }

        _states.updateAll();

        
        showAll();

        //mousemove listener
        canvas.addEventListener("mousemove", (evt) => {
            let canvasBoundingRect = canvas.getBoundingClientRect();
            mousePosition.x = evt.clientX - canvasBoundingRect.left;
            mousePosition.y = evt.clientY - canvasBoundingRect.top;

            let xInside = (mousePosition.x >= canvasBoundingRect.x && mousePosition.x <= canvasBoundingRect.x + canvasBoundingRect.width);
            let yInside = (mousePosition.y >= canvasBoundingRect.y && mousePosition.y <= canvasBoundingRect.y + canvasBoundingRect.height);

            if(xInside && yInside) mousePosition.wasInsideCalendar = true;
            
        });

        canvas.addEventListener("mousedown", (evt) => {
            console.log("pressed");
            calendarState.setTargetDate(calendarState._hoverData.dateInfo);
            _states.updateAll();
        });

        //set calendarState.setTargetDate
    }
})();