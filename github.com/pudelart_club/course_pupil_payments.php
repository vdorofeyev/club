<?php
include 'assets/header.php';
//include 'assets/config.php';
$name=filter_input(INPUT_GET, 'name');
echo "Посещения и оплата курса '".$name."'";
$sql="select id from course where name='".$name."'";
$row=$pdo->query($sql)->fetch();
$course_id=$row['id'];
$sql="select id, date from date where course_id=".$course_id." order by date desc";
$dates=$pdo->query($sql)->fetchAll();
echo '<table class="w3-table-all w3-small"><tr><th>ФИО</th><th>Статус</th><th>Уплачено</th><th>Посещений</th><th>Осталось</th></tr>';
foreach($dates as $date)
{

    echo "<tr><td style='color: red'> ".$date['date']."</td></tr>";
    $sql="select * from date join pupil_attended_date on date.id = pupil_attended_date.date_id"
  ." join pupil on pupil_attended_date.pupil_id = pupil.id"
 ." where  date_id=".$date['id']." and course_id=".$course_id;
    $pupils=$pdo->query($sql)->fetchAll();
  foreach($pupils as $pupil)
    {
        echo "<tr><td>".$pupil['name']."</td>";
      $sql="select  sum(sum) as sum from pupil_attended_date join date on pupil_attended_date.date_id = date.id where  pupil_id=".$pupil['id']." and course_id=".$course_id;
       $sums=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC); $sum = $sums['sum'];
      //  echo "<td>".$sum."</td>";
              $sql="select  count(*) as count from pupil_attended_date  join date on pupil_attended_date.date_id = date.id where status='ok'  and pupil_id=".$pupil['id']
                  ." and course_id=".$course_id;
          //   echo "<td>".$sql."</td></tr>";
             $counts=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC); $count = $counts['count'];$r=$sum/PRICE; $r=$r-$count;
          echo "<td>".$pupil['status']."</td><td>".$sum."</td><td>".$count."</td>"."<td>".$r."</td></tr>";
        /**/}
}
echo "</table>";
/*echo "Платежи курса: ".$name;
$sql="select distinct  name from pupil join pupil_attended_date on pupil.id = pupil_attended_date.pupil_id join date on pupil_attended_date.date_id = date.id where course_id=".$course_id." order by name desc";
$pupils=$pdo->query($sql)->fetchAll();//print_r($pupils);
foreach($pupils as $pupil){
    echo "<br>".$pupil['name'];
}
$sql="select id, date from date where course_id=".$course_id." order by date desc";
$dates = $pdo->query($sql)->fetchAll();
foreach($dates as $date){
echo "<br>".$date['date'];
echo '<table class="w3-table-all w3-small"><tr><th>ФИО</th><th>Уплачено</th><th>Кол-во посещений</th></tr>';
}
$sql="select name, sum, date from pupil join pupil_attended_date on pupil.id = pupil_attended_date.pupil_id join date on pupil_attended_date.date_id = date.id where course_id=".$course_id." and status='ok' order by name desc";
$pupils=$pdo->query($sql)->fetchAll();//print_r($pupils);
foreach ($pupils as $pupil){

}

echo '<table class="w3-table-all w3-small"><tr><th>ФИО</th><th>Уплачено</th><th>Кол-во посещений</th></tr>';
foreach($pupils as $pupil){
   // print_r($pupil); echo "<br>";
    $name=$pupil['name']; $pupil_id=$pupil['id'];
    $res=course_pupil_balanse($pdo, $course_id, $pupil_id);
    $sum=$res['sum']; $count=$res['count'];
    echo $name.", ".$sum.", ".$count."<br>";
    echo "<tr><td>".$name."</td><td>".$sum."</td><td>".$count."</td></tr>";
}
echo '</table>';
*/
include 'assets/footer.php';