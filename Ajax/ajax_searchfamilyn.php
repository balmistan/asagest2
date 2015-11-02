<?php
require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}

if(!isset($_SESSION["lastdateupdate"]) || $_SESSION["lastdateupdate"] != config::getConfig("lastdateupdate", "internalconfig")){

$family=new Family();
$arr_options = array(array("on", "person.family_register_number", "=", "family.family_register_number", true));
$arr_ret=$family->getForTable($arr_options);
$arr_temp = array();

for($i=0; $i<count($arr_ret); $i++){
   
    $arr_temp[] = array(
        $arr_ret[$i]["surname"],
        $arr_ret[$i]["name"],
        $arr_ret[$i]["born_date"],
        $arr_ret[$i]["address"],
        $arr_ret[$i]["district_id"],
        $arr_ret[$i]["family_register_number"],
        $arr_ret[$i]["state"],
        $arr_ret[$i]["search_result"],
    );
}

$_SESSION["lastdateupdate"] = config::getConfig("lastdateupdate", "internalconfig"); //Viene settata solo in questo punto e quì setto anche out subito dopo

$_SESSION["out"] = $out = json_encode($arr_temp);

}else{
    $out = $_SESSION["out"];
}

echo '{"aaData": '.$out.'}';
?>
