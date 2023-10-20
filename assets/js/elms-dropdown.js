var dropBtns = document.querySelectorAll(".icon");
var notifMenus = document.querySelectorAll(".notif-menu");
var actionBtn = document.querySelectorAll(".action");

dropBtns.forEach(btn => {
    btn.addEventListener("click", (e) => {
        const notifMenu = e.currentTarget.nextElementSibling;
        
        // Toggle the "show" class for the clicked menu
        notifMenu.classList.toggle("show");
        
        // Close other open menus
        notifMenus.forEach(menu => {
            if (menu !== notifMenu && menu.classList.contains("show")) {
                menu.classList.remove("show");
            }
        });
        
        // Prevent the click event from propagating to the document click event listener
        e.stopPropagation();
    });
});


var logoutBtns = document.querySelectorAll(".log-out-btn");
var logItems = document.querySelectorAll(".log-out-item");

logoutBtns.forEach(btn => {
    btn.addEventListener("click", (e) => {
        const logMenu = e.currentTarget.nextElementSibling;

        logMenu.classList.toggle("show");

        logItems.forEach(menu => {
            if (menu !== logMenu && menu.classList.contains("show")) {
                menu.classList.remove("show");
            }
        });

        e.stopPropagation();
    });
});


// Add a click event listener to the document to close menus when clicking outside
document.addEventListener("click", (e) => {
    notifMenus.forEach(menu => {
        if (menu.classList.contains("show") && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }
    });

    logItems.forEach(menu => {
        if (menu.classList.contains("show") && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }
    });
});