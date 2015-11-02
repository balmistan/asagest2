<?php
require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}

function getDataLimitByAge($age){
    $age=intval($age, 10);
    $date=date("Y-m-d");
    if($age>0){
        $Years=intval(substr($date, 0, 4), 10);
        $Years-=$age;
        $date=$Years.substr($date, 4);
    }
    return $date;
}

$family=new Family();

$arr_options=array(
array("on", "person.family_register_number", "=", "family.family_register_number", true),
array("and", "state", "<>", "deleted")
);

$arr_ret=$family->getForTable2($arr_options);

echo '{"aaData": '.json_encode($arr_ret).'}';
?>
