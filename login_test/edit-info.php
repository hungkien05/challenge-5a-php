<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "Edit successfully";
    header("location: edit-info.php");
    exit;
}
$edit_id = $_SESSION['id'];
echo $edit_id;
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_email = $confirm_email = $new_phone = $confirm_phone = "";
$new_email_err = $confirm_email_err = $new_phone_err = $confirm_phone_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //Validate email address
    $email_pattern = "/[\w\.]+@\w+\.[\w\.]+/";
    if(empty(trim($_POST["new_email"]))){
        $new_email_err = "Please enter your email.";     
    } elseif (!preg_match($email_pattern, trim($_POST["new_email"]))) {
        $new_email_err = "Your email address is invalid. Please retype the correct form of email address.";
    }  
    else{
        $new_email = trim($_POST["new_email"]);
    }
    $phone_pattern = "/[\+\d]+/";
    //Validate phone number
    if(empty(trim($_POST["new_phone"]))){
        $new_phone_err = "Please enter your phone number.";     
    } elseif (!preg_match($phone_pattern, trim($_POST["new_phone"]))) {
        $new_phone_err = "Phone number must only contain numbers.";
    } 
    else{
        $new_phone = trim($_POST["new_phone"]);
    }
        
    // Check input errors before updating the database
    if(empty($new_email_err) && empty($new_phone_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET email = ?, phone = ? WHERE id = $edit_id";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_phone);
            
            // Set parameters
            $param_email = $new_email;
            $param_phone = $new_phone;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Info updated successfully. Destroy the session, and redirect to welcome page
                //session_destroy();
                // echo "success";
                header("location: welcome.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "msqli_prepare error.";
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit information</title>
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
                    <li class="active"><a href="/login_test/edit-info.php">Edit your information</a></li>
                    <li><a href="/login_test/reset-password.php">Change your password</a></li>
                    <li><a href="/login_test/logout.php">Sign out</a></li>
                </ul>
            </div>
            <div class="vl"></div>
            <div class="col-md-4 col-md-offset-1">
                <h2>Edit information</h2>
                <p>Please fill out this form to edit your email address and phone number.</p>
                <form action="<?php echo "edit-info.php"; ?>" method="post"> 
                    <div class="form-group <?php echo (!empty($new_email_err)) ? 'has-error' : ''; ?>">
                        <label>New email address</label>
                        <input type="text" name="new_email" class="form-control" value="<?php echo $new_email; ?>">
                        <span class="help-block"><?php echo $new_email_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($new_phone_err)) ? 'has-error' : ''; ?>">
                        <label>New phone number</label>
                        <input type="text" name="new_phone" class="form-control">
                        <span class="help-block"><?php echo $new_phone_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a class="btn btn-link" href="welcome.php">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>    
</body>
</html>