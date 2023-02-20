<?php 
function connAll(){ 
	try{
	$conn = new PDO('mysql:host=localhost;dbname=todolist2','root', 'mysql');
	return $conn;
}
	catch(PDOException $e){
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
	}
}  
function clean($data){
    $data = preg_replace('@[^A-Za-z0-9\w\ ]@', '', $data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $data = trim($data);
    
    return $data;
}
function allTodo(){
	$conn = connAll();
	$query = "SELECT * FROM todo ORDER BY id";
	$conn = connAll();
	$result = $conn->prepare($query);
	$result->execute();
	$rows = $result->fetchAll();
	return $rows;
}

function allTask(){
	$conn = connAll();
	$query = "SELECT * FROM task ORDER BY id";
	$conn = connAll();
	$result = $conn->prepare($query);
	$result->execute();
	$rows = $result->fetchAll();
	return $rows;
}
function allTaskOrderdByList($listId){
	$conn = connAll();
	$query = "SELECT * FROM task WHERE listId = :listId";
	$result = $conn->prepare($query);
	$result->execute([":listId" => $listId]);
	$rows = $result->fetchAll();
	return $rows;
}
function addTodo($name){
	$conn = connAll();
	$query = $conn->prepare("INSERT INTO todo (id, name) VALUES (NULL, :name)");
	$query -> execute(array(
		':name' => $name));
}
function addTask($name, $description,$time,$listId){
	
	$conn = connAll();
	$query = $conn->prepare("INSERT INTO task (id, name, description, time, status, listId) VALUES (NULL, :name, :description, :time, 'active', :listId)");
	$query -> execute([
	':name' => $name,
	':description' => $description,
	':time' => $time,
	':listId' => $listId
]);
}
function updateTask($id,$name,$description,$time,$status){
	$conn = connAll();
	$query = $conn->prepare("UPDATE task SET name=:name, description=:description, time=:time, status=:status WHERE id=:id");
	$query->execute([":name" => $name, ":description" => $description, ":time" => $time, ":status" => $status, ":id" => $id]);

	
}
function deleteTask($id){
	$conn = connAll();
	$query = $conn->prepare("DELETE FROM task WHERE id = ?");
	$query->execute([$id]);
}
function getTodo($id){
	$conn = connAll();
	$query = $conn->prepare("SELECT * FROM todo WHERE id=:id");
	$query->bindParam(":id", $id);
	$query->execute();
	$singleTodo = $query->fetch();
	return $singleTodo;
}
function getTask($id){
	$conn = connAll();
	$query = $conn->prepare("SELECT * FROM task WHERE id=:id");
	$query->bindParam(":id", $id);
	$query->execute();
	$singleTask = $query->fetch();
	return $singleTask;
}
function updateTodo($id,$name){
	$conn = connAll();
	$query = $conn->prepare("UPDATE todo SET name=:name WHERE id=:id");
	$query->execute([':name' => $name, ':id'=>$id]);
}
function deleteTodo($id){
	$conn = connAll();
	$query = $conn->prepare("DELETE FROM todo WHERE id = ?");
	$query->execute([$id]);
}
function filterAscStatus($status){
	switch($status){
		case "inactive":
	$conn = connAll();
	$query = $conn->prepare("SELECT * FROM task WHERE status = :status ORDER BY status ASC");
	$query->execute([":status" => $status]);
	$result = $query->fetchAll();
	echo "Nani";
	return $result;
	break;
	default:
		echo "function is done";
		break;
	}
}
function filterDescStatus($status){
	switch($status){
		case "active":
	$conn = connAll();
	$query = $conn->prepare("SELECT * FROM task WHERE status = :status ORDER BY status DESC");
	$query->execute([":status" => $status]);
	$result = $query->fetchAll();
	echo "PEEPEE";
	return $result;
	break;
	default:
		echo "function is executed";
		break;
	}
}
function filterStatus($status){
	switch($status){
	case "inactive":
	case "active":
	$conn = connAll();
	$query = $conn->prepare("SELECT * FROM task ORDER BY FIELD(status, '$status'");
	$query->execute([":status" => $status]);
	$result = $query->fetchAll();
	echo "WORLD";
	return $result;
	break;
	default:
		echo "function is proved that it works";
		break;

	}
}
/*
function filterTime(){
	$conn = connAll();
	$query = $conn->prepare("SELECT * FROM task ORDER BY time DESC");
	$query->execute();
	$result = $query->fetchAll();
	return $result;
}*/

//clean functions
$taskId = clean($_POST['taskId']);
$todoListId = clean($_POST['todoListId']);
$taskTime = clean($_POST['taskTime']);
$taskStatus = clean($_POST['taskStatus']);
$taskDescription = clean($_POST['taskDescription']);
$todoName = clean($_POST['todoName']);
$taskName = clean($_POST['taskName']);



//post if statements
if (isset($_POST['makeTodoList'])){
	addTodo($todoName);
}
if(isset($_POST['makeTask'])){
	
	addTask($taskName,$taskDescription, $taskTime, $todoListId);
}
if(isset($_POST['updateTask'])){
	
	updateTask($taskId, $taskName,$taskDescription, $taskTime, $taskStatus);
}
if(isset($_POST['deleteTask'])){
	deleteTask($taskId);
}
if (isset($_POST['updateTodo'])){
	updateTodo($todoListId, $todoName);
	
}
if (isset($_POST['deleteTodo'])){
	deleteTodo($todoListId);
}



