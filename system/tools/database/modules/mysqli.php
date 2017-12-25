<?php 
class CS_MySQLi extends Chihsin{
	/**
	 * @var 實體
	 */
	private static $instance;

	/**
	 * @var 資料庫連線
	 */
	private $link = null;


	/**
	 * @var 原始語法
	 */
	private $sql = "";

	/**
	 * @var 參數
	 */
	private static $params = array();

	/**
	 * @var 設定值
	 */
	private $settings = array();

	/**
	 * 取得資料庫實體
	 * @return Database::$instance
	 */
	public static function get_database($setting) {
		if (false == (self::$instance instanceof self)) {
			self::$instance = new self();
			self::$instance -> connect($setting);
		}
		return self::$instance;
	}

	public function connect($setting) {
		$this -> settings = $setting;
		$this -> link = mysqli_connect(
					$setting -> host . ":" . $setting -> port,
					$setting -> user, 
					$setting -> password
				) or die("Database Parameter Invalid.");
		mysqli_query($this -> link, "SET NAMES '" . $setting -> charset . "'") or die("CharSet Error");
		mysqli_select_db($this -> link,$setting -> database) or die("Database Selected Error");

		self::$params = array();
	}

	/**
	 * 查詢執行
	 * @access public
	 * @param string $sql SQL語法
	 * @return item[] $results
	 */
	public function execute_query($count = -1) {

		$total = 0;

		if($count > 0){
			$total = $this -> execute_query_num();
			
		}
		

		$sql = $this -> into_command();

		// 是否分頁
		if($count > 0){
			$this -> tool_pagination -> set_count_per_page($count);
			$this -> tool_pagination -> set_total_count($total);

			$per_page = $this -> tool_pagination -> get_count_per_page();
			$start_index = $this -> tool_pagination -> get_start_index();

			if ($per_page > -1) {
				// 計算分頁數量取得SQL的 LIMIT參數值
				$sql = preg_replace("/;|limit .*?$/i", "", $sql) . (" LIMIT " . $start_index . "," . $per_page) . ";";
				
			}
		}


		$res = mysqli_query($this -> link, $sql);

		$results = array();
		while ($res && $row = mysqli_fetch_assoc($res)) {
			$results[] = $row;
		}
		unset($row);
		unset($res);
		return $results;
	}

	/**
	 * 查詢單筆結果
	 * @return item $result
	 */
	public function execute_single_query() {
		$sql = $this -> into_command();
		$res = mysqli_query($this -> link, $sql);
		$result = null;
		if ($row = mysqli_fetch_assoc($res)) {
			$result = $row;
		}
		unset($row);
		unset($res);
		return $result;
	}

	/**
	 * 查詢結果筆數
	 * @return integer $count
	 */
	public function execute_query_num() {
		

		$count = 0;

		$sql = $this -> into_command(false);


		// 取得此查詢總筆數
		$count_sql = preg_replace(array('/SELECT.*?FROM /Asi', '/SELECT \*,/Asi', '/ORDER BY .*/'), array('SELECT COUNT(*) AS counter FROM ', 'SELECT COUNT(*) AS counter,', ''), $sql);


		if($sql == $count_sql){
			return 0;
		}

		$res = mysqli_query($this -> link, $count_sql);

		if ($res && $row = mysqli_fetch_assoc($res)) {
			$count = isset($row["counter"]) ? $row["counter"] : 0;
		}
		unset($row);
		unset($res);

		return $count;

	}

	// 不曉得為什麼…但可以防止 params 參數被父類的 __get 呼叫(吸走)!!!!
	public function nani(){
		//$this -> params = array();
	}

	/**
	 * 執行新增、修改、刪除
	 * @access public
	 * @param string $sql SQL語法
	 */
	public function execute() {
		$sql = $this -> into_command();
		mysqli_query($this -> link, $sql);
	}

	public function __destruct() {
		@mysqli_close($this -> link);
		unset($this -> link);
	}

	/**
	 * 帶入字串參數
	 */
	public function embedString($value) {
		if ($value === null) {
			self::$params[] = "NULL";
		} else {
			self::$params[] = "'" . mysqli_real_escape_string($this -> link, $value) . "'";
		}
	}

	/**
	 * 帶入數值參數
	 */
	public function embedNumber($value) {
		if ($value === null) {
			self::$params[] = "NULL";
		} else if (is_numeric($value)) {
			self::$params[] = $value;
		}
	}

	/**
	 * 帶入UUID 參數
	 */
	public function embedUUID() {
		self::$params[] = "UUID()";
	}

	public function embedData($data) {

		// 進資料庫資訊不可為陣列
		if (is_array($data)) {
			ob_start();
			print_r($data);
			$err_data = ob_get_contents();
			ob_end_clean();
			throw new Exception("Can't save array : " . $err_data);
		}

		switch(true) {
			case is_null($data) :
				self::$params[] = "NULL";
				break;
			case is_double($data) :
			case is_float($data) :
			case is_int($data) :
			case is_integer($data) :
			case is_long($data) :
				$this -> embedNumber($data);
				break;
			case is_string($data) :
				$this -> embedString($data);
		}
	}

	/**
	 * 設定語法
	 * @param string $sql
	 */
	public function embedCommand($sql) {
		$this -> sql = &$sql;
	}

	/**
	 * 帶入參數至原始語法
	 * @return string $sql
	 */
	private function into_command($clear = true) {
		
		$datas = explode("?", $this -> sql);
		if (isset(self::$params) && count(self::$params) != count($datas) - 1) {
			return "";
		}
		
		$sql = "";
		foreach ($datas as $key => $data) {
			$param = isset(self::$params[$key]) ? self::$params[$key] : "";
			$sql .= $data . $param;
		}

		if ($clear) {
			$this -> sql = "";
			// unset(self::$params);
			self::$params = array();
		}

		$sql = str_replace("<prefix>", $this -> settings -> prefix, $sql);
		
		$this -> tool_logger -> log($sql, "database");

		return $sql;
	}
}
?>