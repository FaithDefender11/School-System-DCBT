var deptBTN = document.getElementById("btn");

var shsMenu = document.getElementById("shs-menu");
var shsOptions = document.getElementById("shs-options");
var collegeMenu = document.getElementById("college-menu");
var collegeOptions = document.getElementById("college-options");

function shs() {
  deptBTN.style.left = "0px";

  shsMenu.style.display = "flex";
  shsOptions.style.display = "flex";
  collegeMenu.style.display = "none";
  collegeOptions.style.display = "none";
}

function college() {
  deptBTN.style.left = "119.9px";

  shsMenu.style.display = "none";
  shsOptions.style.display = "none";
  collegeMenu.style.display = "flex";
  collegeOptions.style.display = "flex";
}

window.onload = shs;

function view(page){
    if (page === "page1"){
        window.location.href = "shs-subject-item-template.html";
    } else if (page === "page2"){
        window.location.href = "college-subject-item-template.html";
    }
}