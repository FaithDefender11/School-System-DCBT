var infoTab = document.getElementById("teacher-details");
var scheduleTab = document.getElementById("subjects-load");

var teacherInfo = document.getElementById("teacher-information");
var teacherSched = document.getElementById("teacher-schedule");

function tab1() {
    infoTab.style.background = "var(--mainContentBG)";
    infoTab.style.color = "black";
    scheduleTab.style.background = "var(--theme)";
    scheduleTab.style.color = "white";

    teacherInfo.style.display = "flex";
    teacherSched.style.display = "none";
}

function tab2() {
    infoTab.style.background = "var(--theme)";
    infoTab.style.color = "white";
    scheduleTab.style.background = "var(--mainContentBG)";
    scheduleTab.style.color = "black";

    teacherInfo.style.display = "none";
    teacherSched.style.display = "flex";
}

window.onload = tab1;