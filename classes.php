<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Class ConditionFilesForProcessing
{
 $temp_log_array = array();
  
 public function read_files($log_file_dir){
      $log_file_dir=$log_file_dir;
      $directory_name = opendir($log_file_dir);
     // echo $directory_name;
      while (FALSE !== ($log_file_name = readdir($directory_name))) {
        $log_file_name_type_check = explode('.', $log_file_name);
        //echo $log_file_name;
        //checks if log file type
        if ($log_file_name_type_check[1] === 'log'){
        //concatenates directory and file name
            $log_entries = fopen($log_file_dir.$log_file_name, 'r');
            //creates array with same name as month to hold log files
            //echo($log_file_name_type_check[0]);
            $a = $log_file_name_type_check[0];
            $$a = array();
            ${$a}['month'] = $a;
            //creates an array to hold a single line of log
            //$log_line= array();
            //echo $log_entries;
            //echo $log_file_name;
            while(!feof($log_entries)){
                $temp_log_file_string=fgets($log_entries,1024);
                //echo $temp_log_file_string;
                $log_line = preg_split('/[\"\[]/', $temp_log_file_string);
                array_push( ${$a},$log_line);
               
            } 
          
        }
       }  
           
            //print_r($april) ;
           
           // print_r($may) ;
      } 
    //eo readfiles
    // close directory here

  function neat_print($month){
       print_r($month) ;
       echo'<p>-----------</p><p>---------</p><p>--------</p><p>-----</p><p>----------</p>';
  
  }
       
 
} 

?>
