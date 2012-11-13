<?php
// checks for existance of the folder
class FindsExtractsAndFormats {
     public $log_file_path = 'logs/';
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
        $directory_name = opendir($this->log_file_path); 
        //http://php.net/manual/en/function.readdir.php
        while (FALSE !== ($log_file_name = readdir($directory_name))){
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
                    
                    $log_line = preg_split(' /[\"\)[]/', $temp_log_file_string);
                    $cut_array = array_slice($log_line, 3, 1);
                    $temp_array = explode(' ',"$cut_array[0]");
                    array_pop($temp_array);
                    array_shift($temp_array);
                    array_splice($log_line, 3, 1, $temp_array);
                   //gets rid of empty key
                    
                    array_pop($log_line);
                    //http://www.php.net/manual/en/function.array-combine.php
                    //meaningful key names
                    $log_line = array_combine($log_array_key, $log_line);
                    //if(feof($log_entries))break;
                    array_push( $log_array,$log_line);                              
                 } 

           //gets rid of empty key at end
           array_pop($log_array); 
           //print_r($log_array);
           //var_dump($log_array);
           return $log_array;
             }
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
        $i = 0;

        while($i < count($enumerated_output)){
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
        
        $this->return_array['status code']=$this->status_code;
        $this->return_array['article request']=$this->article_request;
        $this->return_array['bandwidth consumed']=$this->bandwidth_consumed;
        $this->return_array['total views']=$this->all_requests;
    } 
}
?>



