(() => {
    let cldnrCtn = document.getElementById('calendar-container');
    let canvas = document.createElement('canvas');
    canvas.setAttribute("width", "400px");
    canvas.setAttribute("height", "400px");
    canvas.setAttribute("id", "calendar");
    cldnrCtn.appendChild(canvas);
    let ctx = canvas.getContext('2d');

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

    class CalendarState{

        constructor(){
            
        }

        days = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
        months = [
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

        _targetDate = {
            day : 1,
            month : 0,
            year : 2024
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

        draw = () => {
            console.log('update');
            
            let selectedDate = new Date(this._targetDate.year, this._targetDate.month, 1);
            let monthDays = getDaysInMonth(this._targetDate.year, this._targetDate.month);
            let dayOffset = -selectedDate.getDay() + 1;

            let w = canvas.width;
            let h = canvas.height;
            let daySpacing = w / this.days.length;

            
            ctx.clearRect(0, 0, w, h);

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
                if(dayOffset + i < 1 || dayOffset + i > monthDays) ctx.fillStyle = "#B8B8B8";

                ctx.fillText(day.getDate(), x * daySpacing + 40 - textDim.width, y * daySpacing + 80);
            }
        }

    }

    window.onload = () =>{
        let calendarState = new CalendarState();
        
        setInterval(() => {calendarState.draw()}, 100); //apparently, u have to put it in anon-function for it to be called ¯\_(ツ)_/¯
    }
})();