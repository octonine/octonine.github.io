const monthYear = document.getElementById('month-year');
const calendarGrid = document.getElementById('calendar-grid');
let currentDate = new Date();
const API_KEY = 'nyk_v0_iQ0N7Cswb1sHmvtx49dmTeYcGB5SZP1gcXsYLXz1gSThPc4swa1wF5PcHt2vohLO';

// Getting month and year from API
async function fetchMonthYear() {
  try {
    const response = await fetch(
      'https://api.us.nylas.com/v3/grants/GRANT_ID/events?calendar_id=primary&limit=5',
      {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${API_KEY}`,
          Accept: 'application/json',
          'Content-Type': 'application/json',
        },
      }
    );
    const data = await response.json();

    // Mock data response: Update this based on your API response structure
    const month = currentDate.toLocaleString('default', { month: 'long' });
    const year = currentDate.getFullYear();
    return { month, year }; // Update this to match API data if different
  } catch (error) {
    console.error('Error fetching data:', error);
    return {
      month: currentDate.toLocaleString('default', { month: 'long' }),
      year: currentDate.getFullYear(),
    };
  }
}

// Fetch tasks from backend
async function fetchTasks() {
  try {
    const response = await fetch('tasks.php'); // Update with your actual endpoint
    const tasks = await response.json();
    return tasks;
  } catch (error) {
    console.error('Error fetching tasks:', error);
    return [];
  }
}

// Render the calendar with tasks
async function renderCalendar() {
  const { month, year } = await fetchMonthYear();

  // Update the calendar header
  monthYear.textContent = `${month} ${year}`;
  calendarGrid.innerHTML = ''; // Clear previous content

  // Calculate first day of the month and total days in the month
  const firstDayOfMonth = new Date(year, new Date(Date.parse(month + ' 1')).getMonth(), 1).getDay();
  const daysInMonth = new Date(year, new Date(Date.parse(month + ' 1')).getMonth() + 1, 0).getDate();

  // Fill in empty cells for days before the first of the month
  for (let i = 0; i < firstDayOfMonth; i++) {
    const emptyCell = document.createElement('div');
    emptyCell.classList.add('calendar-cell', 'empty');
    calendarGrid.appendChild(emptyCell);
  }

  // Create day cells
  for (let day = 1; day <= daysInMonth; day++) {
    const dayCell = document.createElement('div');
    dayCell.classList.add('calendar-cell');
    dayCell.dataset.day = day; // Add a dataset attribute for easier task matching
    dayCell.textContent = day;
    calendarGrid.appendChild(dayCell);
  }

  // Add tasks to the calendar
  await addTasksToCalendar();
}

//adding tasks to the calendar cells
async function addTasksToCalendar() {
  const tasks = await fetchTasks(); // getting tasks from php

  tasks.forEach((task) => {
    const dueDate = new Date(task.due_date); // convertind due_date to date object
    const day = dueDate.getDate(); // day
    const taskMonth = dueDate.toLocaleString('default', { month: 'long' });
    const taskYear = dueDate.getFullYear();

    // putting task into the relevant day cell
    const dayCell = [...document.querySelectorAll('.calendar-cell')].find(
      (cell) =>
        cell.dataset.day == day &&
        monthYear.textContent.includes(taskMonth) &&
        monthYear.textContent.includes(taskYear)
    );

    if (dayCell) {
      const taskElement = document.createElement('div');
      taskElement.classList.add('task');
      taskElement.textContent = task.title; 

      // check whether the task is completed or not
      if (parseInt(task.is_completed) === 1) { 
        taskElement.classList.add('completed'); // line through completed task(s)
      }

      dayCell.appendChild(taskElement);
    }
  });
}

// Add event listener to each task element
document.addEventListener('click', (event) => {
  if (event.target.classList.contains('task')) {
      const modal = document.getElementById('task-modal');
      const modalTitle = modal.querySelector('.modal-title');
      const modalDescription = modal.querySelector('.modal-description');

      const taskTitle = event.target.textContent;
      const task = tasks.find((t) => t.title === taskTitle);

      if (task) {
          modalTitle.textContent = task.title;
          modalDescription.textContent = task.description;
          modal.classList.add('show');
      }
  }
});

// Close the modal when clicking the close button
const closeButton = document.querySelector('.modal-close');
closeButton.addEventListener('click', () => {
  const modal = document.getElementById('task-modal');
  modal.classList.remove('show');
});
// Navigate to the previous month
function prevMonth() {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar();
}

// Navigate to the next month
function nextMonth() {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar();
}

// Initialize the calendar
renderCalendar();