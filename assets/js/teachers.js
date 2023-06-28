var teachersList = document.getElementById("teachers-list");
var teachersBTN = document.getElementById("teachers-btn");
var subjectsLoad = document.getElementById("subjects-load");
var subjectsBTN = document.getElementById("subjects-btn");

var shsTeachers = document.getElementById("shs-teachers");
var collegeTeachers = document.getElementById("college-teachers");

var shsSubjectLoad = document.getElementById("shs-subject-load");
var collegeSubjectLoad = document.getElementById("college-subject-load");

function teachers_list(){
    teachersList.style.background = "var(--mainContentBG)";
    teachersList.style.color = "black";
    teachersBTN.style.background = "var(--mainContentBG)";
    teachersBTN.style.color = "black";

    subjectsLoad.style.background = "none";
    subjectsLoad.style.color = "white";
    subjectsBTN.style.background = "none";
    subjectsBTN.style.color = "white";

    shsTeachers.style.display = "flex";
    collegeTeachers.style.display = "flex";

    shsSubjectLoad.style.display = "none"
    collegeSubjectLoad.style.display = "none"
}

function subject_load(){
    teachersList.style.background = "none";
    teachersList.style.color = "white";
    teachersBTN.style.background = "none";
    teachersBTN.style.color = "white";

    subjectsLoad.style.background = "var(--mainContentBG)";
    subjectsLoad.style.color = "black";
    subjectsBTN.style.background = "var(--mainContentBG)";
    subjectsBTN.style.color = "black";

    shsTeachers.style.display = "none";
    collegeTeachers.style.display = "none";

    shsSubjectLoad.style.display = "flex"
    collegeSubjectLoad.style.display = "flex"
}