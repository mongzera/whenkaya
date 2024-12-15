(() => {
    let updateScheduleList = () => {
        let schedList = document.getElementById('schedule-list');
        schedList.innerHTML = ''; //remove all childrens
        $.post("/fetchuserschedule", null, (response, status) => {
            if(response.status === 'success'){
                response.data.forEach(e => {
                    let schedItem = document.createElement('a');
                    schedItem.setAttribute("href", e[1]);
                    schedItem.setAttribute("class", "schedule-item");

                    let text = document.createElement('h5');
                    text.innerHTML = e[0];
                    schedItem.appendChild(text);

                    schedList.appendChild(schedItem);
                });
            }
        });
    }

    //add new schedule
    let newScheduleBtn = document.getElementById('add-schedule-btn');
    newScheduleBtn.onclick = () => {
        if(newScheduleBtn.hasAttribute('creating')) return;
        newScheduleBtn.toggleAttribute("creating");
        let schedList = document.getElementById('schedule-list');

        let schedNameInp = document.createElement('input'); //schedule name input
        schedNameInp.setAttribute("class", "new-schedule-inp");
        schedList.appendChild(schedNameInp);

        schedList.addEventListener("keypress", (evt) => {
            if(evt.key !== 'Enter') return;
            //xmlhttprequest
            let schedTitle = schedNameInp.value;
            //console.log(schedTitle);
            $.post("/addschedule", {
                'schedule_title' : schedTitle
            }, (data, status) => {
                alert("Data: " + data + "\nStatus: " + status);
                schedList.removeChild(schedNameInp);
                newScheduleBtn.removeAttribute('creating');
                updateScheduleList();
            });
        });
    }

    updateScheduleList();
})();