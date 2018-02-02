<?php
class Website_Controller extends WebsiteController{

	public function main($action){
		$this -> loadView($action);
	}

    public function index()
    {
        $brands = $this->tool_database->findAll(
            "brand",
            array(),
            array(),
            array(),
            array("sequence")
        );
        $this ->display("index",array("brands"=>$brands));
    }

    public function signin()
    {
        $this ->display("signin");
    }

    public function login()
    {
        $this ->display("login");
    }

	public function logout(){
		@session_start();
		unset($_SESSION["USER_ID"]);
		unset($_SESSION["USER_NAME"]);
        unset($_SESSION["USER"]);
        unset($_SESSION["USER_CARTNUM"]);
		@session_write_close();

		$this -> tool_go -> page("/index.php/");
	}

	public function  product(){
        $productlist = $this -> tool_database -> moreTableFindAll(
            "cs_product",
            array("id","name","cheapest","img","price"),
            array("publish='Y'")
        );

        $datas=array("productlist"=>$productlist);
        $this ->display("product",$datas);
    }

    public  function  item(){
	    //商品详情
        $product = $this -> tool_database -> moreTableFind(
            "cs_product c inner join cs_brand b on c.brand=b.id",
            array("*","b.title brandname"),
            array("c.publish='Y'","c.id=?"),
            array($this->tool_io->get("i"))
        );

        if(count($product)<= 0){
            exit("商品信息不存在！");
        }

        //商品颜色
        $product["color"] = "'".str_replace(":","','",$product["color"])."'";
        $colors = $this->tool_database->moreTableFindAll(
            "cs_color",
            array(),
            array("id in (".$product["color"].")"),
            array()
        );

        //商品尺寸
        $product["sizes"] = "'".str_replace(":","','",$product["size"])."'";
        $sizes = $this->tool_database->moreTableFindAll(
            "cs_size_class",
            array(),
            array("id in (".$product["sizes"].")"),
            array()
        );

        $datas=array("product"=>$product,
                      "sizes"=>$sizes,
                      "colors"=>$colors);
        $this ->display("item",$datas);
    }

    public  function  cart(){
        $this->checkLogin("cart");
        //查找用户已存在的购物车
        $cart = $this -> tool_database -> find(
            "cart",
            array(),
            array("memberId=?"),
            array($_SESSION["USER_ID"])
        );

	    if(isset($_GET["i"])){
            $product = $this->tool_database->find(
                "product",
                array(),
                array("id=?","concat(':',color,':') like ?","concat(':',size,':') like ?"),
                array($this->tool_io->get("i"),array('%',$this->tool_io->get("c"),'%') ,array('%',$this->tool_io->get("s"),'%'))
            );

            if(empty($product->id)){
                exit("商品信息不存在");
            }

            //收集用户点击加入购物车的商品信息
            $item = array("uid"=>uniqid(),//购物车商品唯一标示
                "id"=>$this->tool_io->get("i"),
                "color"=>$this->tool_io->get("c"),
                "size"=>$this->tool_io->get("s"),
                "price"=>$this->tool_io->get("p"),
                "count"=>"1");

            //购物车不存在新增，反之叠加
            if($cart->id!=""){
                $carts = json_decode($cart->cart);
                $carts[]=$item;
                $cart->cart=json_encode($carts,JSON_UNESCAPED_UNICODE);
                $cart->update();
            }else{
                $cart -> id = uniqid();
                $cart->memberId=$_SESSION["USER_ID"];
                $cart->name=$_SESSION["USER_NAME"];
                $cart->email=$_SESSION["USER"]["email"];
                $cart->cart= json_encode(array($item));
                $cart -> createTime = date("Y-m-d H:i:s",time());
                $cart->insert();
            }
        }

        //读取购物车每条商品的详细数据
        $products = (array)json_decode($cart->cart);
        $lateProducts=array();//未下架的商品(购物车存放久了，可能有下架的)
        $lstProducts=array();
        $totalcount=0;
        $totalfee=0;
        foreach ($products as $p){
            $pr = $this->tool_database->moreTableFind(
                "cs_product p inner join cs_brand b on p.brand=b.id inner join cs_color c on c.id=? inner join cs_size_class s on s.id=?",
                array("p.id,p.item,b.title brandname","c.title color","s.title size","p.img","p.price","p.name"),
                array("p.id=?","p.publish='Y'"),
                array($p->color,$p->size,$p->id)
            );

            //找出未下架的商品
            if(count($pr)>0){
                $lateProducts[]=$p;

                $pr["count"]=$p->count;
                $pr["uid"]=$p->uid;
                $pr["img"]= json_decode($pr["img"])[0]->path;

                $totalfee+=$pr["price"];
                $totalcount+=$p->count;

                $lstProducts[]=$pr;
            }
        }

        $cart->cart=json_encode($lateProducts,JSON_UNESCAPED_UNICODE);
        $cart->update();

        $_SESSION["USER_CARTNUM"] =count($lstProducts) ;
        $datas=array("cart"=>$lstProducts,"totalfee"=>$totalfee,"totalcount"=>$totalcount);
        $this ->display("cart",$datas);
    }

	public function script(){

		header("Content-type: application/x-javascript");
		
		$parts = explode(".",basename($_SERVER['HTTP_REFERER']));
		$viewName = $parts[0];
		if($viewName=="www"){
			$viewName = "index";
		}

		$jscontent=array();
        $jscontent[] = $this -> config_env -> basePath . $this -> config_env -> websitePath . "/js/common.js";//公共js類
        $jscontent[] = $this -> config_env -> basePath . $this -> config_env -> websitePath . "/controllers/" . $viewName . ".js";//业务逻辑js类

        foreach ($jscontent as $content){
            if(file_exists($content)){
                $content = file_get_contents($content);
                echo $this -> tool_jspacker -> encode($content);
            }
        }

	}

	public function checkLogin($desti){
		@session_start();
		if(!isset($_SESSION["USER_ID"])){
			$this -> tool_go -> page("/index.php/login.html?desti=".$desti."&".$_SERVER['QUERY_STRING']);
		}
		@session_write_close();
	}

}
?>