<!-- This is upload page for teacher -->
<?php
require_once "dbconnect.php";
//check if form is submitted
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
            // if ($result)
            // {
            //     $row = mysqli_fetch_array($result);
            //     //$filename = ($row['id']+1) . '-' . $filename;
            // }
            
                //$filename = '1' . '-' . $filename;

            //set target directory
            $path = 'uploads/';

            
            $created = @date('Y-m-d H:i:s');
            if (move_uploaded_file($_FILES['file1']['tmp_name'],($path . $filename))){
            // insert file details into databas
                $sql = "INSERT INTO tbl_files (filename, created) VALUES('$filename', '$created') ";
                if (mysqli_query($con, $sql) ){
                    echo "success";
                } else echo "fail sql query";
                header("Location: index.php?st=success");
            } else {
                echo "loi move_uploaded_file";
                header("Location: index.php?st=error");
            }
        }    
        else
        {
            header("Location: index.php?st=error");
        }
    }
    else
        header("Location: index.php");
}
?>