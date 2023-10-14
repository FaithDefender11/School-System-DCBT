var sidebarBtn = document.querySelector(".sidebar");
var sidebarMenu = document.querySelector(".menu");

sidebarBtn.addEventListener("click", function () {
    sidebarMenu.classList.toggle("show");
})