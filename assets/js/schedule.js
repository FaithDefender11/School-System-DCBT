var RmTab = document.getElementById("room");
var SectTab = document.getElementById("section");

var shsScheduler = document.getElementById("shs-scheduler");
var shsOverview = document.getElementById("shs-schedule-overview");
var shsRmTable = document.getElementById("shs-room-table");
var shsSectTable = document.getElementById("shs-section-table");
var shsRmHeader = document.getElementById("shs-room-tab");
var shsSectHeader = document.getElementById("shs-section-tab");

var collegeScheduler = document.getElementById("college-scheduler");
var collegeOverview = document.getElementById("college-schedule-overview");
var collegeRmTable = document.getElementById("college-room-table");
var collegeSectTable = document.getElementById("college-section-table");
var collegeRmHeader = document.getElementById("college-room-tab");
var collegeSectHeader = document.getElementById("college-section-tab");

var deptBTN = document.getElementById("btn");

//Add event listeners
document.getElementById("shs-btn").addEventListener("click", shs);
document.getElementById("college-btn").addEventListener("click", college);

function shs() {
    deptBTN.style.left = "0px";

    shsScheduler.style.display = "flex";
    shsOverview.style.display = "flex";

    collegeScheduler.style.display = "none";
    collegeOverview.style.display = "none";

    RmTab.addEventListener("click", shsRmTab);
    SectTab.addEventListener("click", shsSectTab);

    RmTab.removeEventListener("click", collegeRmTab);
    SectTab.removeEventListener("click", collegeSectTab);

    shsRmTab();
}
//SHS Room listener
function shsRmTab(){
    RmTab.style.background = "var(--theme)";
    RmTab.style.color = "white";

    SectTab.style.background = "var(--mainContentBG)";
    SectTab.style.color = "black";

    shsRmHeader.style.display = "flex";
    collegeRmHeader.style.display = "none";

    shsSectHeader.style.display = "none";
    collegeSectHeader.style.display = "none";

    shsRmTable.style.display = "flex";
    shsSectTable.style.display = "none";

    collegeRmTable.style.display = "none";
    collegeSectTable.style.display = "none";
}
//SHS Section listener
function shsSectTab(){
    RmTab.style.background = "var(--mainContentBG)";
    RmTab.style.color = "black";

    SectTab.style.background = "var(--theme)";
    SectTab.style.color = "white";

    shsRmHeader.style.display = "none";
    collegeRmHeader.style.display = "none";

    shsSectHeader.style.display = "flex";
    collegeSectHeader.style.display = "none";

    shsRmTable.style.display = "none";
    shsSectTable.style.display = "flex";

    collegeRmTable.style.display = "none";
    collegeSectTable.style.display = "none";
}

function college() {
    deptBTN.style.left = "119.9px";

    shsScheduler.style.display = "none";
    shsOverview.style.display = "none";

    collegeScheduler.style.display = "flex";
    collegeOverview.style.display = "flex";

    RmTab.addEventListener("click", collegeRmTab);
    SectTab.addEventListener("click", collegeSectTab);

    RmTab.removeEventListener("click", shsRmTab);
    SectTab.removeEventListener("click", shsSectTab);

    collegeRmTab();
}
//College Room listener
function collegeRmTab(){
    RmTab.style.background = "var(--theme)";
    RmTab.style.color = "white";

    SectTab.style.background = "var(--mainContentBG)";
    SectTab.style.color = "black";

    shsRmHeader.style.display = "none";
    collegeRmHeader.style.display = "flex";

    shsSectHeader.style.display = "none";
    collegeSectHeader.style.display = "none";

    shsRmTable.style.display = "none";
    shsSectTable.style.display = "none";

    collegeRmTable.style.display = "flex";
    collegeSectTable.style.display = "none";
}
//College Section listener
function collegeSectTab(){
    RmTab.style.background = "var(--mainContentBG)";
    RmTab.style.color = "black";

    SectTab.style.background = "var(--theme)";
    SectTab.style.color = "white";

    shsRmHeader.style.display = "none";
    collegeRmHeader.style.display = "none";

    shsSectHeader.style.display = "none";
    collegeSectHeader.style.display = "flex";

    shsRmTable.style.display = "none";
    shsSectTable.style.display = "none";

    collegeRmTable.style.display = "none";
    collegeSectTable.style.display = "flex";
}

window.onload = shs;