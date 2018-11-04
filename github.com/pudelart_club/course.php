<?php
include 'assets/header.php';
//include 'assets/config.php';
$today=date_format(date_create(), 'Y-m-d');
if(count($_POST)>0)
{//print_r($_POST);
/*new course*/$new_course_name = filter_input(INPUT_POST,'new_course_name');
if($new_course_name<>null)
{
       $sql = "insert into course(name) values ('".$new_course_name."')";
    //   echo $sql."<br>";
    try{$pdo->exec($sql);}catch(Exception $e){print_r ($e);}
}
//course delete, update
$course_name_update= filter_input(INPUT_POST,'course_name_update');
$course_id_update= filter_input(INPUT_POST,'course_id_update');

 /*$course update/delete */
    if($course_id_update>0) {
        $sql = $course_name_update == null ? "DELETE FROM course WHERE id=" . $course_id_update
            : "UPDATE course SET name='" . $course_name_update . "' WHERE id=" . $course_id_update;
        //  echo $sql . "<br>";
        try {
            $pdo->exec($sql);
        } catch (Exception $e) {
            print_r($e);
        }
                        }
   //new course date
       $new_course_date = filter_input(INPUT_POST,'new_course_date');
       $course_id = filter_input(INPUT_POST,'course_id');
       if($new_course_date<>null)
       {
           $sql = "insert into date(course_id,date) values (".$course_id.", '".$new_course_date."')";
           // echo $sql."<br>";
              try{$pdo->exec($sql);} catch(Exception $e){print_r ($e);}
       }
          //course date delete, update
             $course_date_update= filter_input(INPUT_POST,'course_date_update');
             $date_id_update= filter_input(INPUT_POST,'date_id_update');
      /*     course date update/delete */ if($date_id_update<>null) {
          $sql=$course_date_update == null?"DELETE FROM date WHERE id=" . $date_id_update
              :"UPDATE date SET date='" . $course_date_update . "' WHERE id=" . $date_id_update;
    //   echo $sql . "<br>";
                  try{$pdo->exec($sql);}  catch(Exception $e){print_r ($e);}
             }

}
//Новый курс форма
echo<<<TAG
<form class="w3-form" method="post" >
    <div class = "w3-group">
        Наименование курса: <input  type = "text" name='new_course_name' placeholder="Введите новый курс"/>
    <input type="submit" value="Добавить"></div>
</form>
<table class="w3-table-all w3-small">
TAG;
$courses=$pdo->query("select * from course order by name")->fetchAll();
//Список курсов
foreach($courses as $course)
{
    $course_name = $course['name'];
    $course_id = $course['id'];

echo <<<TAG
<tr><form class="w3-form" method="post">
<td colspan="2"><input type="text" class="w3-input" name="course_name_update" style="color:red" value="$course_name"></td>
<td><input type="hidden" name="course_id_update" value="$course_id">
<input type="submit" class="w3-input" value="Изменить"> </td>
</form>
</tr>
<!-- new course date --><tr><form class="w3-form" method="post">
<td colspan="2"><input type="date" class="w3-input" name="new_course_date" value="$today">
<input type="hidden" name="course_id" class="w3-input" value="$course_id"</td>
<td><input type="submit" class="w3-input" value="Добавить"></td>
</form>
</tr>
TAG;
/*course dates list*/foreach($pdo->query("select * from date where course_id=".$course_id." order by date desc")->fetchAll() as $date)
    {
        $course_date=$date['date'];$date_id=$date['id'];
echo<<<TAG
<!-- Course date --><tr><form class="w3-input" method="post">
<td><a href="course_date_pupils.php?date_id=$date_id">Посещение</a>
<input type="hidden" name="date_id_update" value="$date_id"</td>
<td><input type="date"class="w3-input" name="course_date_update" value="$course_date"></td>
<td><input type="submit" class="w3-input" value="Изменить"></td>
</form>
</tr>
TAG;
        }
    }

echo<<<TAG
</table>
TAG;

include 'assets/footer.php';