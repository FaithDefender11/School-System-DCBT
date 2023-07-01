var shsRoomTab = document.getElementById("shs-room");
var shsSectionTab = document.getElementById("shs-section");
var collegeRmTab = document.getElementById("college-room");
var collegeSectTab = document.getElementById("college-section");

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

    shsRoomTab.addEventListener("click", shsRmTab);
    shsSectionTab.addEventListener("click", shsSectTab);

    collegeRmTab.removeEventListener("click", collegeRoomTab);
    collegeSectTab.removeEventListener("click", collegeSectionTab);

    shsRmTab();
}
//SHS Room listener
function shsRmTab(){
    shsRoomTab.style.background = "var(--theme)";
    shsRoomTab.style.color = "white";

    shsSectionTab.style.background = "var(--mainContentBG)";
    shsSectionTab.style.color = "black";

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
    shsRoomTab.style.background = "var(--mainContentBG)";
    shsRoomTab.style.color = "black";

    shsSectionTab.style.background = "var(--theme)";
    shsSectionTab.style.color = "white";

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

    collegeRmTab.addEventListener("click", collegeRoomTab);
    collegeSectTab.addEventListener("click", collegeSectionTab);

    shsRoomTab.removeEventListener("click", shsRmTab);
    shsSectionTab.removeEventListener("click", shsSectTab);

    collegeRoomTab();
}
//College Room listener
function collegeRoomTab(){
    collegeRmTab.style.background = "var(--theme)";
    collegeRmTab.style.color = "white";

    collegeSectTab.style.background = "var(--mainContentBG)";
    collegeSectTab.style.color = "black";

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
function collegeSectionTab(){
    collegeRmTab.style.background = "var(--mainContentBG)";
    collegeRmTab.style.color = "black";

    collegeSectTab.style.background = "var(--theme)";
    collegeSectTab.style.color = "white";

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


var GAS = document.getElementById("GAS");
var ICT = document.getElementById("ICT");
var HUMSS = document.getElementById("HUMSS");
var ABM = document.getElementById("ABM");
var IA = document.getElementById("IA");
var HE = document.getElementById("HE");