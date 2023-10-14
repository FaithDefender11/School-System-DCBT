var dropBtns = document.querySelectorAll(".icon");
var notifMenus = document.querySelectorAll(".notif-menu");

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

// Add a click event listener to the document to close menus when clicking outside
document.addEventListener("click", (e) => {
    notifMenus.forEach(menu => {
        if (menu.classList.contains("show") && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }
    });

    annMenus.forEach(menu => {
        if (menu.classList.contains("show") && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }
    });
});


var annBtn = document.getElementById("announce-btn");
var annMenus = document.querySelectorAll(".announce-menu");

annBtn.forEach(btn => {
    btn.addEventListener("click", (e) => {
        const annMenu = e.currentTarget.nextElementSibling;

        annMenu.classList.toggle("show");

        annMenus.forEach(menu => {
            if (menu !== annMenu && menu.classList.contains("show")) {
                menu.classList.remove("show");
            }
        });

        e.stopPropagation();
    });
});
