<?php
// checks for existance of the folder
class FindsExtractsAndFormats {
     public $log_file_path = 'logs/';
     public $directory_name;
     public function check_existance(){
        if (file_exists($this->log_file_path)) {
            return 'folder_exists';  
        }
        else{
            return 0;
        }
        
      }
     //reads the  files
      public function read_files(){  
        $log_file_list = array();
        $this->directory_name = opendir($this->log_file_path); 
        //http://php.net/manual/en/function.readdir.php
        while (FALSE !== ($log_file_name = readdir($this->directory_name))){
            if ($log_file_name != "." && $log_file_name != "..") {
               array_push($log_file_list, $log_file_name);
            }  
         }
         return $log_file_list;
       }
       //formats the files into an array which can be more easily processed
       public function extract_data_from_files($log_file){
            $log_file_name=$log_file;
            $log_array_key= array('ip Address','timestamp','filename','http status code','bandwidth','user agent'); 
            $log_file_name_type_check = explode('.', $log_file_name);
            if ($log_file_name_type_check[1] === 'log'){
                $log_entries = fopen($this->log_file_path.$log_file_name, 'r');
                $log_array = array();
                while(!feof($log_entries)){
                    $temp_log_file_string=fgets($log_entries,1024);
                    if($temp_log_file_string !== FALSE){
                        $log_line = preg_split(' /[\"\)[]/', $temp_log_file_string);
                        $cut_array = array_slice($log_line, 3, 1);
                        $temp_array = explode(' ',$cut_array[0]);
                        //deletes leading a following values made of space
                        array_pop($temp_array);
                        array_shift($temp_array);
                        array_splice($log_line, 3, 1, $temp_array);
                       //gets rid of value made of space
                        array_pop($log_line);
                        //http://www.php.net/manual/en/function.array-combine.php
                        //meaningful key names
                        $log_line = array_combine($log_array_key, $log_line);
                        array_push( $log_array,$log_line);
                        
                } 
               }
           //gets rid of empty key at end
           array_pop($log_array); 
           array_unshift($log_array, $log_file_name_type_check[0]);
           fclose($log_entries);
           return $log_array;
             }
         }
         //closes folder after use
         public function close_folder(){
           
           closedir($this->directory_name);  
         }
}
//enumerates the required parts of the files and ouputs them as an array
class EnumerateAndOutput {
       private $status_code=0;
       private $article_request=0;
       private $bandwidth_consumed=0;
       private $coincidental_404_requests = array();
       private $article_regex =  '/articles\//';
       private $all_requests = 0;
       private $unique_coincidental_404_requests = array();
       public $return_array = array();


       function enumerate($get_some_output){
        $enumerated_output =$get_some_output;
        $start_position_to_avoid_the_month = 1;
        $i = $start_position_to_avoid_the_month;  
        while($i <= count($enumerated_output)){
           if($enumerated_output[$i]['http status code']==='404'){
                $this->status_code++;
            }
            if(preg_match($this->article_regex, $enumerated_output[$i]['filename'])==1){
                $this->article_request++;
            }
            if($enumerated_output[$i]['bandwidth']){
                $this->bandwidth_consumed = $enumerated_output[$i]['bandwidth'] + $this->bandwidth_consumed;
            }
            if($enumerated_output[$i]['http status code']==='404'){
                array_push($this->coincidental_404_requests,$enumerated_output[$i]['filename']);
            }
            
            $i++;
            $this->all_requests = $i;
            $this->unique_coincidental_404_requests=array_unique($this->coincidental_404_requests);   
        }
        $this->return_array['month']= $enumerated_output[0];
        $this->return_array['status code']=$this->status_code;
        $this->return_array['article request']=$this->article_request;
        $this->return_array['bandwidth consumed']=$this->bandwidth_consumed;
        $this->return_array['total views']=$this->all_requests-$start_position_to_avoid_the_month;
        $this->return_array['unique_coincidental_404']=$this->unique_coincidental_404_requests;
    } 
}

function print_output($month,$status_code,$article_request,$bandwidth_consumed,$total_views,$unique_404){
    $month = $month;
    $status_code = $status_code;
    $article_request = $article_request;
    $bandwidth_consumed = $bandwidth_consumed;
    $total_views =$total_views;
    $unique_404 = $unique_404;
    
    
    echo '<h2>'.$month.'</h2>';
    echo'<table>';
    echo '<tr><td><h3>404 Status Codes, </h3></td><td><h3>Request of artical, </h3></td><td><h3>Bandwidth used, </h3></td><td><h3>Total views</h3></td></tr>';
    echo '<tr><td>'.$status_code.'</td><td>'.$article_request.'</td><td>'.$bandwidth_consumed.'</td><td>'.$total_views.'</td></tr>';
    echo '<tr><td><h3>Uninque 404</h3></td><td>';
    foreach ($unique_404 as $value){
        echo"<tr><td>$value</td></tr>";}
    echo'</table>';
    
}
?>



