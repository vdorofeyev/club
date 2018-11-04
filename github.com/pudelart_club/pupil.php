<?php
include 'assets/header.php';
//include 'assets/config.php';
$sql="select * from course where 1 order by name desc";
try{$courses=$pdo->query($sql)->fetchAll();}
catch(Exception $e){print_r ($e);}
//insert pupil
$name= filter_input(INPUT_POST,'name');
$momname=filter_input(INPUT_POST,'momname');
$tel= filter_input(INPUT_POST,'tel');
$bd= filter_input(INPUT_POST,'bd');
$comment= filter_input(INPUT_POST,'comment');
if($name<>null)
{
    $sql = "insert into pupil(name, momname, tel, bd, comment)"." values ('" .$name. "','".$momname."','".$tel."','".$bd."','".$comment."')";
try{$pdo->exec($sql);}
catch(Exception $e){print_r ($e);}
}
$pupil_id=$pdo->lastInsertId();
$course_id=filter_input(INPUT_POST, 'course_id');
if($course<>null)
{
    $sql = "insert into course_has_pupil(course_id, pupil_id)"." values ('" .$course_id. "','".$pupil_id."')";
    try{$rows=$pdo->exec($sql);}
    catch(Exception $e){print_r ($e);}
}
//delete pupil
$delname= filter_input(INPUT_POST,'delname');
if($delname<>null)
{$sql = "delete from pupil where name='".$delname."'";
    try{$rows=$pdo->exec($sql);}
    catch(Exception $e){print_r ($e);}
}
//edit pupil
$oldname= filter_input(INPUT_POST,'oldname');$newname= filter_input(INPUT_POST,'newname');
$oldmomname= filter_input(INPUT_POST,'oldmomname');$newmomname= filter_input(INPUT_POST,'newmomname');
$oldtel= filter_input(INPUT_POST,'oldtel');$newtel= filter_input(INPUT_POST,'newtel');
$oldbd= filter_input(INPUT_POST,'oldbd');$newbd= filter_input(INPUT_POST,'newbd');
$oldcomment= filter_input(INPUT_POST,'oldcomment');$newcomment= filter_input(INPUT_POST,'newcomment');
if($oldname<>null && $newname <> null)
{$sql = "update pupil set name='".$newname."',"
    ." momname='".$newmomname."',"
    ." tel='".$newtel."',"
    ." bd='".$newbd."',"
    ." comment='".$newcomment."'"
    . " where name='".$oldname."'";
    try{$rows=$pdo->exec($sql);}
    catch(Exception $e){print_r ($e->getMessage());}
}
//add new pupil
echo<<<TAG
Ученик
<form method="post" >
    <table class = "w3-table-small">
        <tr><td>ФИО:</td><td> <input  type = "text" name='name' /></td></tr>
        <tr><td>ФИО отв.:</td><td> <input  type = "text" name='momname'/></td></tr>
        <tr><td>Тел.:</td><td> <input  type="tel" name='tel'/></td></tr>
        <tr><td>ДР:</td><td> <input  type="date" name='bd'/></td></tr>
        <tr><td>Доп. инф.:</td><td> <input  type = "text" name='comment'/></td></tr>
        <tr><td colspan="2"><input type="submit" value="Добавить"></td></tr>
    </table>
</form>
TAG;
$pupils=$pdo->query("select * from pupil order by name")->fetchAll();
echo<<<TAG
<!--<div class="w3-responsive">-->
<table class="w3-table-all w3-small">
TAG;
 foreach($pupils as $pupil){
    $id=$pupil['id'];$name=$pupil['name'];$momname=$pupil['momname'];$tel=$pupil['tel'];$bd=$pupil['bd'];$comment=$pupil['comment'];
     $r=pupil_balanse($pdo,$id);
echo<<<TR
 <tr>  <td><form method="post">
           <div> ФИО $r<input type="hidden" name="oldname" value="$name"/>
            <input type="text" name="newname" value="$name"/></div>
            <div>ФИО отв.<input type="hidden" name="oldmomname" value="$momname"/>
            <input type="text" name="newmomname" value="$momname"/></div>
           <div>Тел. <input type="hidden" name="oldtel" value="$tel"/>
            <input type="text" name="newtel" value="$tel"/></div>
            <div>ДР<input type="hidden" name="oldbd" value="$bd"/>
            <input type="text" name="newbd" value="$bd"/></div>
            <div>Доп. инф.<input type="hidden" name="oldcomment" value="$comment"/>
            <input type="text" name="newcomment" value="$comment"/></div>
            <input type="submit" value="Сохранить">
            </form></td>
            <td><form method="post">
            <input type="hidden" name="delname" value="$name"/>
            <input type="submit" value="Удалить">
            </form><a href="pupil_has_courses.php?pupil_id=$id">Группы</a>
            <!--<a href="pupil_course_payments.php?pupil_id=$id">Посещения и платежи</a>-->
            </td>
            
</tr>

TR;
    }
echo<<<TAG
</table>
<!--</div>-->
TAG;
include 'assets/footer.php';