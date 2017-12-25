<?php
class Admin_Model extends CS_Model{

    public function doLogin(){
        $account = $this -> tool_io -> post("account");
        $password = $this -> tool_io -> post("password");


        $data_sources = array("sys_admin");
        $authorities = array("");

        $logins = $this -> tool_database -> findAll(
            "sys_member",
            array("id","data_source", "authority"),
            array("admin_login='Y'")
        );

        foreach($logins as $login){
            $data_sources[] = $login -> data_source;
            $authorities[] = $login -> authority;
        }


        $authority = "";
        foreach($data_sources as $key => $data_source){
            $member = $this -> tool_database -> find(
                $data_source,
                array(),
                array("account=?","password=?"),
                array($account, $password)
            );

            if($member -> isExists()){

                if(isset($member -> authority) && $member -> authority != ""){
                    break;
                }

                $member -> authority = "";

                $authorityID = $authorities[$key];
                if($authorityID == ""){
                    break;
                }

                $authorityObject = $this -> tool_database -> find(
                    "sys_authority",
                    array(),
                    array("id=?"),
                    array($authorityID)
                );

                if($authorityObject -> isExists()){
                    $member -> authority = $authorityObject -> authority_setting;
                }
                break;
            }
        }

        if(!$member -> isExists()){
            return false;
        }

        @session_start();
        $_SESSION[$_SERVER["HTTP_HOST"] . "_adminID"] = $member -> id;
        $_SESSION[$_SERVER["HTTP_HOST"] . "_adminAuthority"] = $member -> authority;
        @session_write_close();
        return true;

    }

    public function checkLogin(){
        @session_start();
        $value = isset($_SESSION[$_SERVER["HTTP_HOST"] . "_adminID"]);
        @session_write_close();
        return $value;
    }

    public function checkLogout(){
        $page_id = $this -> tool_io -> get("page_id");
        if(isset($page_id)){

            $page_class = $this -> tool_database -> find(
                "sys_page_class",
                array(),
                array("id=?"),
                array($page_id)
            );

            if($page_class -> isExists() && $page_class -> logout == "Y"){
                unset($_SESSION[$_SERVER["HTTP_HOST"] . "_adminID"]);
                unset($_SESSION[$_SERVER["HTTP_HOST"] . "_adminAuthority"]);
                return true;
            }
        }

        return false;
    }

    public function getInformation(){
        return $this -> tool_database -> find("sys_information");
    }

    public function getMenu(){
        $menuRes = $this -> tool_database -> find("sys_menu");
        $menus = json_decode($menuRes -> menu);

        if($menus == ""){
            return (object) $menus;
        }

        $authority = array();

        // 濡傛湁娆婇檺瑷畾
        @session_start();
        if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_adminAuthority"])){
            $authority = $this -> refineAuthorityObject(json_decode($_SESSION[$_SERVER["HTTP_HOST"] . "_adminAuthority"]));
        }
        @session_write_close();

        foreach($menus as $key => $menu){
            // 鏈夊瓙闋呯洰
            if(isset($menu -> items) &&
                is_array($menu -> items) &&
                count($menu -> items) > 0){

                foreach($menu -> items as $i_key => $item){

                    // 濡傛湁娆婇檺绠＄悊
                    if(count($authority) > 0){
                        if(!array_key_exists($item -> page, $authority)){
                            unset($menus[$key] -> items[$i_key]);
                            continue;
                        }
                    }

                    $page = $this -> tool_database -> find(
                        "sys_page_class",
                        array("title"),
                        array("id=?"),
                        array($item -> page)
                    );

                    $menus[$key] -> items[$i_key] -> title = $page -> title;
                }

            }

            if(count($menus[$key] -> items) == 0){
                unset($menus[$key]);
            }
        }


        return $menus;

    }

    public function getPageInfo(){

        $pageID = $this -> tool_io -> get("page_id");

        if(!isset($pageID) || $pageID == ""){

            $page_class = $this -> tool_database -> find(
                "sys_page_class",
                array("id"),
                array("landing='Y'")
            );

            if($page_class -> isExists()){
                $pageID = $page_class -> id;
                $_GET["page_id"] = $pageID;
            }
            else{
                return array();
            }
        }

        $pages = $this -> tool_database -> findAll(
            "sys_page",
            array(),
            array("parentID=?"),
            array($pageID),
            array("sortTime ASC", "sequence ASC", "createTime DESC")
        );

        return (object)$pages;

    }


    public function getPageTitle(){

        $pageID = $this -> tool_io -> get("page_id");

        if(!isset($pageID) || $pageID == ""){
            $page_class = $this -> tool_database -> find(
                "sys_page_class",
                array("id", "title"),
                array("landing='Y'")
            );
        }
        else{
            $page_class = $this -> tool_database -> find(
                "sys_page_class",
                array("id", "title"),
                array("id=?"),
                array($pageID)
            );
        }

        return ($page_class -> isExists()) ? $page_class -> title : "";

    }

    private function refineAuthorityObject($authorityObject){

        if($authorityObject == ""){
            return array();
        }

        $createArray = array();

        foreach($authorityObject as $object){
            $array = get_object_vars($object);
            foreach($array as $key => $value){
                if($value == array()) continue;
                $createArray[$key] = $value;
            }
        }

        return $createArray;
    }

}
?>
