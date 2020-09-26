<?php
session_start();
include_once 'dbconnect.php';

if (!file_exists('challenge/')) {
    mkdir('challenge/', 0777, true);
}

// fetch files
$sql = "SELECT filename, id, hint FROM challenge";
$result = mysqli_query($con, $sql);

$hint=$hint_err=$_SESSION['hint']="";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["hint"]))){
        $hint_err = "Please enter your hint.";     
    } else{
        $hint = trim($_POST["hint"]);
        $_SESSION['hint']=$hint;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Challenge</title>
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 border-right">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/login_test/welcome.php">Home</a></li>
                    <li><a href="/login_test/upload-test/index.php">Homework</a></li>
                    <li class="active"><a href="/login_test/upload-test/challenge.php">Challenge</a></li>
                    <li><a href="/login_test/edit-info.php">Edit your information</a></li>
                    <li><a href="/login_test/reset-password.php">Change your password</a></li>
                    <li><a href="/login_test/logout.php">Sign out</a></li>
                </ul>
            </div>
            <div class="vl"></div>
             <!-- phan quyen giao vien, hoc sinh -->
            <?php if ($_SESSION['isTeacher']==1) { ?> 
            <div class="col-xs-8 col-xs-offset-2 well">
                <form action="challenge-upload.php" method="post" enctype="multipart/form-data">
                    <legend>Create new challenge:</legend>
                    <div class="form-group">
                        <input type="file" name="file1" />
                    </div>
                    <div class="form-group" <?php echo (!empty($hint_err)) ? 'has-error' : ''; ?>>
                        <label>Hint for this challenge</label>
                        <input type="text" name="hint" class="form-control" value="<?php echo $hint; ?>">
                        <span class="help-block"><?php echo $hint_err; ?></span>
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
                            echo 'Invalid File Extension! Only txt file allowed';
                            echo "</div>";
                        } ?>

                    <?php } ?>
                </form>
            </div>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-xs-6 col-xs-offset-4">
                <h4>Here are the challenge we have so far: </h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Challenge ID</th>
                            <th>File Name</th>
                            <th>Hint</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while($row = mysqli_fetch_array($result)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['filename']; ?></td>
                                <td><?php echo $row['hint']; ?></td>
                                <?php echo '<td><a href="challenge-solve.php?chl_id='.$row['id'].'">Solve</td>'  ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>