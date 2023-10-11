var deptBTN = document.getElementById("btn");

var shsSY = document.getElementById("shs-sy");
var collegeSY = document.getElementById("college-sy");

function shs() {
  deptBTN.style.left = "0";
  deptBTN.style.width = "114px"

  shsSY.style.display = "flex";
  collegeSY.style.display = "none";
}

function college() {
  deptBTN.style.left = "110px";
  deptBTN.style.width = "140px";

  shsSY.style.display = "none";
  collegeSY.style.display = "flex";
}

function shs_calendar() {
  window.location.href =
    "/DCBT-2/school-year/calendar-view/Calendar-view.html";
}