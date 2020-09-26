<?php
session_start();
include_once 'dbconnect.php';

$username = $_SESSION['username'];
$id = $_SESSION['id'];
if(isset($_GET['chl_id'])) $chl_id = $_GET['chl_id'];
$check = false;

// fetch files
$sql = "SELECT filename FROM challenge WHERE id=$chl_id ";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$answer="";
if (isset($_SESSION['answer'])) $answer = $_SESSION['answer']; 
$answer_err ="";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["answer"]))){
        $answer_err = "Please don't leave the answer box empty";     
    } else{
        $answer = trim($_POST["answer"]);
        $_SESSION['answer']=$answer;
        $answer_pattern = "/".$answer."\.\w+/";
        if ( preg_match($answer_pattern, trim($row["filename"])) ) $check= true; else $check = false;
    }
}
//validate the answer

?>

<!DOCTYPE html>
<html>
<head>
    <title>Solve Challenge </title>
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
            <div class="col-md-4 col-md-offset-1 well">
                <form action="<?php echo 'challenge-solve.php?chl_id='.$chl_id ?>" method="post" enctype="multipart/form-data">
                    <legend>Solve the challenge #<?php echo $chl_id; ?> by filling the box below:</legend>
                    <div class="form-group" <?php echo (!empty($answer_err)) ? 'has-error' : ''; ?>>
                        <label>Answer for this challenge</label>
                        <input type="text" name="answer" class="form-control" value="<?php echo $answer; ?>">
                        <span class="help-block"><?php echo $answer_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                </form>
            </div>
        </div>
        <div class ="row">
            <div class="col-md-4 col-md-offset-4 well">
                <?php
                if ($check and $answer !="") {
                    echo "Correct answer. Here is the content of the file: \n";
                    $myfile = fopen("challenge/".$row['filename'], "r") or die("Unable to open file!");
                    //echo fread($myfile, filesize("challenge/".$row['filename']));
                    while(!feof($myfile)) {
                        $line=fgets($myfile);
                        echo $line;
                        //echo strpos($line,":");
                        echo "<br />";
                    }
                    fclose($myfile);
                } elseif ($check ==false and $answer!="") {
                    echo "Incorrect answer. Try again.";
                }
                ?>
            </div>
        </div>
        
    </div>
    
</body>
</html>