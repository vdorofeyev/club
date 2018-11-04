<?php
include 'assets/header.php';
//include 'assets/config.php';

//form processing
if(count($_POST)>0)
{//print_r($_POST);
    $status_form =filter_input(INPUT_POST,'status_form');
    $sum_form =filter_input(INPUT_POST,'sum_form');
    $pupil_id_form =filter_input(INPUT_POST,'pupil_id_form');
    $date_id_form =filter_input(INPUT_POST,'date_id_form');
    if($status_form==''&&$sum_form=='')
        $sql="delete from pupil_attended_date where pupil_id=".$pupil_id_form." and date_id=".$date_id_form;
    else {
        $sum_form=$sum_form==''?0:$sum_form;
        $sql = "INSERT INTO pupil_attended_date(pupil_id, date_id, status, sum)"
            . " VALUES(" . $pupil_id_form . ", " . $date_id_form . ",'" . $status_form . "', " . $sum_form . ")"
            . " ON DUPLICATE KEY UPDATE status='" . $status_form . "', sum=" . $sum_form;
    }
    //   echo "sql: ".$sql."<br>";
    try{ $pdo->exec($sql);} catch (Exception $e) {var_dump($e);}
}
$date_id=filter_input(INPUT_GET,'date_id');
$sql="select * from date join course on course_id=course.id where date.id=".$date_id;
$date_row=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
$course_id=$date_row['course_id'];
$course_date=$date_row['date'];
$course_name=$date_row['name']."<br>";
//echo $course_id.$course_name.$course_date."<br>";
echo<<<TAG
<div>$course_name $course_date </div>
<table class="w3-table-all w3-small">
<tr><th>ФИО</th><th>Статус</th><th>Сумма</th><th>Изменение</th></tr>
TAG;
//$pupils=$pdo->query("select * from pupil order by name")->fetchAll();
//$sql="select *, (select count(*) as count from date join pupil_attended_date on date.id = pupil_attended_date.date_id
//join pupil on pupil_attended_date.pupil_id = pupil.id
//                 where course_id=".$course_id." and pupil_id=pupil.id)  as count from pupil order by count desc, name";
$sql="select *, (select count(*) from pupil_attended_date where id=pupil_ID and date_id in (select id from date where course_id=".$course_id.")) as count
from pupil ORDER by count desc, name";
$pupils=$pdo->query($sql)->fetchAll();
foreach($pupils as $pupil)
{$pupil_id=$pupil['id']; $pupil_name=$pupil['name'];
    $r=pupil_balanse($pdo,$pupil_id);
    $sql="select * from pupil_attended_date where pupil_id=".$pupil_id." and date_id=".$date_id;
    $pupil_attended_date=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    $status=$pupil_attended_date['status']; $sum=$pupil_attended_date['sum'];
    echo<<<TAG
<tr ><form method="post">
<td><label class="w3-input">$pupil_name $r</label>
<input type="hidden" name="pupil_id_form" value="$pupil_id">
<input type="hidden" name="date_id_form" value="$date_id">
</td>
<td><input type="text" class="w3-input" name="status_form" value="$status"></td>
<td><input type="number" class="w3-input" name="sum_form" value="$sum"></td>
<td><input type="submit" class="w3-input" value="Изменить"</td>
</form></tr>
TAG;
}
echo<<<TAG
</table>
TAG;


include 'assets/footer.php';