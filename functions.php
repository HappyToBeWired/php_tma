<?php
// checks for existance of the folder
function check_existance($log_file_dir){
    $log_file_dir = $log_file_dir;
    if (file_exists($log_file_dir)) {
        return 'folder_exists';  
    }
    else{
       return 'folder_does_not_exist';
    }
    
  }
  
  
  function read_files($log_file_dir){  
    $log_file_dir=$log_file_dir;
    $log_array_key= array('ip Address','timestamp','filename','http status code','bandtidth','user agent'); 
    $directory_name = opendir($log_file_dir);
    //not use glob as this limits reuse of code to folders on the server
    while (FALSE !== ($log_file_name = readdir($directory_name))){
        //checks existtance of .log files
        $log_file_name_type_check = explode('.', $log_file_name);
        if ($log_file_name_type_check[1] === 'log'){
        //concatenates directory and file name
            $log_entries = fopen($log_file_dir.$log_file_name, 'r');
            //creates array with same name as month to hold log files
            //echo($log_file_name_type_check[0]);
            $log_array_name = $log_file_name_type_check[0];
            $log_array = array();
            //creates sub array with month name
            //$log_array['month'] = $log_array_name;
            while(!feof($log_entries)){
                $temp_log_file_string=fgets($log_entries,1024);
                //echo $temp_log_file_string;
                $log_line = preg_split(' /[\"\)[]/', $temp_log_file_string);
                $temp_array = array_slice($log_line, 3, 1);
                $temp_array = preg_split('/[\s]/', $temp_array[0]);
                array_pop($temp_array);
                array_shift($temp_array);
                array_splice($log_line, 3, 1, $temp_array);
               //gets rid of empty key
                array_pop($log_line);
                //http://www.php.net/manual/en/function.array-combine.php
                //meaningful key names
                $log_line = array_combine($log_array_key, $log_line);
                array_push( $log_array,$log_line);
               
            } 
       //gets rid of empty key at end
       array_pop($log_array); 
       //print_r($log_array);
       return $log_array; array_pop($log_line);
         }
         //print_r($may);
         
    }
    
}
/*
 * outputs and formats 
 */


function output_array($readfiles){
    $read_files = $readfiles;
    $i=0;
    echo'<table>';
    echo '<tr><td>'.str_pad('IP Address', 5).'</td><td>'.str_pad('Timestamp',3).'</td><td>'.str_pad('Filename', 3).'</td><td>'.str_pad('HTTP Status Code', 3).'</td><td>'.str_pad('Bandwidth Used', 3).'</td><td>'.str_pad('User Agent',3).'</td></tr>';
    while($i < count($read_files)){
        echo '<tr>';
        foreach ($read_files[$i] as $value) {
            echo'<td>';
            echo $value;
            echo'</td>'; 

            }

      echo '</tr>';
      
    $i++;
    }   
    echo'</table>'; 
    //print_r($read_files);
} 
?>



