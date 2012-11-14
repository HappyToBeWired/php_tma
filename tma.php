<?php
//delibaratlly suppressed theis undifined offset notice 
//http://www.dmxzone.com/go/13811/php-getting-notice-undefined-index/
error_reporting (E_ALL ^ E_NOTICE);
require_once 'functions.php';



$log_file_dir = 'logs/';
$process_files = new FindsExtractsAndFormats;
$process_files->check_existance();
// checks for existance of folder     


if ($process_files->check_existance() === 'folder_exists'){
    $process_files->read_files();
     
    }
else{
    echo"The folder \"$log_file_dir\" is not here.";
}
//reead individual files from log directory

$totalized=array('status code'=>'','article request'=>'','bandwidth consumed'=>'','total views'=>'');
$unique_404 = array();

$month_log= $process_files->read_files();
$i =0;
while($i < count($month_log)){
    $filtered_log_file=$process_files->extract_data_from_files($month_log[$i]);
    $i++;
    $rupert= new EnumerateAndOutput;
    $rupert->enumerate($filtered_log_file);
    $for_presentation = $rupert->return_array;
    $totalized['status code'] = $totalized['status code'] + $for_presentation['status code'];
    $totalized['article request'] = $totalized['article request'] + $for_presentation['article request'];
    $totalized['bandwidth consumed'] = $totalized['bandwidth consumed'] + $for_presentation['bandwidth consumed'];
    $totalized['total views'] = $totalized['total views'] + $for_presentation['total views'];
    $unique_404 = array_merge($unique_404,$for_presentation['unique_coincidental_404']);
    print_output($for_presentation['month'], $for_presentation['status code'], 
            $for_presentation['article request'], $for_presentation['bandwidth consumed'], $for_presentation['total views'], $for_presentation['unique_coincidental_404']);
}
 $total_unique_heading = 'Totals';
 
 print_output($total_unique_heading, $totalized['status code'], 
            $totalized['article request'], $totalized['bandwidth consumed'], $totalized['total views'], array_unique($unique_404));     
 
 $process_files->close_folder();




?>