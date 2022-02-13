<?php
$userid=$_GET['studentid'];
$itemname=$_GET['itemname'];

include ('mysqli_connect.php');

$sqla="SELECT count(student_id) a from member_info where student_id={$userid}";
$resa=mysqli_query($dbc,$sqla);
$resulta=mysqli_fetch_array($resa);
// if($resulta[a] == 1){
    if(isset($itemname)){
        $sqlb = "SELECT item_id, name, remarks, price, stock_date, shelf_id, state,class_info.class_name FROM `item_info`,`class_info` WHERE (item_info.class_id = class_info.class_id) and (name like '%{$itemname}%')";
        $resb=mysqli_query($dbc,$sqlb);
    
      
        $results = array();
        while($line = mysqli_fetch_assoc($resb)){
            $results[] = $line;
        }    
       
        echo json_encode($results);
    }
    else echo '{"err":"empty request param"}'
// }
// else echo '{"err":"student_id not exist"}'
?>
