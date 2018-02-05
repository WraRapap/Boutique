<?php
class Api_Controller extends CS_Controller{

	public function main(){

	}
    public function fileupload(){
        foreach($_FILES as $key => $fileObj){

            $files = $this -> tool_file -> upload_to_temp($key);
            foreach($files as $file){

                $tempfile = array(
                    "id" => uniqid(),
                    "path" => $file -> path,
                    "fileName" => $file -> name,
                    "size" => $file -> size,
                    "ext" => $file -> ext,
                    "createTime" => date("Y-m-d H:i:s")
                );

                echo base64_encode(json_encode($tempfile));

                exit;
            }
        }
    }
    public function fileDelete(){
        $object = $this -> tool_io -> post("object");
        $object = json_decode(base64_decode($object));



        $source = (json_decode(base64_decode($this -> tool_io -> post("signed"))));

        if($source == ""){
            return;
        }

        $this -> tool_file -> delete($object -> path);

        $variable = $this -> tool_io -> post("variable");

        if($source -> id != "" && $variable != ""){

            $item = $this -> tool_database -> find(
                $source -> data_source,
                array(),
                array("id=?"),
                array($source -> id)
            );

            if($item -> isExists()){

                $fileJSON = $item -> {$variable};
                $files = json_decode($fileJSON);
                if($files == ""){
                    $files = array();
                }
                $newFiles = array();
                foreach($files as $file){
                    if($file -> id == $object -> id){
                        continue;
                    }
                    $newFiles[] = $file;
                }

                $item -> {$variable} = json_encode($newFiles);
                $item -> update();
            }
        }

        echo json_encode(array("result" => "yes"));
    }

    public  function signin(){
        if($this->tool_io->post("email")==""){
            echo json_encode( array("msg" => "電子信箱必填"));
            return;
        }
        if($this->tool_io->post("account")==""){
            echo json_encode( array("msg" => "姓名必填"));
            return;
        }
        if($this->tool_io->post("password")==""){
            echo json_encode( array("msg" => "密碼必填"));
            return;
        }
	    if($this->tool_io->post("password")!=$this->tool_io->post("confirmpwd")){
            echo json_encode( array("msg" => "密碼 與 確認密碼不符"));
            return;
        }

        $member = $this -> tool_database -> find(
            "member",
            array(),
            array("email=?"),
            array($this->tool_io->post("email"))
        );

        if($member -> isExists()){
            echo json_encode(array("msg"=>"電子信箱已被註冊"));
            return;
        }

        $member = $this -> tool_database -> emptyRecord("member");

        $member -> id = uniqid();
        $member -> name = $this -> tool_io -> post("account");
        $member -> email = $this -> tool_io -> post("email");
        $member -> mt4 = $this -> tool_io -> post("mt4");
        $member -> password = $this -> tool_io -> post("password");
        $member -> createTime = date("Y-m-d H:i:s",time());
        $member -> insert();



        echo json_encode( array("status" => 1));
    }

    public  function login(){
        $email = $this -> tool_io -> post("email");
        $password = $this -> tool_io -> post("password");

        $member = $this -> tool_database -> moreTableFind(
            "cs_member",
            array(),
            array("email=?","password=?"),
            array($email, $password)
        );

        if(empty($member["id"])){
            echo json_encode(array("msg"=>"賬號或者密碼不對"));
            return;
        }

        $cart = $this->tool_database -> find(
            "cart",
            array(),
            array("memberId=?"),
            array($member["id"])
        );

        @session_start();
        $_SESSION["USER_ID"] = $member["id"];
        $_SESSION["USER_NAME"] = $member["name"];
        $_SESSION["USER"] = $member;

        $_SESSION["USER_CARTNUM"] = count((array)json_decode($cart->cart));
        @session_write_close();
        echo json_encode(array("status"=>1));
        return ;
    }

    public  function  delCartProduct(){
	    $cart = $this->tool_database->find(
	        "cart",
            array(),
            array("memberId=?"),
            array($_SESSION["USER_ID"])
        );

	    if(!$cart->isExists()){
	        echo json_encode(array("msg"=>"找不到指定商品"));
	        return;
        }

        $flag=false;
        $carts=json_decode($cart->cart);
        $lstcarts=array();
        foreach ($carts as $pro) {
            if($pro->uid==$this->tool_io->post("uid")){
                $flag=true;
                continue;
            }
            $lstcarts[]=$pro;
	    }

	    if(!$flag){
            echo json_encode(array("msg"=>"找不到指定商品"));
            return;
        }

	    if(count($carts)>0){
            $cart->cart = json_encode($lstcarts,JSON_UNESCAPED_UNICODE);
            $cart->update();
        }else{
            $cart->delete();
        }

        $_SESSION["USER_CARTNUM"]-=1;

        echo json_encode(array("status"=>1));
    }
}
?>