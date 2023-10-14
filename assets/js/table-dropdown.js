var tableDrops = document.querySelectorAll(".table-drop");
var tables = document.querySelectorAll(".b");
var iconUp = document.querySelectorAll(".bi-chevron-up");
var iconDown = document.querySelectorAll(".bi-chevron-down");

tableDrops.forEach((btn, index) => {
  btn.addEventListener("click", function () {
    tables[index].classList.toggle("show");
    iconDown[index].classList.toggle("show");

    if (tables[index].classList.contains("show")) {
        iconDown[index].classList.remove("show");
        iconDown[index].classList.toggle("none");
        iconUp[index].classList.toggle("show");
        iconUp[index].classList.remove("none");
    } else {
        iconDown[index].classList.remove("none");
        iconDown[index].classList.toggle("show");
        iconUp[index].classList.remove("show");
        iconUp[index].classList.toggle("none");
    }
  });
});
