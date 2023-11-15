document.addEventListener("DOMContentLoaded", function() {
  const calendarBody = document.getElementById("calendar-body");
  const prevMonthButton = document.getElementById("prev-month");
  const nextMonthButton = document.getElementById("next-month");
  const currentMonthYear = document.getElementById("current-month-year");

  let currentDate = new Date();
  renderCalendar(currentDate);

  prevMonthButton.addEventListener("click", function() {
      currentDate.setMonth(currentDate.getMonth() - 1);
      renderCalendar(currentDate);
  });

  nextMonthButton.addEventListener("click", function() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
  });
  
  function renderCalendar(date) {
      const year = date.getFullYear();
      const month = date.getMonth();
      const firstDay = new Date(year, month, 1);
      const lastDay = new Date(year, month + 1, 0);

      calendarBody.innerHTML = "";
      currentMonthYear.textContent = `${new Intl.DateTimeFormat('en-US', { month: 'long' }).format(date)} ${year}`;

      let day = new Date(firstDay);
      day.setDate(day.getDate() - day.getDay());

      while (day <= lastDay) {
          const tr = document.createElement("tr");
          for (let i = 0; i < 7; i++) {
              const td = document.createElement("td");
              td.textContent = day.getDate();
              if (day.getMonth() !== month) {
                  td.classList.add("inactive");
              }
              if (isToday(day)) {
                  td.classList.add("today");
              }
              td.addEventListener("click", function() {
                const clickedDate = new Date(day);
                const formattedDate = clickedDate.toISOString().slice(0, 10); // Format the date as YYYY-MM-DD
                window.location.href = `calendar.php`;
              });
              tr.appendChild(td);
              day.setDate(day.getDate() + 1);
          }
          calendarBody.appendChild(tr);
      }
  }

  function isToday(date) {
      const today = new Date();
      return date.getDate() === today.getDate() &&
             date.getMonth() === today.getMonth() &&
             date.getFullYear() === today.getFullYear();
  }
});
