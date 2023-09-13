var tab1 = document.getElementById("student-details");
var tab2 = document.getElementById("enrolled-subjects");

var shsDetails = document.getElementById("shs-information");
var shsEnrollDetails = document.getElementById("shs-enrollment-details");
var shsStrandSubjects = document.getElementById("shs-strand-subjects");
var shsAddedSubjects = document.getElementById("shs-added-subjects");
var shsApproveBtn = document.getElementById("shs-approve-btn");

function Tab1() {
    tab1.style.background = "var(--mainContentBG)";
    tab1.style.color = "black";
    tab2.style.background = "var(--theme)";
    tab2.style.color = "white";

    shsDetails.style.display = "flex";
    shsEnrollDetails.style.display = "none";
    shsStrandSubjects.style.display = "none";
    shsApproveBtn.style.display = "none";
    shsAddedSubjects.style.display = "none";
}

function Tab2() {
    tab1.style.background = "var(--theme)";
    tab1.style.color = "white";
    tab2.style.background = "var(--mainContentBG)";
    tab2.style.color = "black";

    shsDetails.style.display = "none";
    shsEnrollDetails.style.display = "flex";
    shsStrandSubjects.style.display = "flex";
    shsApproveBtn.style.display = "flex";
    shsAddedSubjects.style.display = "flex";
}

window.onload = Tab1;