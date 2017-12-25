<?php
/**
 * 資料存取元件
 */
class CS_Record extends CS_Tool implements IteratorAggregate{
	/**
	 * @var 資料列
	 */
	protected $data = array();

	/**
	 * @var 資料表名稱
	 */
	protected $tableName = "";

	public function __set($name, $value) {
		if (method_exists($this, "set_" . $name)) {
			$this -> data[$name] = $this -> {"set_".$name}($value);
		} else {
			$this -> data[$name] = $value;
		}
	}

	public function __get($name) {

		$object = parent::__get($name);
		if(!is_null($object)){
			return $object;
		}

		if (method_exists($this, "get_" . $name)) {
			return $this -> {"get_".$name}((isset($this -> data[$name])) ? $this -> data[$name] : null);
		} else {
			return (isset($this -> data[$name])) ? $this -> data[$name] : null;
		}
	}

	public function __isset($name) {
		return isset($this -> data[$name]);
	}

	public function __unset($name) {
		unset($this -> data[$name]);
	}

	public function __construct($tableName, $data = null) {
		$this -> tableName = $tableName;
		if (isset($data)) {
			$this -> data = $data;
		}
	}

	public function isKeyExists($key){
		return array_key_exists($key,$this -> data);
	}

	public function getIterator() {
        $o = new ArrayObject($this->data);
        return $o->getIterator();
    }


	/**
	 * 新增資料
	 * @return string | int 此筆記錄新增的編號
	 */
	public function insert() {
		return $this -> tool_database -> insert($this -> tableName, $this -> data);
	}

	/**
	 * 更新資料
	 */
	public function update() {
		return $this -> tool_database -> update($this -> tableName, $this -> data);
	}

	/**
	 * 刪除資料
	 */
	public function delete() {
		return $this -> tool_database -> delete($this -> tableName, $this -> data);
	}

	

	/**
	 * 此筆資料是否存在
	 * @return boolean
	 */
	public function isExists() {
		return (isset($this -> data["id"]) && $this -> data["id"] != "");
	}
	
	/**
	 * 判斷如果不是JSON格式
	 * @param string $value
	 * @return boolean
	 */
	public function isJson($value){
		if(is_array($value)) return false;
		
    	return ! is_null(json_decode($value));
	}

	/**
	 * 取得資料陣列
	 * @return array
	 */
	public function toArray() {
		return $this -> data;
	}

	/**
	 * 取得 JSON格式
	 * @return string
	 */
	public function toJson() {
		return json_encode($this -> data);
	}

	public function toObject(){
		return json_decode($this -> to_json());
	}

	

	/**
	 * 取得限制長度的資料
	 * @param string $field_name 欄位名稱
	 * @param int $len 限制資料長度
	 * @param boolean $strip_tags 是否過濾 HTML 標籤
	 * @param string $more_string (更多)提示字
	 */
	public function get_limit_data($field_name, $len, $strip_tags = false, $more_string = "...") {
		if (isset($this -> data[$field_name])) {

			$data = ($strip_tags) ? strip_tags($this -> data[$field_name] -> get_value()) : $this -> data[$field_name];

			$source_len = mb_strlen($data, "utf-8");
			// 原始長度比設定長度還短，則顯示全部資料
			if ($len >= $source_len) {
				return $data;
			} else {
				$strlen = mb_strlen($data, 'UTF-8');
				$cutLen = 0;
				$retval = "";
				for ($i = 0; $i < $strlen; $i++) {
					$s = mb_substr($data, $i, 1, 'UTF-8');
					if (strlen($s) == 1) {
						$cutLen+=0.5;
					} else {
						$cutLen += 1;
					}
					$retval .= $s;
					if ($cutLen >= $len) {
						return $retval . $more_string;
					}
				}

				return $retval . $more_string;
			}
		} else {
			return "";
		}
	}

}
?>