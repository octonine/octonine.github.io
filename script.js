
    //functions for tasks
document.addEventListener('DOMContentLoaded', function () {
    //Arrows functionalities
    document.querySelector('.left-arrow').addEventListener('click', function() {
        document.querySelector('.task-container').scrollLeft -= 200; // slide to left
    });

    document.querySelector('.right-arrow').addEventListener('click', function() {
        document.querySelector('.task-container').scrollLeft += 200; // slide to right
    });

    const taskCards = document.querySelector('.task-cards');
    const addTaskBtn = document.querySelector('.sidebar ul li:first-child a');
    const defaultMessage = document.querySelector('.default-message');

    // Function to add a new task and open the form immediately
    function addTask() {
        defaultMessage.style.display = 'none';

        const taskCard = document.createElement('div');
        taskCard.classList.add('task-card');

        // Open the task form immediately upon adding a new task
        openTaskForm(taskCard);
        taskCards.appendChild(taskCard);
    }

    // Function to create and open the task form (used for both adding and editing)
    function openTaskForm(taskCard) {
        // Create form elements
        const form = document.createElement('form');
        form.setAttribute('action', 'add_task_page.php'); //deneme
        form.setAttribute('method', 'post') //deneme
        form.innerHTML = `
                <input type="text" name="task-title" class="task-title" placeholder="Enter task title"><br>
                <textarea name="task-description" class="task-description" placeholder="Enter task description"></textarea><br>
                <input type="date" name="due-date" class="task-due-date"><br>
                <button name="submit" class="btn-submit" type="submit">Save Task</button>
        `;

        // Replace the task card content with the form
        taskCard.innerHTML = '';
        taskCard.appendChild(form);

        // form submission
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Get values from form fields
            const title = form.querySelector('.task-title').value;
            const description = form.querySelector('.task-description').value;
            const dueDateInput = form.querySelector('.task-due-date').value;


            // Update task card with new values
            taskCard.innerHTML = 
                `
                <div class="icon-bell"></div>
                <p><b>${title}</b></p>
                <div class="task-description">${description}</div>
                <div>Due: <b>${dueDateInput}</b></div>
                <div class="actions">
                    <a href="#" class="check">✔</a>
                    <span class="delete">🗑</span>
                </div>
            `;

            setTaskColorDueDate(dueDateInput, taskCard);
        });
    }

    // Attach addTask function to the Add Task button
    addTaskBtn.addEventListener('click', addTask);

    //when you click on 'tik' the background gets green 
    const checkIcons = document.querySelectorAll(".check");
 
    checkIcons.forEach(icon => {
        icon.addEventListener("click", function (event) {
            event.preventDefault(); // prevent the usual behaviour of 'link' 
            const taskId = this.closest(".task-card").dataset.id; 

            // sending AJAX request 
            fetch('is_completed.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ task_id: taskId }) // send the task_id 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    //update the design after it is completed successfully
                    const taskCard = document.querySelector(`.task-card[data-id='${taskId}']`);
                    if (taskCard) {
                        taskCard.classList.add('completed'); // adding "completed" CSS class 
                        taskCard.parentNode.appendChild(taskCard); // move the task at the end 
                    }
                } else {
                    alert(data.message || "An error occured.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });
}); 


