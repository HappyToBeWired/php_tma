<?php
require_once 'functions.php';
//require_once 'classes.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//$directory_content = new ConditionFilesForProcessing();
//variable to check folder existance
$log_file_dir = 'logs/';
//$key_array = array();
$does_folder_exist=check_existance($log_file_dir);
// checks for existance of folder     


if ($does_folder_exist === 'folder_exists'){
    //echo 'hello Folder';
    $abetter_name_needed= read_files($log_file_dir);
     
    }
    

else{
    echo"The folder \"$log_file_dir\" is not here.";
}

/*reead individual files from log directory
 * 
 */
$get_some_output=$abetter_name_needed;

output_array($get_some_output);


  

?>