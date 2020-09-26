<!-- This is challenge upload page for teacher -->
<?php
session_start();
require_once "dbconnect.php";
$hint=$hint_err="";
// $_SESSION['hint']="";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $hint = trim($_POST["hint"]);
    //$_SESSION['hint']=$hint;
}

//check if form is submitted
if (isset($_POST['submit']))
{
    $filename = $_FILES['file1']['name'];

    //upload file
    if($filename != '')
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed = ['txt'];
    
        //check if file type is valid
        if (in_array($ext, $allowed))
        {
            //set target directory
            $path = 'challenge/';
            //echo "path = ".$path.", filename = ".$filename;
                
            $created = @date('Y-m-d H:i:s');
            if (move_uploaded_file($_FILES['file1']['tmp_name'],($path . $filename))){
            // insert file details into database
                
                $sql = "INSERT INTO challenge (filename, hint, created) VALUES('$filename', '$hint', '$created')";
                mysqli_query($con, $sql);
                header("Location: challenge.php?st=success");
            } else {
                echo "loi move_uploaded_file";
                header("Location: challenge.php?st=error");
            }
        }    
        else
        {
            header("Location: challenge.php?st=error");
        }
    }
    else
        header("Location: challenge.php");
}
?>