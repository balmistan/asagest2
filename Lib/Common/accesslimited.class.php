<?php

require_once 'db.class.php';

class accesslimited {

    /**
     * Incrementa il numero di tentativi falliti e attiva il captcha se necessario
     */
    public static function LoginFailed($userid, $maxfailed = 3) {
        $db = new db();
        $session=new session();
        $result = $db->getRow($session->getSessionVar('prefix')."users", array("captcha", "loginsfailed"), array(
            array("WHERE", "userId", "=", $userid, true),
                ));
        if ($result["captcha"])
            return;

        if ($result["loginsfailed"] >= $maxfailed - 1) {

            $db->update($session->getSessionVar('prefix')."users", array("captcha" => 1), array(
                array("WHERE", "userId", "=", $userid, true),
            ));
        }
        //inserisco il controllo <100 in modo da non permettere il reset per overflow dell' attr db loginsfailed
        if ($result["loginsfailed"] < 100)
            $db->update($session->getSessionVar('prefix')."users", array("loginsfailed" => (1 + $result["loginsfailed"])), array(
                array("WHERE", "userId", "=", $userid, true),
            ));
    }

    /**
     * disattivo il captcha
     */
    public static function resetCaptcha($userid) {
        $db = new db();
        $session=new session();
        $db->update($session->getSessionVar('prefix')."users", array("captcha" => 0), array(
            array("WHERE", "userId", "=", 1, true),
        ));
        $db->update($session->getSessionVar('prefix')."users", array("loginsfailed" => 0), array(
            array("WHERE", "userId", "=", 1, true),
        ));
    }

    /**
     * testa se va attivato il captcha
     */
    public static function useCaptcha($userid) {

        $db = new db();
        $session=new session();
        $res = $db->getRow($session->getSessionVar('prefix')."users", "captcha", array(
            array("WHERE", "userId", "=", $userid, true),
                ));
        if (!count($res))
            return false;
        return $res["captcha"];
    }

    /**
     * Permette di controllare la validità di un captcha
     * @return unknown_type
     */
    public static function checkCaptcha($value) {
        @session_start();
        if (isset($_SESSION['code']) && $_SESSION['code'] == $value)
            return true;
        return false;
    }

    public static function isInAutorizedGroups($username, $groups) {
        
        //Se l' utente è disattivato torno false 
        
        $user=new user();
        
        if(!$user->checkIfActiveUser($username))
            return false;
        
        
        if (is_array($groups))
            $arr_autorized_groups = $groups;
        else
            $arr_autorized_groups[] = $groups;
        

        //ottengo l' array dei gruppi a cui l' utente loggato appartiene.

        $arr_groups_tmp = group::groupsOf($username);

        $arr_groups = array();

        for ($i=0; $i<count($arr_groups_tmp); $i++) {
            $arr_groups[] = $arr_groups_tmp[$i]["groupName"];
        }

        if (count(array_intersect($arr_autorized_groups, $arr_groups)) > 0)
            return true;

        return false;
    }

}

?>