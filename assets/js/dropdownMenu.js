var dropBtns = document.querySelectorAll(".icon");

dropBtns.forEach(btn => {
    btn.addEventListener("click", (e) => {
        const dropMenu = e.currentTarget.nextElementSibling;

        if (dropMenu.classList.contains("show")) {
            dropMenu.classList.toggle("show");
        } else {
            document.querySelectorAll(".dropdown-menu").forEach(item => item.classList.remove("show"));
            dropMenu.classList.add("show");
        }
    });
});