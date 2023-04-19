<?php 
include_once 'database.php'; 
$todo = allTodo();


    

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <title>Todo list</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

    <script>
    function modal(elementID, openedClosed) {
        var elementName = document.getElementById(elementID);
        if (openedClosed === "open") {
            elementName.style.display = "block";
        } else {
            elementName.style.display = "none";
        }
    }

    function sortTodo(todo, filter) {
        var todoItems = document.querySelectorAll("[data-todoListID='" + todo + "']")
        var sortItems = [];
        for (let val of todoItems) {
            var currentIdName = val.id;
            var currentTodoName = val.name;
            if (filter == "time") {
                var filterCheck = val.dataset.time;
            } else if (filter == "status") {
                var filterCheck = val.dataset.status;
                if (filterCheck === "inactive") {
                    filterCheck.style.display = "none";
                }
            } else {
                filterCheck = currentTodoName;
            }

            sortItems.push([currentIdName, filterCheck]);
        }
        sortItems.sort(function(a, b) {
            return a[1] - b[1];
        });

        for (let i = 0; i < sortItems.length; i++) {
            console.log(sortItems[i][0]);
            document.getElementById(sortItems[i][0]).style.order = i;
        }

    }
    </script>
</head>

<body>
    <div class="w3-container w3-center">
        <h2 class="title">Make your own todo list!</h2>
        <button class="w3-button w3-blue" onclick="modal('modalNewTodo', 'open')">Create a new todo list</button>
        <form method="get" action="#">
            <input type="submit" name="filterButton" value="filterButton" class="w3-button button w3-orange">
            <input type="submit" name="active" value="active" class="w3-button button w3-green">
            <input type="submit" name="inactive" value="inactive" class="w3-button button w3-red">
            <input type="submit" name="time-ascending" value="time-ascending" class="w3-button button w3-blue">
            <input type="submit" name="time-descending" value="time-descending" class="w3-button button w3-pink">
        </form>
    </div>
    <hr>
    <div class="w3-container">
        <!-- MODAL CREATOR -->

        <div id="modalNewTodo" class="w3-modal">
            <div class="w3-modal-content">
                <div class="w3-container">
                    <span onclick="modal('modalNewTodo','close')" class="w3-button w3-display-topmiddle">&times;</span>

                    <form action="#" method="post" class="w3-container">
                        <h3>Create a todo-list</h3>
                        <input type="text" placeholder="The name of the list..." class="w3-input w3-border"
                            name="todoName" pattern="[a-zA-Z0-9\s]+" required>

                        <br>
                        <input type="submit" name="makeTodoList" value="Make the Todo list" class="w3-button w3-blue">
                    </form>
                    <br>
                </div>
            </div>
        </div>

        <div class="listcard_flex">

            <?php foreach ($todo as $value):?>
            <div class="w3-card-4" style="display:inline-block; position:relative; height:100%">
                <header class="w3-container w3-light-grey">
                    <h3><?php echo $value['name'];?></h3>
                    <button class="w3-btn" value="time filter" name="timebtn"
                        onclick="sortTodo(<?php echo $value['id']; ?>, 'time')">
                        <i class="fa-fa-clock" aria-hidden="true"></i>klok
                    </button>
                    <button class="w3-btn" value="status filter" name="statusbtn"
                        onclick="sortTodo(<?php echo $value['id']; ?>, 'status')">
                        <i class="fa-fa-calendar-check-o" aria-hidden="true"></i>kalender
                    </button>


                </header>
                <div class="w3-container flex-container" id="todoContainer<?php echo $value['id'];?>">
                    <?php 
                    $task = allTaskOrderdByList($value["id"]);
            if(isset($_GET['filterButton'])){
                echo "AHOE";
                filterAscStatus($value["id"]);
            } 
            if(isset($_GET['inactive'])){
                echo "Pog";
                $task = filterDescStatus($value["id"]);
            }
            if(isset($_GET['active'])){
                echo "HELLO";
                $task = filterAscStatus($value["id"]);
            }
            if(isset($_GET['time-ascending'])){
                filterTimeAsc();
            }
            if(isset($_GET['time-descending'])){
                filterTimeDesc();
            }
            foreach($task as $values):?>
                    <div class="task" id="taskId<?php echo $values["id"]; ?>"
                        data-taskName="<?php echo $values['name'];?>" data-taskTime="<?php echo $values['time'];?>"
                        data-taskStatus="<?php echo $values['status']?>" data-todoListId="<?php echo $value["id"];?>">
                        <h3><?php echo $values["name"];?></h3>
                        <p><?php echo $values["description"];?></p>
                        <span class="w3-card w3-purple">Time: <?php echo $values["time"]; ?> min</span>
                        <span class="w3-tag w3-pink"><?php 
                if ($values['status'] === "active"){
                    echo "active";
                }else{
                    echo "inactive";
                }
                ?></span>
                        <span class="w3-button w3-orange"
                            onclick="modal('modalTask<?php echo $values['id']?>', 'open')">
                            Edit Task <i class="fa-solid fa-gear"></i>
                        </span>
                    </div>
                    <hr>

                    <div id="modalTask<?php echo $values['id'] ?>" class="w3-modal">
                        <div class="w3-modal-content">
                            <div class="w3-container">
                                <span onclick="modal('modalTask<?php echo $values['id']?>', 'close')"
                                    class="w3-button w3-display-topright">&times;</span>
                                <form action="#" method="post" class="w3-container">
                                    <h3>The task:</h3>
                                    <input type="hidden" name="taskId" value="<?php echo $values['id']?>">
                                    <br>
                                    <input type="text" name="taskName" placeholder="Name of the task"
                                        class="w3-input w3-border" pattern="[a-zA-Z0-9\s]+" required
                                        value="<?php echo $values['name']?>">
                                    <br>
                                    <input type="text" name="taskDescription" placeholder="Description of the task"
                                        class="w3-input w3-border" pattern="[a-zA-Z0-9\s]+" required
                                        value=<?php echo $values['description']?> class="w3-input w3-border">
                                    <br>
                                    <input type="number" name="taskTime"
                                        placeholder="Duration of the task (in minutes please)"
                                        class="w3-input w3-border" required value=<?php echo $values['time']?>>
                                    <br>
                                    <label for="status">Task Status</label>
                                    <input type="radio" name="taskStatus" value="active" <?php if($values['status'] === "active"){
                                echo 'Checked';
                            } ?>>
                                    <label>Active</label><br>
                                    <input type="radio" name="taskStatus" value="inactive" <?php if($values['status'] !== "active"){
                                echo 'Checked';
                            } ?>>
                                    <label>Inactive</label><br>
                                    <input type="submit" name="updateTask" value="Update Task" class="w3-btn w3-block">
                                    <input type="submit" name="deleteTask" value="Delete Task"
                                        class="w3-btn w3-red w3-block">
                                    <br>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>


                </div>
                <br>
                <div style="position: relative; bottom: 0;">
                    <button class="w3-button w3-block w3-purple"
                        onclick="modal('modalEditTodo<?php echo $value['id']?>', 'open')">Edit the Todo List</button>
                    <button class="w3-button w3-block w3-green"
                        onclick="modal('modalNewTask<?php echo $value['id']?>', 'open')">New task</button>
                </div>
                <div id="modalNewTask<?php echo $value['id']?>" class="w3-modal">
                    <div class="w3-modal-content">
                        <div class="w3-container">
                            <span onclick="modal('modalNewTask<?php echo $value['id']?>', 'close')" cols="25" rows="10"
                                class="w3-button w3-display-topright">&times;</span>

                            <form action="#" method="post" class="w3-container">
                                <h3>New task</h3>
                                <input type="hidden" name="todoListId" value="<?php echo $value['id']?>">
                                <br>
                                <input type="text" name="taskName" placeholder="Name of the task"
                                    class="w3-input w3-border" pattern="[a-zA-Z0-9\s]+" required>
                                <br>
                                <input type="text" name="taskDescription" style="resize: vertical;"
                                    class="w3-input w3-border" placeholder="Description of the task"
                                    pattern="[a-zA-Z0-9\s]+" required>
                                <br>
                                <input type="number" name="taskTime" class="w3-input w3-border"
                                    placeholder="Duration of the task (in minutes please)" class="w3-input w3-border"
                                    required>
                                <br>
                                <input type="submit" name="makeTask" value="Add task" class="w3-btn w3-block">
                                <br>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div id="modalEditTodo<?php echo $value['id']?>" class="w3-modal">
                <div class="w3-modal-content">
                    <div class="w3-container">
                        <span onclick="modal('modalEditTodo<?php echo $value['id']?>', 'close')" cols="25" rows="10"
                            class="w3-button w3-display-topright">&times;</span>
                        <form action="#" method="post" class="w3-container">
                            <h3>Edit Todo List</h3>
                            <input type="hidden" name="todoListId" value="<?php echo $value['id']?>">
                            <br>
                            <input type="text" name="todoName" placeholder="Name of the todo list"
                                class="w3-input w3-border" pattern="[a-zA-Z0-9\s]+" required
                                value="<?php echo $value['name']?>">
                            <input type="submit" name="updateTodo" value="Update Todo List" class="w3-btn w3-block">
                            <input type="submit" name="deleteTodo" value="Delete Todo List"
                                class="w3-btn w3-red w3-block">
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <script src="jquery.js"></script>
</body>

</html>