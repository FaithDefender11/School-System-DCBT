var deptBTN = document.getElementById("btn");

var shsSections = document.getElementById("shs-sections");
var collegeSections = document.getElementById("college-sections");

function shs() {
  deptBTN.style.left = "0px";

  shsSections.style.display = "flex";
  collegeSections.style.display = "none";
}

function college() {
  deptBTN.style.left = "119.9px";

  shsSections.style.display = "none";
  collegeSections.style.display = "flex";
}

window.onload = shs;

function view(page){
    if (page === 'page1'){
        window.location.href = "shs-view-section-template.html";
    } else if (page === 'page2'){
        window.location.href = "college-view-section-template.html";
    }
}