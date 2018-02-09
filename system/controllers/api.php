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
        if(!isset($_SESSION["USER_ID"])){
             echo json_encode(array("status"=>-1));
             return;
        }

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

        @session_start();
        $_SESSION["USER_CARTNUM"]-=1;
        @session_write_close();
        echo json_encode(array("status"=>1));
    }

    public  function confirmOrder(){
        if(!isset($_SESSION["USER_ID"])){
            echo json_encode(array("status"=>-1));
            return;
        }

	    $products = (array)json_decode($_POST["products"]);
	    if(count($products)<1){
	        echo json_encode(array("msg"=>"購物車是空的哦"));
	        return ;
        }

        $flag="";
	    $totalFee=0;
        foreach ($products as $product){
	        $p = $this->tool_database->find("product",array(),array("publish='Y'","id=?"),array($product->id));
	        if($p->id==""){
               $flag=$product->id;
               break;
            }
            $product->price=$p->price;
            $product->count="1";
            $totalFee = $totalFee + $p->price;
	    }
	    if(!empty($flag)){
            echo json_encode(array("status"=>0,"msg"=>"購物車里有商品已經下架了哦，麻烦重新确认","id"=>$flag));
            return ;
        }

        $actions = array();
        //订单模块
        $order = $this -> tool_database -> emptyRecord("order");
        $verifyParm=array("name","email","phone","recName","recPhone","areacode","city","address","country","delivery","payment");
        $emptyParm="";
        foreach ($verifyParm as $parm){
            $lparm=strtolower($parm);
            if(empty($this->tool_io->post($parm))){
                $emptyParm=$parm;
                break;
            }
            $order->$lparm= $this -> tool_io -> post($parm);
        }
        if(!empty($emptyParm)){
            echo json_encode(array("status"=>2,"id"=>$emptyParm));
            return ;
        }

        $order -> id = uniqid();
        $order -> item = "PH".date("YmdHis",time()).uniqid();
        $order -> createTime = date("Y-m-d H:i:s",time());
        $order -> cart = json_encode($products,JSON_UNESCAPED_UNICODE);
        $order-> orderstatus= "15a5714ace2518";
        $order-> remark = $this->tool_io->post("remark");
        $order-> totalfee = $totalFee;
        $order-> memberId = $_SESSION["USER_ID"];
        $actions[]=array("1", $order);

        //会员模块，补全会员信息
        if(empty($_SESSION["USER"]["phone"]) ||empty($_SESSION["USER"]["country"]) ||empty($_SESSION["USER"]["address"])){
            $member = $this -> tool_database -> find("member",array(),array("id=?"),array($_SESSION["USER_ID"]));
            if(empty($_SESSION["USER"]["phone"])){
                $member->phone=$order->phone;
            }
            if(empty($_SESSION["USER"]["country"])){
                $member->country=$order->country;
            }
            if(empty($_SESSION["USER"]["address"])){
                $member->address=$order->address;
            }

            $actions[]=array("2", $member);
        }

        //清空购物车
        $actions[]=array("4","delete from cs_cart where memberId=?",array($_SESSION["USER_ID"]));

        //提交事务
        if($this->tool_database->transaction($actions)){
            @session_start();
            if(empty($_SESSION["USER"]["phone"])){
                $_SESSION["USER"]["phone"]=$order->phone;
            }
            if(empty($_SESSION["USER"]["country"])){
                $_SESSION["USER"]["country"]=$order->country;
            }
            if(empty($_SESSION["USER"]["address"])){
                $_SESSION["USER"]["address"]=$order->address;
            }

            $_SESSION["USER_CARTNUM"]=0;
            @session_write_close();
            echo json_encode(array("status"=>1));
        }
        else{
            echo json_encode(array("msg"=>"訂單送出失敗"));
        }
    }

    public  function member(){
        if(!isset($_SESSION["USER_ID"])){
            echo json_encode(array("status"=>-1));
            return;
        }

	    $parms=array("name,姓名","country,國家","address,地址","phone,手機");
	    $requireFiled="";
	    foreach ($parms as $parm){
            $arr = explode(",",$parm);
	        if(empty($this->tool_io->post($arr[0]))){
                $requireFiled=$arr[1];
                break;
            }
        }
        if(!empty($requireFiled)){
	        echo json_encode(array("msg"=>$requireFiled."必填"));
	        return ;
        }

        $condition=array("id=?");
	    $conditionValue=array($_SESSION["USER_ID"]);
        $hasPwd=false;
	    if(!empty($this->tool_io->post("oldPwd")) || !empty($this->tool_io->post("newPwd")) || !empty($this->tool_io->post("reNewPwd"))){

	        if(empty($this->tool_io->post("oldPwd")) || empty($this->tool_io->post("newPwd")) || empty($this->tool_io->post("reNewPwd"))){
                echo json_encode(array("msg"=>"密碼必填"));
                return ;
            }

            if($this->tool_io->post("newPwd") != $this->tool_io->post("reNewPwd")){
                echo json_encode(array("msg"=>"新密碼兩次不一致"));
                return ;
            }
            $hasPwd=true;
            $condition[]="password=?";
            $conditionValue[]=$this->tool_io->post("oldPwd");
        }



        $member =  $this->tool_database->find("member",array(),$condition,$conditionValue);
	    if($member->id ==""){
	        if($hasPwd){
                echo json_encode(array("msg"=>"舊密碼錯誤"));
                return ;
            }else{
                echo json_encode(array("msg"=>"请重新登录"));
                return ;
            }

        }

        $member->phone=$this->tool_io->post("phone");
        $member->country=$this->tool_io->post("country");
        $member->address=$this->tool_io->post("address");
        $member->name=$this->tool_io->post("name");

        if($hasPwd){
            $member->password=$this->tool_io->post("newPwd");
        }

        $member->update();

        @session_start();
        $_SESSION['USER_NAME']=$this->tool_io->post("name");
        $_SESSION['USER']=$this->tool_database->moreTableFind("cs_member",array(),array("id=?"),array($_SESSION["USER_ID"]));
        @session_write_close();
        echo json_encode(array("status"=>1));
    }

    public  function addLike(){
        if(!isset($_SESSION["USER_ID"])){
            echo json_encode(array("status"=>-1));
            return;
        }
	    if(empty($this->tool_io->post("i"))){
	        echo json_encode(array("msg"=>"商品不存在"));
	        return ;
        }

	    $like  = $this->tool_database->find("likeorder",array(),array("memberId=?"),array($_SESSION["USER_ID"]));
	    $products =  (array)json_decode($like->cart);
	    $exist=false;
	    foreach ($products as $key =>$product){
	        if($product->id == $this->tool_io->post("i")){
                $exist=true;
                break;
            }
        }

        if($exist){
            echo json_encode(array("status"=>1));
            return ;
        }

        $products[]=array("id"=>$this->tool_io->post("i"));
        $like->cart = json_encode($products);
        if($like->id!=""){
            $like->update();
        }else{
            $like->id=uniqid();
            $like->memberId=$_SESSION["USER_ID"];
            $like->insert();
        }
        echo json_encode(array("status"=>1));
    }

    public function delLike(){
        if(!isset($_SESSION["USER_ID"])){
            echo json_encode(array("status"=>-1));
            return;
        }
        if(empty($this->tool_io->post("i"))){
            echo json_encode(array("msg"=>"商品不存在"));
            return ;
        }

        $like  = $this->tool_database->find("likeorder",array(),array("memberId=?"),array($_SESSION["USER_ID"]));
        $products =  (array)json_decode($like->cart);
        $lstProducts=array();
        foreach ($products as $product){
            if($product->id != $this->tool_io->post("i")){
                $lstProducts[]=$product;
            }
        }

        if($like->id!=""){
            $like->cart=json_encode($lstProducts);
            $like->update();
        }
        echo json_encode(array("status"=>1));
    }
}
?>