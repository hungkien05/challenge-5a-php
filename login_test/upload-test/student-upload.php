<!-- This is upload page for student -->
<?php
session_start();
require_once "dbconnect.php";
$username = $_SESSION['username'];
$hw_id = $_SESSION['hw_id'];
//check if form is submitted
$path ="hw_uploads/".$hw_id."/";
if (isset($_POST['submit']))
{
    $filename = $_FILES['file1']['name'];

    //upload file
    if($filename != '')
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed = ['pdf', 'txt', 'doc', 'docx', 'png', 'jpg', 'jpeg',  'gif', 'c'];
    
        //check if file type is valid
        if (in_array($ext, $allowed))
        {
            // get last record id
            $sql = 'select max(id) as id from tbl_files';
            $result = mysqli_query($con, $sql);
            if (count($result) > 0)
            {
                $row = mysqli_fetch_array($result);
                //$filename = ($row['id']+1) . '-' . $filename;
            }
            else
                //$filename = '1' . '-' . $filename;

            //set target directory
            //$path = "hw_uploads/".$hw_id;
            echo "path=".$path;
            echo "hw_uploads/".$hw_id;    
            $created = @date('Y-m-d H:i:s');
            if (move_uploaded_file($_FILES['file1']['tmp_name'],($path . $filename))){
            // insert file details into database
                $sql = "INSERT INTO student_hw (filename, fromUser, HWid, created) VALUES('$filename', '$username','$hw_id', '$created')";
                mysqli_query($con, $sql);
                
                header("Location: student-submit.php?hw_id=".$hw_id."&st=success");
            } else {
                echo "loi move_uploaded_file";
                header("Location:  student-submit.php?hw_id=".$hw_id."&st=error");
            }
        }    
        else
        {
            header("Location:  student-submit.php?hw_id=".$hw_id."&st=error");
        }
    }
    else
        header("Location:  student-submit.php?hw_id=".$hw_id."");
}
?>