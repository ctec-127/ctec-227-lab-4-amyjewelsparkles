<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Image Gallery - Lab 4</title>
</head>
<body>
    <div class="container">
        <?php 
            $message = "Please select a file to upload.";
            
            $upload_errors = array(
                UPLOAD_ERR_OK 				=> "No errors.",
                UPLOAD_ERR_INI_SIZE  		=> "Larger than upload_max_filesize.",
                UPLOAD_ERR_FORM_SIZE 		=> "Larger than form MAX_FILE_SIZE.",
                UPLOAD_ERR_PARTIAL 			=> "Partial upload.",
                UPLOAD_ERR_NO_FILE 			=> "No file.",
                UPLOAD_ERR_NO_TMP_DIR 		=> "No temporary directory.",
                UPLOAD_ERR_CANT_WRITE 		=> "Can't write to disk.",
                UPLOAD_ERR_EXTENSION 		=> "File upload stopped by extension.");  
                
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $error = $_FILES['file_upload']['error']; 
                $message = $upload_errors[$error]; // displaying error message
            }

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $tmp_file = $_FILES['file_upload']['tmp_name'];
            
                // set target file name
                // basename gets just the file name
                $target_file = basename($_FILES['file_upload']['name']);
                
                // set upload folder name
                $upload_dir = 'uploads';
                
                // Now lets move the file
                // move_uploaded_file returns false if something went wrong
                if(move_uploaded_file($tmp_file, $upload_dir . "/" . $target_file)){
                    $message = "File uploaded successfully";
                    
                } else {
                    $error = $_FILES['file_upload']['error'];
                    $message = $upload_errors[$error];
                }
            }

            if($_SERVER['REQUEST_METHOD'] == 'GET' ){
                if (isset($_GET['del'])){
                        unlink('uploads/'. $_GET['del']);
                        $message = "Image Deleted!"; 
                }
            }
        ?>
        <div class="jumbotron mt-3 p-4">
            <div class="bg-dark border border-white rounded mx-4 p-3">
                <h1 class="text-center text-white">Image Gallery</h1><br>
                <h2 class="text-center text-light">CTEC 227 - PHP II (Lab 4)</h2>
            </div>
            <br>

            <h3 class="text-info"><?php echo $message ?></h3><br>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="custom-file w-50">
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
                    <input type="file" class="custom-file-input"  name="file_upload" id="customFile">
                    <label class="custom-file-label" for="customFile">Select File</label>
                </div>
                <br><br>
                <input type="submit" class="btn btn-outline-info" value="Upload">
            </form>
        </div>

        <div class="container my-4 p-4 bg-dark">

        <?php
            $img = '';
            $dir = "uploads";
            $column = 4;
            
            if(is_dir($dir)){
                $dir_array = scandir($dir);
                foreach ($dir_array as $img) {
                    if(strpos($img,'.') > 0){
                        //echo "filename: {$img}<br/>";
                        if ($column > 3){
                            echo '<div class="row">';
                            $column = 1;
                        }//class="shadow p-3 mb-5 bg-white rounded"
                        echo '<div class="col-md-4 shadow p-4 mb-4 bg-white rounded">';
                        echo '<div class="thumbnail">';
                        echo '<img class="rounded shadow" src="' . $dir . '/' . $img . '"  alt="" style="width:100%">';
                        echo '<div class="caption"><p class="text-center"><br><a href="?del=' . $img . '"></a>';
                        echo '<a class="btn btn-outline-danger" href="?del=' . $img . '" role="button">Delete</a></p></div></div></div>';
                        $column++;
                        if ($column == 4) {
                            echo "</div>"; // end of row
                        }

                    }
                }
            }
        ?>

        </div>
    </div>
</body>
</html>