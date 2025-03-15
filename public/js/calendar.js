(() => {

    let cardFactory = (timeStart, timeEnd, title, desc) => {
        let cardHTML = 
        `
                    <div class="card flex flex-row align-center">
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

    let cldnrCtn = document.getElementById('calendar-container');
    let canvas = document.createElement('canvas');
    canvas.setAttribute("width", "400px");
    canvas.setAttribute("height", "400px");
    canvas.setAttribute("id", "calendar");
    cldnrCtn.appendChild(canvas);
    let ctx = canvas.getContext('2d');
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
            }

            this.nextMonthBtn.onclick = () => {
                this.nextMonth();
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

        draw = () => {
            //console.log('update');

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

                ctx.fillStyle = "#000";
                let isDayWitinTargetMonth = true;
                if(dayOffset + i < 1 || dayOffset + i > monthDays) {
                    ctx.fillStyle = "#B8B8B8";
                    isDayWitinTargetMonth = false;
                }

                let pad = 15;
                let textX = x * daySpacing + 40 - textDim.width;
                let textY = y * daySpacing + 80;
                let textW = textDim.width;
                let textH = textDim.height;

                let midX = textX + textW*0.5;
                let midY = textY - textH*0.5;

                let outlinePad = 20;

                ctx.fillText(day.getDate(), textX , textY);

                if( this.isDayBeingHovered(midX - outlinePad, midY - outlinePad, 2 * outlinePad, 2 * outlinePad) ){
                    this._hoverData.currentX = midX;
                    this._hoverData.currentY = midY;
                    this._hoverData.isHoveredDayInTargetMonth = isDayWitinTargetMonth;
                    this._hoverData.dateInfo = day;
                    console.log("DAY IS HOVERED!");
                    // ctx.fillStyle = "#0f0";
                    // ctx.beginPath();
                    // ctx.ellipse(midX, midY, 5, 5, 0, 0, Math.PI*2, false);
                    // ctx.fill();

                    // ctx.strokeStyle = "#B8B8B8";
                    // ctx.lineWidth = 2;
                    // ctx.beginPath();
                    // ctx.roundRect(midX - outlinePad, midY - outlinePad, 2 * outlinePad, 2 * outlinePad, 5);
                    // ctx.stroke();
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

    let updateCurrentDateCards = (currentDate) => {

        $.post("/requestusercards", {
            'date' : {'day': currentDate.day, 'month': currentDate.month, 'year': currentDate.year}
        }, (data, status) => {
            //alert("Data: " + data + "\nStatus: " + status);
            
            //updateCalendarList();
        });

        let cardsContainer = document.getElementById('cards_container');

        //clear
        cardsContainer.innerHTML = "";

        for(let i = 0; i < 5; i++){
            cardsContainer.innerHTML += cardFactory("4:10 AM", "11:00 PM", "TEST TITLE", "TEST DESCRIPTION");
        }
    }

    let updateDisplayDate = (calendarState) => {
        let dashboard_date_display = document.getElementById('current_date');
        let current_date = calendarState._targetDate;
        dashboard_date_display.innerHTML = calendarState.months[current_date.month] + " " + current_date.day + ", " + current_date.year;

        updateCurrentDateCards(calendarState._targetDate);
    }

    let showAll = () => {
        let container = document.getElementById("container");

        container.style.display = "flex";
    }


    window.onload = () =>{
        let calendarState = new CalendarState();
        
        setInterval(() => {
            calendarState.draw()

        }, 1000 / 144); //apparently, u have to put it in anon-function for it to be called ¯\_(ツ)_/¯

        updateDisplayDate(calendarState);
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

            updateDisplayDate(calendarState);
        });

        //set calendarState.setTargetDate
    }
})();