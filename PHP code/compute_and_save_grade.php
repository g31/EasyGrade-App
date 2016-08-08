<?php
//header('Access-Control-Allow-Origin: *');
include_once 'course_Dao.php';
include_once 'course_user_dao.php';

//$_POST = json_decode(file_get_contents('php://input'), true);

$courseId = $_POST["CourseId"];
$studentId = $_POST["StudentId"];

$Homework = $_POST["Homework"];
$Labs = $_POST["Labs"];
$Project = $_POST["Project"];
$Presentation = $_POST["Presentation"];
$Midterm = $_POST["Midterm"];
$Final = $_POST["Final"];


$data = get_course_info_by_id($courseId);
$course_name = $data['CourseName'];
$data1 = get_student_info_by_id($studentId);
$email = $data1['EmailId'];
$phone = $data1['Phone'];

//make sure that marks are not invalid
if($Homework > $data['MHomework'] || $Labs > $data['MLabs'] || $Project > $data['MProject'] || $Presentation > $data['MPresentation']
|| $Midterm > $data['MMidterm'] || $Final > $data['MFinal']){
	 //http_response_code(400);
	 $response = array("error"=>"one of the fields is exceeds maximum marks");
	 //$response['error'] = "One of the field has marks more than the max marks";
	 //print "returning the error";
	 header('Content-Type: application/json');
	 echo json_encode($response);	
	 exit();
}

//calculate percentage of every field.
$homework_percent = compute_percentage($data['MHomework'], $Homework, $data['PHomework']);
$labs_precent = compute_percentage($data['MLabs'], $Labs, $data['PLabs']);
$project_precent = compute_percentage($data['MProject'], $Project, $data['PProject']);
$presentation_precent = compute_percentage($data['MPresentation'], $Presentation, $data['PPresentation']);
$mideterm_percent = compute_percentage($data['MMidterm'], $Midterm, $data['PMidterm']);
$final_precent = compute_percentage($data['MFinal'], $Final, $data['PFinal']);

//print "\n homework marks: " . $homework_percent . "\n\n";

$total_percent = $homework_percent + $labs_precent + $project_precent + $presentation_precent + $mideterm_percent + $final_precent;

//print "total percentage: " . $total_percent ."\n\n";

$grade = compute_grade($total_percent, $data);

$total_marks = $Homework + $Labs + $Project + $Presentation + $Midterm + $Final;

insert_or_update_grades($courseId, $studentId, $Homework, $Labs, $Project, $Presentation, $Midterm, $Final, $grade);

$response_data = array("grade"=>$grade, "percentage"=>$total_percent, "totalMarks"=>$total_marks);

mail("$email", "Grades Released", "$course_name is graded. Your grade: $grade");
mail("+14087149978@tmomail.net", "Grades Released", "$course_name is graded. Your grade: $grade");

header('Content-Type: application/json');
echo json_encode($response_data);

//http_response_code(400);

function compute_percentage($max_marks, $marks_obtained, $weightage){
	$percentage = ($weightage * $marks_obtained)/$max_marks;
	return $percentage;
}

function compute_grade($total_percent, $data){
	if($total_percent >= $data['ARangeStart'] && $total_percent <= $data['ARangeEnd']){
		return "A";
	}
	else if ($total_percent >= $data['BRangeStart'] && $total_percent < $data['ARangeStart']){
		return "B";
	}
	else if ($total_percent >= $data['CRangeStart'] && $total_percent < $data['BRangeStart']){
		return "C";
	}
	else if ($total_percent >= $data['DRangeStart'] && $total_percent < $data['CRangeStart']){
		return "D";
	}
	else if ($total_percent >= $data['FRangeStart'] && $total_percent < $data['DRangeStart']){
		return "F";
	}
}

function get_student_info_by_id($studentId){
	
	$conn = get_connection();
	$sql = "SELECT * FROM users where ID=" . $studentId;
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
    // output data of each row
		while($row = mysqli_fetch_assoc($result)) {
			$data2 = array('ID'=>intval($row["ID"]), 'FirstName'=>$row["FirstName"], 'LastName'=>$row["LastName"], 'EmailId'=>$row["EmailId"], 'Phone'=>$row['Phone']);
		}
	} else {
		echo "0 results";
	}

	mysqli_close($conn);
	return $data2;
}

?>