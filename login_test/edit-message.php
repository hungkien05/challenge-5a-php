<?php
// Initialize the session
session_start();
// Include config file
require_once "config.php";
$edit_id=$delete_id=-1;
// Check edit id
if(isset($_GET['edited_id'])) $edit_id = $_GET['edited_id'];
if(isset($_GET['deleted_id'])) $delete_id = $_GET['deleted_id'];
// echo "editing id= $edit_id";
if ($delete_id>0) {
    $sql = "DELETE FROM `messages` WHERE `messages`.`id` = $delete_id";
    if (mysqli_query($link, $sql)) {
        echo "delete sucesss";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    else echo "delete fail";
} elseif ($edit_id>0){

$sql = "SELECT id, content,fromID, toID FROM messages WHERE id = $edit_id";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result)>0) $row = mysqli_fetch_assoc($result);

// Define variables and initialize with empty values
$new_content = $confirm_content ="";
$new_content_err = $confirm_content_err="";
$content = $row["content"];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //Receive name
    {
        $content = trim($_POST["content"]);
    }
    
    

    // Check input errors before inserting in database
    if(1){
        
        // Prepare an insert statement
        $sql = "UPDATE messages SET content = ? WHERE id = $edit_id";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_content);
            
            // Set parameters
            $param_content = $content;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: view.php?viewed_id=".$row['toID']);
            } else{
                echo "Execute error";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else echo "There is a mysqli_prepare() error";
    } else echo "There is an empty() error";
    // Close connection
    //mysqli_close($link);
}
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit message</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
        .vl { 
                border-left: 6px solid black; 
                height: 800px; 
                position:absolute; 
                left: 17%; 
            } 
    </style>
</head>
<body>
    <div class="page-header">
        <h5>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. You are logged in as a
            <?php if ($_SESSION['isTeacher']==0) echo "Student"; else echo "Teacher"; ?></h5>
    </div>
    <div class="container-fluid">
        <div class = "row" >
            <div class="col-md-2 border-right">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/login_test/welcome.php">Home</a></li>
                    <li><a href="/login_test/upload-test/index.php">Homework</a></li>
                    <li><a href="/login_test/upload-test/challenge.php">Challenge</a></li>
                    <li><a href="/login_test/edit-info.php">Edit your information</a></li>
                    <li><a href="/login_test/reset-password.php">Change your password</a></li>
                    <li><a href="/login_test/logout.php">Sign out</a></li>
                </ul>
            </div>
            <div class="vl"></div>
            <div class="col-md-4 col-md-offset-1">
        <!-- <h2>Edit information</h2> -->
                <h3></h3>
                <form action="<?php echo "edit-message.php?edited_id=$edit_id"; ?>" method="post">
                    <div class="form-group">
                        <label>Edit your message in the below box</label>
                        <input type="text" name="content" class="form-control" placeholder=$content value="<?php echo $content; ?>">
                    </div>    
                    
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Done">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                </form>
            </div>
        </div>
    </div>    
</body>
</html>