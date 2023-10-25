const currentMonth = new Date();
const daysContainer = document.querySelector('.days');
const currentMonthElement = document.getElementById('current-month');

function renderCalendar() {
  const year = currentMonth.getFullYear();
  const month = currentMonth.getMonth();

  currentMonthElement.textContent = new Date(year, month, 1).toLocaleString(
    'en-US',
    { month: 'long', year: 'numeric' }
  );

  daysContainer.innerHTML = '';

  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const today = new Date();

  for (
    let day = new Date(firstDay);
    day <= lastDay;
    day.setDate(day.getDate() + 1)
  ) {
    const dayElement = document.createElement('div');
    dayElement.textContent = day.getDate();
    daysContainer.appendChild(dayElement);

    if (day.toDateString() === today.toDateString()) {
      dayElement.classList.add('current-day');
    }
  }
}

function previousMonth() {
  currentMonth.setMonth(currentMonth.getMonth() - 1);
  renderCalendar();
}

function nextMonth() {
  currentMonth.setMonth(currentMonth.getMonth() + 1);
  renderCalendar();
}

renderCalendar();

const daysRow = document.querySelector('.days_headers');

function renderDays() {
  daysRow.innerHTML = '';
}
