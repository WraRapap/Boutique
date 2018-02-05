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


    private  function collectparm($mod,$brandIds,$totalparm,$table,$condition){
        if(!empty($brandIds)){
            if($mod==1){
                $totalparm["moreTabel"].=" ".$table." ";
                $totalparm["condititon"][]="$condition";
                $totalparm["condititonValue"][]=$brandIds;
            }else{
                $brandArr = explode(",",$brandIds);
                $brandIds="";
                foreach($brandArr as $key => $brandId){
                    if($key == count($brandArr)-1){
                        $brandIds.= " ".$condition." ";
                    }
                    else{
                        $brandIds.= " ".$condition." or ";
                    }
                    if($mod==2){
                        $totalparm["condititonValue"][]=$brandId;
                    }else{
                        $totalparm["condititonValue"][]=array('%',$brandId,'%');
                    }

                }
                $totalparm["condititon"][]="(".$brandIds.")";
            }
        }
        return $totalparm;
    }
//1.类别（包，鞋，服饰，时尚配饰）2.男/女//3.品牌//4.尺寸//5.颜色//6.价格//7关键字//上架时间（大小）//价钱（大小）
//SELECT * from cs_product p
//INNER JOIN v_category vc on p.id=vc.id //关联类别视图
//INNER JOIN cs_brand b on p.brand=b.id //品牌
//INNER JOIN cs_size_class s on CONCAT(':',p.size,':') like CONCAT('\'',s.id,'\'') //尺寸
//INNER JOIN cs_sex se on se.id=p.sex //性别
//INNER JOIN cs_color co on on CONCAT(':',p.size,':') like CONCAT('\'',co.id,'\'')//颜色
//where vc.categoryid=?
//and (p.brand = ? or )
//and (CONCAT(':',p.size,':') like ? or )
//and p.sex = ?
//and (CONCAT(':',p.color,':') like ? or )
//and p.price >= ?
//and p.price <= ?
//and (p.description like ? or p.name like ? or  b.title like ? or s.title like ? or se.title like ? or co.title like ? )//关键字
//and p.publish='Y'
// group by id
//order by p.createTime ?,
//order by p.price ?
	public function  product(){
        $perPageItems=empty($this->tool_io->get("count"))? 2:$this->tool_io->get("count");
        $currentPage=empty($this->tool_io->get("page"))? 1:$this->tool_io->get("page");
	    $totalparm=array();
        $totalparm["moreTabel"]="cs_product p";
        $totalparm["condititon"]=array("publish='Y'");
        $totalparm["condititonValue"]=array();
        $totalparm["sort"]=array();
        $totalparm["group"]=array();

	    $categoryId = $this->tool_io->get("c");//类别
        $totalparm = $this->collectparm(1,$categoryId,$totalparm,"INNER JOIN v_category vc on p.id=vc.id","vc.categoryid=?");//$mod,$brandIds,$totalparm,$table,$condition
        $brandIds = $this->tool_io->get("b");//品牌
        $totalparm = $this->collectparm(2,$brandIds,$totalparm,"","p.brand = ?");
        $sizeIds = $this->tool_io->get("s");//尺寸
        $totalparm = $this->collectparm(3,$sizeIds,$totalparm,"","CONCAT(':',p.size,':') like ?");
        $sexId = $this->tool_io->get("se");
        $totalparm = $this->collectparm(1,$sexId,$totalparm,"","p.sex = ?");
        $colorIds = $this->tool_io->get("co");
        $totalparm = $this->collectparm(3,$colorIds,$totalparm,"","CONCAT(':',p.color,':') like ?");
        $mixPrice = $this->tool_io->get("p1");
        $totalparm = $this->collectparm(1,$mixPrice,$totalparm,"","p.price >=");
        $maxPrice = $this->tool_io->get("p2");
        $totalparm = $this->collectparm(1,$maxPrice,$totalparm,"","p.price <=");

        $keyword=$this->tool_io->get("k");//关键字
        if(!empty($keyword)){
            $totalparm["moreTabel"].=" INNER JOIN cs_brand b on p.brand=b.id INNER JOIN cs_size_class s on CONCAT(':',p.size,':') like CONCAT('%',s.id,'%') ";
            $totalparm["moreTabel"].=" INNER JOIN cs_sex se on se.id=p.sex INNER JOIN cs_color co on CONCAT(':',p.color,':') like CONCAT('%',co.id,'%') ";
            $totalparm["condititon"][]="(p.description like ? or p.name like ? or  b.title like ? or s.title like ? or se.title like ? or co.title like ?)";
            $totalparm["condititonValue"][]=array('%',$keyword,'%');
            $totalparm["condititonValue"][]=array('%',$keyword,'%');
            $totalparm["condititonValue"][]=array('%',$keyword,'%');
            $totalparm["condititonValue"][]=array('%',$keyword,'%');
            $totalparm["condititonValue"][]=array('%',$keyword,'%');
            $totalparm["condititonValue"][]=array('%',$keyword,'%');
            $totalparm["group"][]="p.id";
        }

        $sort=$this->tool_io->get("so");
        if(isset($sort)){
            switch ($sort){
                case "4":
                    $totalparm["sort"][]="p.createTime";
                    break;
                case "3":
                    $totalparm["sort"][]="p.createTime desc";
                    break;
                case "1":
                    $totalparm["sort"][]="p.price";
                    break;
                case "2":
                    $totalparm["sort"][]="p.price desc";
                    break;
            }
        }

        $productlist = $this -> tool_database -> moreTableFindAll(
            $totalparm["moreTabel"],
            array("p.id","p.name","p.img","p.price"),
            $totalparm["condititon"],
            $totalparm["condititonValue"],
            $totalparm["sort"],
            $totalparm["group"],
            $perPageItems,
            true
        );//参数里的true代表会返回总数，默认不返回

        if(isset($_POST["rq"])){//ajax请求数据
            echo json_encode(array("data"=>$productlist[0]));
            return;
        }

        $colors = $this->tool_database->findAll("color");
        $brands = $this->tool_database->findAll("brand");

        $sizeCondition=array("parentID!=''");
        $sizeConditionValue = array();
        if(isset($_GET["c"])){
            $sizeCondition[]="parentID=?";
            $sizeConditionValue = array($this->tool_io->get("c"));
        }

        $sizes = $this->tool_database->findAll(
            "size_class",
             array("id","title"),
            $sizeCondition,
            $sizeConditionValue
        );

        $datas = array("productlist"=>$productlist[0],
                        "totalItems"=>$productlist[1],
                        "perPageItems"=>$perPageItems ,
                        "colors"=>$colors ,
                        "brands"=>$brands ,
                        "sizes"=>$sizes ,
                        "currentPage"=>$currentPage);
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