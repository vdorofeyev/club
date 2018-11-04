<?php
include 'assets/header.php';
$date_to = date_create();
//echo date_format($date_to, 'Y-m-d');
$to=date_format($date_to, 'Y-m-d');//echo "<br>".$to;
$date_from=date_add($date_to, date_interval_create_from_date_string('-1 month'));
$from = date_format($date_from, 'Y-m-d');
/* form processing*/if(count($_POST)>0)
{//print_r($_POST);
    $from=filter_input(INPUT_POST,'from');
    $to=filter_input(INPUT_POST,'to');
}

echo<<<TAG
<form method="post" class="w3-form">
с: <input type="date" class="w3-input" name="from" value="$from"> 
по: <input type="date" class="w3-input" name="to" value="$to"> 
<input type="submit" class="w3-input" value="Пересчитать">   
</form>
<table class="w3-table-all w3-small">
TAG;
$sql = "select * from course order by name";
$courses=$pdo->query($sql)->fetchAll();
$total_sum_add=0; $ok_count_add=0;
echo "<tr><td>ФИО\Курс (".pupil_course_dates_sum($pdo,0, 0, $from, $to).")</td>";
foreach($courses as $course)
{    $name=$course['name'];$course_id=$course['id'];
 echo"<td>".$name."(".pupil_course_dates_sum($pdo,0, $course_id, $from, $to).")</td>";
}
echo "</tr>";
$sql="select id, name from pupil order by name";
$pupils=$pdo->query($sql)->fetchAll();
//echo '<div style="height: 100px; overflow-y: scroll;">';
foreach($pupils as $pupil)
{$pupil_id=$pupil['id']; $name=$pupil['name'];
echo "<tr><td>".$name." ".pupil_course_dates_sum($pdo, $pupil_id, 0, $from, $to)."</td>";
foreach($courses as $course)
{$course_id=$course['id'];
    echo "<td>".pupil_course_dates_sum($pdo, $pupil_id, $course_id, $from, $to)."</td>";
}
echo "</tr>";
}
//echo "</div>";
echo "</table>";
include 'assets/footer.php';