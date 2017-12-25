<?php
/**
 * 主機資訊 模組
 */
class Serverinfo_Tool extends CS_Tool{

    public function getIP(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $myip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $myip = $_SERVER['REMOTE_ADDR'];
        }
        return $myip;
    }
}
?>