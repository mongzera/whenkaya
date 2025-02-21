"use strict";

(() => {
    
    let sidebarBtns = ['collection', 'calendar', 'planning', 'tasks'];

    let selectOnly = (id) => {
        sidebarBtns.forEach(e => {
            let btn = document.getElementById("sidebar-"+e);
            btn.setAttribute('selected', 'false');
        });

            

        let activeBtn = document.getElementById(e);
        activeBtn.setAttribute('selected','true');
    }

    sidebarBtns.forEach(e => {
        
        let btn = document.getElementById("sidebar-"+e);

        btn.onclick = () =>{
            selectOnly(btn.id);
        }
    });
    console.log("run");
});

