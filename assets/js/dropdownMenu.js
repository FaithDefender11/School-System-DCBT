document.addEventListener("DOMContentLoaded", function() {
    var dropdownToggle = document.querySelector(".icon");
    var dropdownMenu = document.querySelector(".dropdown-menu");

    dropdownToggle.addEventListener("click", function() {
        dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", function(event) {
        if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove("show");
        }
    });
});