<?php
//delibaratlly suppressed theis undifined index warning
//http://www.dmxzone.com/go/13811/php-getting-notice-undefined-index/
//error_reporting (E_ALL ^ E_NOTICE);
require_once 'functions.php';
//require_once 'classes.php';


$log_file_dir = 'logs/';
$process_files = new FindsExtractsAndFormats;
$process_files->check_existance();
var_dump($process_files->check_existance());
// checks for existance of folder     


if ($process_files->check_existance() === 'folder_exists'){
    //echo 'hello Folder';
    $process_files->read_files();
     
    }
    

else{
    echo"The folder \"$log_file_dir\" is not here.";
}
/*reead individual files from log directory
 * 
 */
$totalized=array('status code'=>'','article request'=>'','bandwidth consumed'=>'','total views'=>'');

//var_dump($process_files->read_files($log_file_dir));
$month_log= $process_files->read_files();
var_dump($month_log);
$i =0;
while($i < count($month_log)){
    //var_dump($month_log[$i]);
    $filtered_log_file=$process_files->extract_data_from_files($month_log[$i]);
    //var_dump($filtered_log_file);
    $i++;
    $rupert= new EnumerateAndOutput;
    $rupert->enumerate($filtered_log_file);
    //var_dump($rupert);
    $rupert->return_array;
    $totalized['status code'] = $totalized['status code'] + $rupert->return_array['status code'];
    $totalized['article request'] = $totalized['article request'] + $rupert->return_array['article request'];
    $totalized['bandwidth consumed'] = $totalized['bandwidth consumed'] + $rupert->return_array['bandwidth consumed'];
    $totalized['total views'] = $totalized['total views'] + $rupert->return_array['total views'];
    echo'<table>';
    echo '<tr><td><h3>404 Status Codes, </h3></td><td><h3>Request of artical, </h3></td><td><h3>Bandwidth used, </h3></td><td><h3>Total views</h3></td></tr>';
    echo '<tr><td>'.$rupert->return_array['status code'].'</td><td>'.$rupert->return_array['article request'].'</td><td>'.$rupert->return_array['bandwidth consumed'].'</td><td>'.$rupert->return_array['total views'].'</td></tr>';
    echo'</table>'; 
    var_dump($totalized);
}





?>