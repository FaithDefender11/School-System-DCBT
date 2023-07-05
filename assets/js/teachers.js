var teachersList = document.getElementById("teachers-list");
var subjectsLoad = document.getElementById("subjects-load");

var shsTeachers = document.getElementById("shs-teachers");
var collegeTeachers = document.getElementById("college-teachers");

var shsSubjLoad = document.getElementById("shs-subject-load");
var collegeSubjectLoad = document.getElementById("college-subject-load");

//Add event listeners
document.getElementById("shs-btn").addEventListener("click", shs);
document.getElementById("college-btn").addEventListener("click", college);

var deptBTN = document.getElementById("btn");

function shs() {
  deptBTN.style.left = "0px";

  shsTeachers.style.display = "flex";
  collegeTeachers.style.display = "none";

  shsSubjLoad.style.display = "none";
  collegeSubjectLoad.style.display = "none";

  teachersList.addEventListener("click", shsTeachersList);
  subjectsLoad.addEventListener("click", shsSubjectsLoad);

  teachersList.removeEventListener("click", collegeTeachersList);
  subjectsLoad.removeEventListener("click", collegeSubjectsLoad);

  shsTeachersList();
}
//SHS Teachers list lsitener
function shsTeachersList(){
    teachersList.style.background = "var(--mainContentBG)";
    teachersList.style.color = "black";

    subjectsLoad.style.background = "none";
    subjectsLoad.style.color = "white";

    shsTeachers.style.display = "flex";
    collegeTeachers.style.display = "none";

    shsSubjLoad.style.display = "none";
    collegeSubjectLoad.style.display = "none";
}
// SHS Subjects listener
function shsSubjectsLoad(){
    teachersList.style.background = "none";
    teachersList.style.color = "white";

    subjectsLoad.style.background = "var(--mainContentBG)";
    subjectsLoad.style.color = "black";

    shsTeachers.style.display = "none";
    collegeTeachers.style.display = "none";

    shsSubjLoad.style.display = "flex";
    collegeSubjectLoad.style.display = "none";
}

function college() {
  deptBTN.style.left = "119.9px";

  shsTeachers.style.display = "none";
  collegeTeachers.style.display = "flex";

  shsSubjLoad.style.display = "none";
  collegeSubjectLoad.style.display = "none";

  teachersList.addEventListener("click", collegeTeachersList);
  subjectsLoad.addEventListener("click", collegeSubjectsLoad);

  teachersList.removeEventListener("click", shsTeachersList);
  subjectsLoad.removeEventListener("click", shsSubjectsLoad);

  collegeTeachersList();
}
// College Teachers listener
function collegeTeachersList(){
    teachersList.style.background = "var(--mainContentBG)";
    teachersList.style.color = "black";

    subjectsLoad.style.background = "none";
    subjectsLoad.style.color = "white";

    shsTeachers.style.display = "none";
    collegeTeachers.style.display = "flex";

    shsSubjLoad.style.display = "none";
    collegeSubjectLoad.style.display = "none";
}

// College Subjects listener
function collegeSubjectsLoad(){
    teachersList.style.background = "none";
    teachersList.style.color = "white";

    subjectsLoad.style.background = "var(--mainContentBG)";
    subjectsLoad.style.color = "black";

    shsTeachers.style.display = "none";
    collegeTeachers.style.display = "none";

    shsSubjLoad.style.display = "none";
    collegeSubjectLoad.style.display = "flex";
}

window.onload = shs;


function addTeacher(teacher) {
  if (teacher === 'shs') {
    window.location.href = "add-teacher-template.html";
  } else if (teacher === 'college') {
    window.location.href = "add-teacher-template.html";
  }
}


function view() {
  window.location.href = "view-teacher-template.html";
}