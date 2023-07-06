var shsMenu = document.getElementById("shs-menu");
var shsOptions = document.getElementById("shs-options");
var collegeMenu = document.getElementById("college-menu");
var collegeOptions = document.getElementById("college-options");

var deptBTN = document.getElementById("btn");

function shs() {
    deptBTN.style.left = "0px";

    shsMenu.style.display = "flex"
    shsOptions.style.display = "flex";

    collegeMenu.style.display = "none";
    collegeOptions.style.display = "none";
}

function college() {
    deptBTN.style.left = "119.9px";

    shsMenu.style.display = "none"
    shsOptions.style.display = "none";

    collegeMenu.style.display = "flex";
    collegeOptions.style.display = "flex";
}

window.onload = shs;