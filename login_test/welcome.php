<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" type = "text/css"/>
    <style type="text/css">
        .vl { 
                border-left: 6px solid black; 
                height: 800px; 
                position:absolute; 
                left: 17%; 
            } 
        body{ font: 14px sans-serif; 
            
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
        
        /*table, th, td {
            border: 1px solid black;
            padding: 10px;
        }
        table.center {
          margin-left: auto;
          margin-right: auto;
        }*/
    </style>
</head>
<body>
    <div class="page-header">

        <h4>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the site.</h4>
    </div>
    <div class = "container-fluid">
        <div class = "row" >
            <div class="col-md-2 border-right">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="/login_test/welcome.php">Home</a></li>
                    <li><a href="/login_test/upload-test/index.php">Homework</a></li>
                    <li><a href="/login_test/upload-test/challenge.php">Challenge</a></li>
                    <li><a href="/login_test/edit-info.php">Edit your information</a></li>
                    <li><a href="/login_test/reset-password.php">Change your password</a></li>
                    <li><a href="/login_test/logout.php">Sign out</a></li>
                </ul>
            </div>
            <div class="vl"></div>
            <div class="col-md-6 col-md-offset-1">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Username</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    require_once "config.php";
                    $sql = "SELECT id, username FROM users";
                    $result = mysqli_query($link, $sql);
                    $i = 1;
                    while($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><a href="view.php?viewed_id=<?php echo $row['id']; ?>"> View</a></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>