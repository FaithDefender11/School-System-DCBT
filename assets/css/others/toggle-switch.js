var deptBTN = document.getElementById("btn");

var shsSY = document.getElementById("shs-sy");
var collegeSY = document.getElementById("college-sy");

function shs() {
  deptBTN.style.left = "0px";

  shsSY.style.display = "flex";
  collegeSY.style.display = "none";
}

function college() {
  deptBTN.style.left = "119.9px";

  shsSY.style.display = "none";
  collegeSY.style.display = "flex";
}

function shs_calendar() {
  window.location.href = "/DCBT-2/school-year/calendar-view/Calendar-view.html";
}
