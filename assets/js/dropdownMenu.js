  document.addEventListener("DOMContentLoaded", function() {
    var dropdownToggles = document.querySelectorAll(".icon");
    var dropdownMenus = document.querySelectorAll(".dropdown-menu");

    for (var i = 0; i < dropdownToggles.length; i++) {
        var dropdownToggle = dropdownToggles[i];
        var dropdownMenu = dropdownMenus[i];

        dropdownToggle.addEventListener("click", createToggleHandler(dropdownMenu));
    }

    function createToggleHandler(menu) {
        return function() {
            menu.classList.toggle("show");
        };
    }

    document.addEventListener("click", function(event) {
        for (var i = 0; i < dropdownToggles.length; i++) {
            var dropdownToggle = dropdownToggles[i];
            var dropdownMenu = dropdownMenus[i];

            if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove("show");
            }
        }
    });
});