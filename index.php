<?php
$todos = json_decode(file_get_contents('todos.json'), true) ?: [];
$completed = json_decode(file_get_contents('completed.json'), true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $createdAt = $_POST['createdAt'];
    $todo = [
        'content' => $content,
        'done' => false,
        'createdAt' => $createdAt
    ];
    $todos[] = $todo;
    file_put_contents('todos.json', json_encode($todos));
}

if (isset($_GET['complete'])) {
    $index = $_GET['complete'];
    if (isset($todos[$index])) {
        $completedTodo = $todos[$index];
        $completedTodo['completedAt'] = date('Y-m-d H:i:s');
        $completed[] = $completedTodo;
        unset($todos[$index]);
        file_put_contents('completed.json', json_encode($completed));
        file_put_contents('todos.json', json_encode($todos));
    }
}

if (isset($_GET['clearall'])) {
    $completed = [];
    file_put_contents('completed.json', json_encode($completed));
}

function DisplayTodos()
{
    $todos = json_decode(file_get_contents('todos.json'), true) ?: [];
    $todoList = '';
    $counter = 1;
    if (empty($todos)) {
        $todoList .= '<tr><td colspan="4" class="no-task">No Task Available</td></tr>';
    } else {
        foreach ($todos as $index => $todo) {
            $todoList .= '<tr class="todo-item">';
            $todoList .= '<td class="sno">' . $counter . '.</td>';
            $todoList .= '<td class="task">' . htmlspecialchars($todo['content']) . '</td>';
            $todoList .= '<td class="deadline">';
            $todoList .= 'Date: ' . date('d.m.Y', strtotime($todo['createdAt'])) . '<br>';
            $todoList .= 'Time: ' . date('H:i', strtotime($todo['createdAt']));
            $todoList .= '</td>';
            $todoList .= '<td class="status">';
            $todoList .= '<button class="complete-button" onclick="completeItem(' . $index . ')">Complete</button>';
            $todoList .= '</td>';
            $todoList .= '</tr>';
            $counter++;
        }
    }
    return $todoList;
}


function DisplayCompleted()
{
    $completed = json_decode(file_get_contents('completed.json'), true) ?: [];
    $completedList = '';
    $counter = 1;
    if (empty($completed)) {
        $completedList .= '<tr><td colspan="3" class="no-task">Not Yet Completed</td></tr>';
    } else {
        foreach ($completed as $todo) {
            $completedList .= '<tr class="completed-item">';
            $completedList .= '<td class="completed-serial">' . $counter . '.</td>';
            $completedList .= '<td class="completed-content">' . htmlspecialchars($todo['content']) . '</td>';
            $completedList .= '<td class="completed-at">';
            $completedList .= 'Date: ' . date('d.m.Y').'<br>';
            date_default_timezone_set('Asia/Kolkata');
            $completedList .= 'Time: ' . date('H:i');
            $completedList .= '</td>';
            $completedList .= '</tr>';
            $counter++;
        }
    }
    return $completedList;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="app">
        <section class="greeting">
            <h2 class="title">
                What's up, Balaji
            </h2>
        </section>
        <section class="create-todo">
            <h3>CREATE A TODO</h3>
            <form action="index.php" method="post" id="new-todo-form">
                <div class="task-input">
                    <input type="text" name="content" id="content" placeholder="e.g. finish homework" onchange="setName(this.value)" required>
                </div>
                <div class="datetime-input">
                    <input type="datetime-local" name="createdAt" id="createdAt" onchange="setDate(this.value)" required>
                </div>
                <div class="add-button">
                    <input type="submit" value="Add todo">
                </div>
            </form>
        </section>
        <!-- TODO List -->
        <section class="completed-list">
            <h3>TODO LIST</h3>
            <table class="list" id="todo-list">
                <tr class="list-header">
                    <th>S.no</th>
                    <th>Task</th>
                    <th>Deadline</th>
                    <th>Status</th>
                </tr>
                <?php echo DisplayTodos(); ?>
            </table>
        </section>
        <!-- Completed List -->
        <section class="completed-list">
            <div class="completed-heading">Completed Task<span class="clear-all-button" onclick="clearAll()">Clear All</span></div>
            <table class="list" id="completed-list">
                <tr class="list-header">
                    <th>S.no</th>
                    <th>Task</th>
                    <th>Completed Time</th>
                </tr>
                <?php echo DisplayCompleted(); ?>
            </table>
        </section>
        <!-- End of Todo List -->
    </main>
    <script>
        
        function completeItem(index) {
            window.location.href = 'index.php?complete=' + index;
            
        }

        function clearAll() {
            if (confirm('Are you sure you want to clear all completed tasks?')) {
                window.location.href = 'index.php?clearall';
            }
        }
        var data=""
        var name=""
        var new1=""
        var time1=new Date()
        var newt=""+time1
        console.log(newt)
        console.log(newt.slice(16,21))
        function setDate(value){
            date=value
            console.log(date.length);
            if(date!==''){
                new1=date.slice(11,16)
                console.log(new1)
            }
            
        }
        function setName(value){
            name=value
            console.log(name)
        }
    </script>
</body>

</html>
