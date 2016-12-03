<?php

require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    echo json_encode("danied");
} else {
    if ($_POST["req"] == "umis") {
        $product = new product();
        $arr_out = $product->getProductUMis();
        echo json_encode($arr_out);
    }
}
?>