<?php

header('Access-Control-Allow-Origin: *');
include_once 'database_config.php';

//handle /student_data?studentId=something
if(isset($_GET["studentId"])){
	//print "id is set";
	$studentId = $_GET["studentId"];
	$data = get_student_info_by_id($studentId);
}
else{
	//handle /student_data
	$data = get_student_info();
	//print "Id is not set";
}

header('Content-Type: application/json');
//$data = get_student_info();
//print "returning json\n";
echo json_encode($data);


function get_student_info_by_id($studentId){
	
	$conn = get_connection();
	$sql = "SELECT * FROM users where ID=" . $studentId;
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
    // output data of each row
		while($row = mysqli_fetch_assoc($result)) {
			$data = array('ID'=>intval($row["ID"]), 'FirstName'=>$row["FirstName"], 'LastName'=>$row["LastName"], 'EmailId'=>$row["EmailId"], 'Phone'=>$row['Phone']);
		}
	} else {
		echo "0 results";
	}

	mysqli_close($conn);
	return $data;
}

function get_student_info(){
	
	$conn = get_connection();

	//$sql = "SELECT * FROM users";
	$sql = "SELECT * FROM users INNER JOIN course_user ON users.ID = course_user.PUserId JOIN courses ON course_user.PCourseId = courses.ID";
	$result = mysqli_query($conn, $sql);

	$array = array();
	if (mysqli_num_rows($result) > 0) {
    // output data of each row
		while($row = mysqli_fetch_assoc($result)) {
			//print_r($row);
			$data = array('ID'=>$row["PUserId"], 'FirstName'=>$row["FirstName"], 'LastName'=>$row["LastName"], 'EmailId'=>$row["EmailId"], 'Phone'=>$row['Phone'], 'CourseId'=>$row['PCourseId'], 'CourseName'=>$row['CourseName']);
			$array[] = $data;
		}
	} else {
		echo "0 results";
	}

	mysqli_close($conn);
	return $array;
}

?>
