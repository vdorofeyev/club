<?php
$DEBUG=true;
define('DBHOST', 'localhost');
define('DBNAME','pudelart_club');
define('DBUSER','pudelart_club');
define('DBPASSWORD', 'st090305');
define('PRICE', 500);
$opt = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);
try
{
    $pdo = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME, DBUSER, DBPASSWORD, $opt);
}
catch (Exception $e) {
    var_dump($e->getMessage());
}

function pupil_balanse($pdo, $pupil_id)
{
     $sql="select  sum(sum) as sum from pupil_attended_date where  pupil_id=".$pupil_id;
    $total_sum=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC)['sum'];
    $sql="select  count(*) as count from pupil_attended_date where replace(status,' ','')='ok' and  pupil_id=".$pupil_id;
    $count=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC)['count'];
    $s=$total_sum/PRICE;$s-=$count;
    $r="(".$total_sum."/".PRICE."-".$count."=".$s.")";
return $r;
}
//print_r(pupil_balanse($pdo));
function count_pupil_attended_course($pdo, $pupil_id, $course_id)
{
    $sql="select count(*) as count from pupil_attended_date join date on pupil_attended_date.date_id = date.id"
        ." where pupil_id=".$pupil_id." and course_id=".$course_id;
    $r=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC)['count'];
    return $r;
}
//print_r(count_pupil_attended_course($pdo, 15, 17));
function total_sum($pdo, $date_from, $date_to)
{
    $sql="select sum(sum) as sum from date join pupil_attended_date on date.id = pupil_attended_date.date_id"
        ." where date>='".$date_from."' and date <= '".$date_to."'";
    $r=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC)['sum'];
    return $r;
}
function pupil_course_dates_sum($pdo, $pupil_id, $course_id, $date_from, $date_to)
{
    $sql="select sum(sum) as sum from date join pupil_attended_date on date.id = pupil_attended_date.date_id"
        ." where date>='".$date_from."' and date <= '".$date_to."'";
    if($pupil_id>0)$sql.=" and pupil_id=".$pupil_id;
    if($course_id>0)$sql.=" and course_id=".$course_id;
     $sum=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC)['sum'];
    $sql="select count(*) as count from date join pupil_attended_date on date.id = pupil_attended_date.date_id"
        ." where replace(status,' ','')='ok' and date>='".$date_from."' and date <= '".$date_to."'";
    if($pupil_id>0)$sql.=" and pupil_id=".$pupil_id;
    if($course_id>0)$sql.=" and course_id=".$course_id;
    $count=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC)['count'];
    return $sum."/".$sum/PRICE." = ".$count*PRICE."/".$count
       ." + ".PRICE*($sum/PRICE-$count)."/".($sum/PRICE-$count);
}