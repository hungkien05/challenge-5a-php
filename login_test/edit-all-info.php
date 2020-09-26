<!-- Only teacher can use this page -->
<?php
// Initialize the session
session_start();
// Include config file
require_once "config.php";
$edit_id=-1;
// Check edit id
if(isset($_GET['edited_id'])) $edit_id = $_GET['edited_id'];
// echo "editing id= $edit_id";
$sql = "SELECT id, username, password, name, email, phone FROM users WHERE id = $edit_id";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result)>0) $row = mysqli_fetch_assoc($result);
 
// Define variables and initialize with empty values
$new_email = $confirm_email = $new_phone = $confirm_phone = "";
$new_email_err = $confirm_email_err = $new_phone_err = $confirm_phone_err = "";
$username = $password = $confirm_password = $email= $phone = $name ="";
$username_err = $password_err = $confirm_password_err = $email_err = $phone_err = $name_err = "";
$username = $row["username"];
$name = $row["name"];
$email = $row["email"];
$phone = $row["phone"];
$password = $row["password"];
$confirm_password = $password;
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            // $param_id = $edit_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    //Receive name
    {
        $name = trim($_POST["name"]);
    }
    //Validate email address
    $email_pattern = "/[\w\.]+@\w+\.[\w\.]+/";
    if (!preg_match($email_pattern, trim($_POST["email"]))) {
        $email_err = "Your email address is invalid. Please retype the correct form of email address.";
    }  
    else{
        $email = trim($_POST["email"]);
    }
    $phone_pattern = "/[\+\d]+/";
    //Validate phone number
    if (!preg_match($phone_pattern, trim($_POST["phone"]))) {
        $phone_err = "Phone number must only contain numbers.";
    } 
    else{
        $phone = trim($_POST["phone"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($phone_err)){
        
        // Prepare an insert statement
        $sql = "UPDATE users SET username = ?, password = ?, name = ?, email = ?, phone = ? WHERE id = $edit_id";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_password, $param_name, $param_email, $param_phone);
            
            // Set parameters
            $param_username = $username;
            $param_password = $password;
            $param_name = $name;
            $param_email = $email;
            $param_phone = $phone;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .container{ width: 350px; padding: 20px; }
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
                <h3>To change <b><?php echo "$username"; ?></b>'s information, please fill out this form</h3>
                <form action="<?php echo "edit-all-info.php?edited_id=$edit_id"; ?>" method="post">
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" placeholder=$username value="<?php echo $username; ?>">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Password</label>
                        <input type="text" name="password" class="form-control" placeholder=$password value="<?php echo $password; ?>">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password</label>
                        <input type="text" name="confirm_password" class="form-control" placeholder=$confirm_password value="<?php echo $confirm_password; ?>">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Full name</label>
                        <input type="text" name="name" class="form-control" placeholder=$name value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Email address</label>
                        <input type="text" name="email" class="form-control" placeholder=$email value="<?php echo $email; ?>">
                        <span class="help-block"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                        <label>Phone number</label>
                        <input type="text" name="phone" class="form-control" placeholder=$phone value="<?php echo $phone; ?>">
                        <span class="help-block"><?php echo $phone_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                </form>
            </div>
        </div>
    </div>    
</body>
</html>