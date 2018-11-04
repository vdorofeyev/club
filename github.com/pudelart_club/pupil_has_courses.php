<?php
include 'assets/header.php';
//include 'assets/config.php';
/*form processing*/ if(count($_POST)>0){
//print_r($_POST);
$status_form =filter_input(INPUT_POST,'status_form');
$sum_form =filter_input(INPUT_POST,'sum_form');
$pupil_id_form =filter_input(INPUT_POST,'pupil_id_form');
$date_id_form =filter_input(INPUT_POST,'date_id_form');
$sql=$status_form==''&&$sum_form==''?
    "delete from pudelart_club.pupil_attended_date where pupil_id=".$pupil_id_form." and date_id=".$date_id_form:
    "insert into pupil_attended_date(pupil_id, date_id, status, sum)"
    ." values(".$pupil_id_form.", ".$date_id_form.",'".$status_form."', '".$sum_form."')"
    ." on duplicate key update status='".$status_form."', sum='".$sum_form."'";
//echo $sql."<br>";
$pdo->exec($sql);
}
$pupil_id=filter_input(INPUT_GET,'pupil_id');
$pupil_name=$pdo->query("select name from pupil where id=".$pupil_id)->fetch(PDO::FETCH_ASSOC)['name'];
echo $pupil_name." ".pupil_balanse($pdo, $pupil_id)."<div style='color:red'>Список курсов.</div>";
//$courses=$pdo->query("select * from course")->fetchAll();
$sql="select *, (select count(*) as count from date join pupil_attended_date"
    ." on date.id = pupil_attended_date.date_id where pupil_id=".$pupil_id." and course_id=course.id)  as count from course"
." order by count desc";
$courses=$pdo->query($sql)->fetchAll();
foreach($courses as $course)
{$course_id=$course['id']; $course_name=$course['name'];
echo<<<TAG
<div  style='color:blue'>$course_name</div>
<table class="w3-table"><tr><th>Дата</th><th>Статус</th><th>Сумма</th><th>Изменение</th></tr>
TAG;
    $sql="select * from date where course_id=".$course_id." order by date desc";

foreach($pdo->query($sql)->fetchAll() as $date) {
    $dt=$date['date'];$date_id=$date['id'];

echo<<<TAG
<tr>

TAG;
$sql="select * from pupil_attended_date where date_id=".$date['id']." and pupil_id=".$pupil_id;
$attendance=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
$status=$attendance['status']; $sum=$attendance['sum'];
echo<<<TAG
<tr><form method="post">
<td><label>$dt</label></td>
<td><input type="text" class="w3-input" name="status_form" value="$status"><input type="hidden" name="date_id_form" value="$date_id"></td>
<td><input type="text" class="w3-input" name="sum_form" value="$sum"><input type="hidden" name="pupil_id_form" value="$pupil_id"</td>
<td><input type="submit" class="w3-input" value="Изменить"></td>
</form>
</tr>
TAG;
}
echo<<<TAG
</table>
TAG;
}
include 'assets/footer.php';