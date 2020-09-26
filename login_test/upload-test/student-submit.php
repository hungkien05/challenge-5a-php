<?php
session_start();
include_once 'dbconnect.php';

$username = $_SESSION['username'];
$id = $_SESSION['id'];
if(isset($_GET['hw_id'])) $hw_id = $_GET['hw_id'];
$_SESSION['hw_id'] = $hw_id;
if (!file_exists('hw_uploads/'.$hw_id)) {
    mkdir('hw_uploads/'.$hw_id, 0777, true);
}

// fetch files
$sql = "SELECT filename FROM student_hw WHERE HWid=$hw_id ";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student submit | Demo</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" >
    <!-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" type="text/css" /> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" type = "text/css"/>
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
    <br/>
    <div class="container-fluid">
        <div class="row">
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
            <div class="col-xs-6 col-xs-offset-2 well">
                <form action="student-upload.php" method="post" enctype="multipart/form-data">
                    <legend>Select File to submit for homework #<?php echo $hw_id; ?>:</legend>
                    <div class="form-group">
                        <input type="file" name="file1" />
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" value="Upload" class="btn btn-info"/>
                    </div>
                    <?php if(isset($_GET['st'])) { ?>
                        
                        <?php if ($_GET['st'] == 'success') {
                            echo "<div class='alert alert-success text-cente'>";
                            echo "File Uploaded Successfully!";
                            echo "</div>";
                        }
                        else
                        {
                            echo "<div class='alert alert-danger text-center'>";
                            echo 'Invalid File Extension!';
                            echo "</div>";
                        } ?>
                        
                    <?php } ?>
                </form>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-6 col-xs-offset-4">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>View</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while($row = mysqli_fetch_array($result)) { ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row['filename']; ?></td>
                                <td><a href="hw_uploads/<?php echo $hw_id; ?>/<?php echo $row['filename']; ?>" target="_blank">View</a></td>
                                <td><a href="hw_uploads/<?php echo $hw_id; ?>/<?php echo $row['filename']; ?>" download>Download</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
    </html>