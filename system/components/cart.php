<?php
class Cart_Component extends CS_Component{

	/** 
	 * 假設訂單有一個欄位名稱為 cart 的欄位
	 * 購買品項請用 json_encode(array(
	 *	array("title" => "商品名稱1", "count" => 10, "price" => 100, "spec" => "S"),
	 *  array("title" => "商品名稱2", "count" => 5, "price" => 200, "spec" => "X"),
	 *	));
     *
     * 會以 JSON 格式儲存在該筆訂單的cart 欄位中。下方的function 會將 JSON抓出來之後，秀出訂單中的購物明細
     * 再請您看一下，以及微調一下！感謝
	 */
    public function render($attrs = array()){
        
				
        $items = $this -> getValue();
		if(is_string($items)){
			$items = json_decode($items);
		}
		$rows = "";
		if(is_array($items)){
            foreach($items as $item){
                $product = $this->tool_database->moreTableFind(
                    "cs_product p inner join cs_color c on c.id='".$item -> color."' inner join cs_size_class s on s.id='".$item -> size."'",
                    array("p.name","c.title color","s.title size","p.price"),
                    array("p.id=?"),
                    array($item->id)
                );

                $rows .= "<tr>
						<td style=\"border:1px #ccc solid;padding:10px 10px;\"><a href='../item.html?i=".$item->id."'>" . $product['name']."</a></td>
							<td style=\"border:1px #ccc solid;padding:0px 10px;\">" .$product['color'] . "</td>
								<td style=\"border:1px #ccc solid;padding:0px 10px;\">" . $product['size'] . "</td>
						<td style=\"border:1px #ccc solid;padding:0px 10px;\">" . $item -> count . "</td>
						<td style=\"border:1px #ccc solid;padding:0px 10px;\">" . $product['price'] . "</td>
							<td style=\"border:1px #ccc solid;padding:0px 10px;\">" . $product['price']*$item -> count . "</td>
					</tr>";
            }
        }

        echo "<table style=\"width:100%;border:1px #ccc solid;\">
    	           	<tr>
                		<td style=\"border:1px #ccc solid;text-align:center;font-weight:bold;\">產品名稱</td>
                		<td style=\"border:1px #ccc solid;text-align:center;font-weight:bold;\">顏色</td>
                		<td style=\"border:1px #ccc solid;text-align:center;font-weight:bold;\">尺寸</td>
                		<td style=\"border:1px #ccc solid;text-align:center;font-weight:bold;\">數量</td>
                		<td style=\"border:1px #ccc solid;text-align:center;font-weight:bold;\">單價</td>
                		<td style=\"border:1px #ccc solid;text-align:center;font-weight:bold;\">金额</td>
                	</tr>
                	" . $rows . "
                </table>";
    }

	public function setValue($value){
		$this -> value = json_encode($value);
	}
}
?>