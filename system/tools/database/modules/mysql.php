<?php 
class CS_MySQL extends Chihsin{
	/**
	 * @var 實體
	 */
	private static $instance;

	/**
	 * @var 資料庫連線
	 */
	private $link = null;

	/**
	 * @var 參數
	 */
	private static $params = array();

	/**
	 * @var 原始語法
	 */
	private $sql = "";

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
		$this -> link = mysql_connect(
					$setting -> host . ":" . $setting -> port,
					$setting -> user, 
					$setting -> password
				) or die("Database Parameter Invalid.");
		mysql_query("SET NAMES '" . $setting -> charset . "'", $this -> link) or die("CharSet Error");
		mysql_select_db($setting -> database) or die("Database Selected Error");
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
		


		$res = mysql_query($sql, $this -> link);

		$results = array();
		while ($res && $row = mysql_fetch_assoc($res)) {
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
		$res = mysql_query($sql, $this -> link);
		$result = null;
		if ($row = mysql_fetch_assoc($res)) {
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

		$res = mysql_query($count_sql, $this -> link);

		if ($res && $row = mysql_fetch_assoc($res)) {
			$count = isset($row["counter"]) ? $row["counter"] : 0;
		}
		unset($row);
		unset($res);

		return $count;

	}

	/**
	 * 執行新增、修改、刪除
	 * @access public
	 * @param string $sql SQL語法
	 */
	public function execute() {
		$sql = $this -> into_command();
		mysql_query($sql, $this -> link);
	}

	public function __destruct() {
		@mysql_close($this -> link);
		unset($this -> link);
	}

	/**
	 * 帶入字串參數
	 */
	public function set_string($value) {
		if ($value === null) {
			self::$params[] = "NULL";
		} else {
			self::$params[] = "'" . mysql_real_escape_string($value) . "'";
		}
	}

	/**
	 * 帶入數值參數
	 */
	public function set_number($value) {
		if ($value === null) {
			self::$params[] = "NULL";
		} else if (is_numeric($value)) {
			self::$params[] = $value;
		}
	}

	/**
	 * 帶入UUID 參數
	 */
	public function set_uuid() {
		self::$params[] = "UUID()";
	}

	public function set_data($data) {

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
				$this -> set_number($data);
				break;
			case is_string($data) :
				$this -> set_string($data);
		}
	}

	/**
	 * 設定語法
	 * @param string $sql
	 */
	public function set_command($sql) {
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
			// unset($this -> params);
			self::$params = array();
		}

		$sql = str_replace("<prefix>", $this -> settings -> prefix, $sql);
		
		$this -> tool_logger -> log($sql, "database");

		return $sql;
	}
}
?>