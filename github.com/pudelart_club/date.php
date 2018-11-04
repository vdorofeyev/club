<?php
include 'assets/header.php';
include 'assets/config.php';
//parse get parameters
$course_name=filter_input(INPUT_GET, 'name');
echo "Даты курса: ".$course_name;
$sql="select id from course where name='".$course_name."'";
$row=$pdo->query($sql)->fetch();
$course_id=$row['id'];
//dates list
$sql="select * from date where course_id=".$course_id." order by date desc";
$dates=$pdo->query($sql)->fetchAll();
//parse post parameters
//update date
$olddate=filter_input(INPUT_POST,'olddate');
$newdate=filter_input(INPUT_POST,'newdate');
/* update date */if($olddate<>null)
{
    $sql = "update date set date='".$newdate."' where course_id='"
        .$course_id
        ."' and date='".$olddate."'";
    try {
        $rows = $pdo->exec($sql);
    } catch (Exception $e) {  print_r($e->getMessage()); }
}
//delete date
$delid=filter_input(INPUT_POST,'delid');
/* delete date */if($delid<>null)
{
    $sql = "delete from date where id='".$delid."'";
    try {
        $rows = $pdo->exec($sql);
    } catch (Exception $e) {  print_r($e->getMessage()); }
}
//add new date
$add_date=filter_input(INPUT_POST,"add_date");
/* add date */if($add_date<>null)
{
    $sql = "insert into date(course_id, date) values('" . $course_id . "', '" . $add_date . "')";
    echo $sql;
    try {
        $rows = $pdo->exec($sql);
    } catch (Exception $e) {  print_r($e->getMessage()); }
}
echo<<<TAG
<!-- add date --><form method="post" > 
    <div class = "w3-group">
     Дата: <input  type = "date" name='add_date' value=""/>
    <input type="submit" value="Добавить"></div>
</form>
TAG;
echo<<<TAG
<!-- course dates list--><table class="w3-table-all w3-small">
TAG;
/* dates list */foreach($dates as $date){ $dt=$date['date']; $id=$date['id'];
echo<<<TAG
<!-- update, delete date tr--><tr>  <td>
<!-- update date form--><form method="post">
            <input type="hidden" name="id" value="$id"/>
            <input type="hidden" name="olddate" value="$dt"/>
            <input type="date" name="newdate" value="$dt"/>
            <input type="submit" value="Сохранить">
 <!-- end of update date form--></form>
<!-- delete date form--><form method="post">
            <input type="hidden" name="delid" value="$id"/>
            <input type="submit" value="Удалить">
 <!-- end of delete date form--></form>     </td>
<!-- end of delete, update tr -->           </tr>

<!-- new sum tr--><tr><td><!-- new sum -->
<!-- new sum form--><form metod="post" class="w3-form">

<!-- end of new sum form--></form></td>
<!-- end of new sum tr --></tr>
<!-- sum for course list tr--><tr><td>
<!-- sum for course list table --><table class="w3-table-all w3-small">
<!-- headers tr --><tr><th>ФИО</th><th>Сумма</th><th>Статус</th></tr>
TAG;
    $sql="select * from pupil_attended_date join pupil"
        ." on pupil_attended_date.pupil_id = pupil.id"
        ." where date_id=$id";
    $payments=$pdo->query($sql)->fetchAll();
/* payments list */ foreach($payments as $payment){$fio=$payment['name'];echo $name."<br>";
    $status=$payment['status']; $sum=$payment['sum']; $sum_status=$payment["sum-status"];
echo<<<TAG
<!-- payment data tr --><tr><td>$fio</td><td><input class="w3-input" type="text" value=$sum /></td>
<td><input class="w3-input" type="text" value=$status /></td>
<!-- end of payment data tr --></tr>
TAG;
}
echo<<<TAG
<!-- end of sum for course list table --></table>
</td>
</tr>
TAG;
/* end of dates list cycle*/}
echo<<<TAG
<!-- end of course dates list table--></table>

TAG;


include 'assets/footer.php';