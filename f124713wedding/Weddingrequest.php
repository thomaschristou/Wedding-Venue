<?php
//The server information is hidden for GitHub
$thecapacity = intval(trim($_GET["thecapacity"]));
$startdate = $_GET["thestartdate"];
$enddate = $_GET["theenddate"];
$therating = intval(trim($_GET["therating"]));
$start = date("Y-m-d",strtotime($startdate));
$end = date("Y-m-d",strtotime($enddate));
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql="SELECT venue_booking.venue_id, name ,venue.capacity, booking_date,grade,weekend_price,weekday_price 
FROM `venue_booking` inner join `catering` on venue_booking.venue_id = catering.venue_id 
inner join `venue` on venue_booking.venue_id = venue.venue_id 
where catering.grade = $therating and venue.capacity >=$thecapacity and booking_date BETWEEN '$start' and '$end';";
$sql2 ="SELECT venue.venue_id, name ,licensed, capacity, grade,weekend_price,weekday_price, 
cost,count(booking_date) as \"total\"
from `venue` inner join `catering` on catering.venue_id = venue.venue_id 
inner join `venue_booking` on venue_booking.venue_id = venue.venue_id 
where capacity >=$thecapacity and grade = $therating
group by venue_id, name ,capacity, licensed, grade,capacity, weekend_price,weekday_price, cost;";
/*$sql2 = "SELECT venue.venue_id, name ,licensed, capacity, grade,weekend_price,weekday_price, cost 
from `venue` inner join `catering` on catering.venue_id = venue.venue_id 
where capacity >=$thecapacity and grade = $therating
group by venue_id, name ,capacity, licensed, grade,capacity, weekend_price,weekday_price, cost;";
*/

$result = mysqli_query($conn, $sql); 
$result2 = mysqli_query($conn, $sql2); 

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
   $allDataArray[] = $row;
}

while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
   $allDataArray2[] = $row;
}


function getDatesFromRange($start, $end){
	$dates = array();
	while ($start <= $end){
		$dates[]  = date("Y-m-d", $start);
		$start = strtotime("+1 day", $start);
	}
	return $dates;
}


$listofdatelists= getDatesFromRange(strtotime($start), strtotime($end));


for($i = 0; $i < count($allDataArray2);$i++){
	$newrow = $allDataArray2[$i];
		for($j = 0; $j < count($listofdatelists);$j++){
			//array_push($newrow,$listofdatelists[$j]);
			$newrow["booking_date"] = $listofdatelists[$j];
			$listofalldatesforallvenues[] = $newrow;
			$newrow = $allDataArray2[$i];
		}
	
}




for($i = 0; $i < count($allDataArray);$i++){
	$selectedrow = $allDataArray[$i];
	for($j = 0; $j < count($listofalldatesforallvenues);$j++){
		if (($listofalldatesforallvenues[$j]["name"] ==$selectedrow["name"])and ($listofalldatesforallvenues[$j]["booking_date"] == $selectedrow["booking_date"])){
			if (($key = array_search($listofalldatesforallvenues[$j], $listofalldatesforallvenues)) !== false) {
				unset($listofalldatesforallvenues[$key]);
				$listofalldatesforallvenues = array_values($listofalldatesforallvenues);
			}
		}
	}
}


echo json_encode($listofalldatesforallvenues);
?>