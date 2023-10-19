var sidebarBtn = document.querySelector(".sidebar");
var sidebarMenu = document.querySelector(".sidebar-nav");
var body = document.body;

sidebarBtn.addEventListener("click", function () {
    sidebarMenu.classList.toggle("show");

    // Disable scrolling when the sidebar is open
    if (sidebarMenu.classList.contains("show")) {
        body.style.overflow = "hidden";
    } else {
        body.style.overflow = "auto";
    }
});

document.addEventListener("click", function (event) {
    if (!sidebarMenu.contains(event.target) && !sidebarBtn.contains(event.target)) {
        sidebarMenu.classList.remove("show");
        body.style.overflow = "auto"; // Re-enable scrolling when the sidebar is closed
    }
});

sidebarMenu.addEventListener("click", function (event) {
    event.stopPropagation();
});
