<?php
/**
 * 記錄模組
 */
class Logger_Tool extends CS_Tool{

        /**
         * 每次載入此模組時檢查是否有過期的 log 檔，超過期限則刪除
         */
        public function __construct() {
           $logPath = $this -> config_env -> basePath . $this -> config_env -> logPath;
                
            $files = scandir($logPath . "/");
                
            foreach ($files as $file) {
                if (in_array($file, array(".", ".."))) {
                    continue;
                }
                $date = substr($file, 0, 10);
                $base_time = strtotime("-7 days");
                $file_time = strtotime($date);

                if ($base_time > $file_time) {
                    $filename = $logPath . "/" . $file;
                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                }
            }
        }


        /**
         * 記錄log
         * @param string $message 記錄訊息
         * @param string $name 記錄名稱
         */
        public function log($message, $name = "log") {
                
            $logPath = $this -> config_env -> basePath . $this -> config_env -> logPath;
            $date = date("Y-m-d");
            $time = date("H:i:s");
			$ip = " - " . $this -> tool_serverinfo -> getIP();

            file_put_contents($logPath . "/" . $date . "-" . $name . ".log", "[" . $date . " " . $time . $ip . "] " . $message . "\r\n", FILE_APPEND | LOCK_EX);
            unset($date);
            unset($time);
        }

}
?>