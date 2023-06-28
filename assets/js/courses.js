var deptBTN = document.getElementById("btn");

var shsStrand = document.getElementById("shs-strand");
var collegeCourses = document.getElementById("college-courses");

function shs() {
  deptBTN.style.left = "0px";

  shsStrand.style.display = "flex";
  collegeCourses.style.display = "none";
}

function college() {
  deptBTN.style.left = "119.9px";

  shsStrand.style.display = "none";
  collegeCourses.style.display = "flex";
}

window.onload = shs;
