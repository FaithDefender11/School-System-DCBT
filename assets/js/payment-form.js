var detailsTab = document.getElementById("student-details");
var subjectsTab = document.getElementById("enrolled-subjects");

var studentInfo = document.getElementById("student-info");
var enrollmentDetails = document.getElementById("enrollment-details");
var subjItems = document.getElementById("subject-items");

var paidBtn = document.getElementById("confirm-btn");

function details_tab(){
    detailsTab.style.background = "var(--mainContentBG)";
    detailsTab.style.color = "black";

    subjectsTab.style.background = "var(--theme)";
    subjectsTab.style.color = "white";

    studentInfo.style.display = "flex";
    enrollmentDetails.style.display = "none";
    subjItems.style.display = "none";

    paidBtn.style.display = "none";
}

function subjects_tab(){
    detailsTab.style.background = "var(--theme)";
    detailsTab.style.color = "white";

    subjectsTab.style.background = "var(--mainContentBG)";
    subjectsTab.style.color = "black";

    studentInfo.style.display = "none";
    enrollmentDetails.style.display = "flex";
    subjItems.style.display = "flex";

    paidBtn.style.display = "flex";
}

window.onload = details_tab;