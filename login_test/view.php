<?php
// Initialize the session
session_start();
// Include config file
require_once "config.php";
$view_username="";
$view_id=-1;
// Check if the user is logged in, if not then redirect him to login page
if(isset($_GET['viewed_id'])) $view_id = $_GET['viewed_id'];
// echo "viewing id= $view_id";

$from_id = $mess_content ="";
$from_id = $_SESSION["id"];
$view_username = "";
$mess_content_err = "";
$is_send = FALSE;

//Set is_send if sending
if (isset($_POST["Send"])){
	$is_send = TRUE;
}


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && $is_send ){
// Check if mess_content is empty
if(empty(trim($_POST["mess_content"]))){
$mess_content_err = "Please enter your message to this user.";
} else{
$mess_content = trim($_POST["mess_content"]);
}
if(empty($mess_content_err)){

// Prepare an insert statement
$sql = "INSERT INTO messages (fromID, toID, content) VALUES (?, ?, ?)";

if($stmt = mysqli_prepare($link, $sql)){
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt, "iis", $param_from_id, $param_toID, $param_mess_content);

// Set parameters
$param_from_id = $from_id;
$param_toID = $view_id;
$param_mess_content = $mess_content;

// Attempt to execute the prepared statement
if(mysqli_stmt_execute($stmt)){
// Redirect to login page
// echo "Success sending";
// header("location: view.php?view_id=$view_id");
// header("location: welcome.php");
} else{
echo "Something went wrong. Please try again later. loi sql";
// header("location: view.php?view_id='.$view_id.'");
// header("location: welcome.php");
}

// Close statement
mysqli_stmt_close($stmt);
}
}
// Close connection
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>View user</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

	<style type="text/css">
		body{ font: 14px sans-serif; /*text-align: center*/; 

            .border-right {
                border-right: 1px solid black;
            }
            .nav-pills {
              display: flex;
            }
            .nav-pills>li>a {
              height: 100%;
              display:flex !important;
              align-items:center;
            }
		}
		.vl { 
                border-left: 3px solid black; 
                height: 800px; 
                position:absolute; 
                left: 17%; 
            } 
		.form-inline {
			align-items: center;
		}
		.flex-container {
			display: flex;
			justify-content: center;
		}

		.form-inline label {
			margin: 5px 10px 5px 0;
		}

		.form-inline input {
			vertical-align: middle;
			margin: 5px 10px 5px 0;
			padding: 10px;
			border: 1px solid #ddd;
		}

		</style>
</head> 
	<body>
		<div class="page-header">
			<h5>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. You are logged in as a
				<?php if ($_SESSION['isTeacher']==0) echo "Student"; else echo "Teacher"; ?></h5>
		</div>
		<div class = "container-fluid">
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
        		<div class="col-md-2 col-md-offset-1">
					<?php
					// Display information of viewing id user
					require_once "config.php";
					$sql = "SELECT id, username, name, email, phone FROM users WHERE id = $view_id";
					$result = mysqli_query($link, $sql);

					if (mysqli_num_rows($result) > 0) {
						// echo "<table class='center'><tr><th>ID</th><th>Username</th><th>Name</th><th>Email address</th><th>Phone number</th><th></th></tr>";
			    	// output user information
						while($row = mysqli_fetch_assoc($result)) {  $view_username = $row["username"]; ?>
							
							<p>Username: <?php echo $row["username"]; ?></p>
							<p>Full name: <?php echo $row["name"]; ?></p>
							<p>User ID: <?php echo $row["id"]; ?></p>
							<p>Email address: <?php echo $row["email"]; ?></p>
							<p>Phone number: <?php echo $row["phone"]; ?></p>
							<?php 
							$tmp_id= $row['id'];
							if ($_SESSION['isTeacher']==1) 
							echo '<p><a href="/login_test/edit-all-info.php?edited_id='.$tmp_id.'">Edit all information of this user (Only teacher can access this)</a></p>'; else 
							echo '<button type="button" class="btn btn-primary disabled">
								  <span>Edit information of this user</span></br>
								  <span>(Only for teacher)</span>
								  </button>';
							?>
						<?php	
						}

					} else {
						echo "0 results";
					}

					?>
				</div>
				<div class="col-md-5 col-md-offset-1">
					<table class='table table-striped table-hover'>
						<thead>
							<tr>
								<th>From</th>
								<th>Message</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php

							echo "Message history";
							// Display all the messages between user and viewing id user
							$sql = "SELECT id, fromID, content FROM messages WHERE (fromID = $from_id AND toID = $view_id) OR (fromID = $view_id AND toID = $from_id) ";
							$result = mysqli_query($link, $sql);
							$delete_id =0; 
							if (mysqli_num_rows($result) > 0) { 

					    	// output data of each row
								while($row = mysqli_fetch_assoc($result)) { 
									$delete_id = $row['id'];
									if ($row["fromID"] == $from_id) $sending_user = $_SESSION["username"]; else $sending_user = $view_username ;
							?>
									<tr>
				                        <td><?php echo $sending_user; ?></td>
				                        <td><?php echo $row['content']; ?></td>
				                    	<?php
				                    	if ($row["fromID"] == $from_id) { 
				                    	?>
					                        <td><a href="edit-message.php?edited_id=<?php echo $row['id']; ?>" > Edit</a></td>
					                        <td><a href="edit-message.php?deleted_id=<?php echo $row['id']; ?>"> Delete</a></td>
					                        <?php } else echo"<td></td><td></td>"; ?>
				                    </tr>
				            <?php 
				            	}
				            }
				            ?>
				        </tbody>
				    </table>
					<form class="form-inline" class ="flex-container" action="<?php echo "view.php?viewed_id=$view_id"; ?>" method="post">
						<div class="form-inline">
							<label>Want to send a message ? </label>
							<input type="text" placeholder="Type your message" name="mess_content" class="form-control" value="<?php echo $mess_content; ?>">
							<input type="submit" class="btn btn-primary" action="<?php $is_send = TRUE; ?>" name="Send" value="Send">
							<span class="help-block"></span>
							<?php
							if (isset($_POST["Send"])){
								$is_send = TRUE;
								
							}
							?>
						</div>    
					</form> 
				</div>
			</div>
		</div>
	</body>

</html> 