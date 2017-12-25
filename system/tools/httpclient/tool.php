<?php 

class Httpclient_Tool extends CS_Tool{

	private $headers;
	private $isPost;
	private $isUpload;
	private $queries;
	private $url;
	private $timeout;
	private $referer;
	private $userAgent;

	/**
	 * 建構子：初始化所有變數
	 */
	public function __construct() {
		$this -> headers = array();
		$this -> isPost = false;
		$this -> isUpload = false;
		$this -> queries = array();
		$this -> url = "";
		$this -> timeout = 30;
		$this -> userAgent = "Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1";
	}

	/**
	 * 解構子：消除所有變數
	 */
	public function __destruct() {
		unset($this -> headers);
		unset($this -> isPost);
		unset($this -> isUpload);
		unset($this -> queries);
		unset($this -> url);
		unset($this -> timeout);
		unset($this -> userAgent);
	}

	/**
	 * 是否為上傳檔案的 Request
	 */
	public function isUpload($upload = true) {
		$this -> isUpload = $upload;
	}

	/**
	 * 是否為 POST
	 */
	public function isPost($post = true) {
		$this -> isPost = $post;
	}

	/**
	 * 設定表頭
	 * @param string $header
	 * @abstract
	 * Connection: Keep-Alive
	 * Content-type: application/x-www-form-urlencoded;charset=UTF-8
	 */
	public function setHeader($header) {
		$this -> headers[] = $header;
	}

	/**
	 * 設定 UserAgent
	 * @param string $userAgent
	 * @abstract
	 * Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1
	 */
	public function setUserAgent($userAgent) {
		$this -> userAgent = $userAgent;
	}

	/**
	 * 設定要傳送的資料
	 * @param string $name
	 * @param string $value
	 */
	public function setField($name, $value) {
		$this -> queries[$name] = $value;
	}

	/**
	 * 超過多久時間自動斷線
	 * @param integer $timeout
	 */
	public function setTimeout($timeout) {
		$this -> timeout = $timeout;
	}

	/**
	 * 設定網址
	 * @param string $url
	 */
	public function setUrl($url) {
		$this -> url = $url;
	}

	/**
	 * 設定來源網址
	 * @param string $url
	 */
	public function setReferer($url) {
		$this -> referer = $url;
	}
	

	/**
	 * 送出
	 * @param string $session = ""
	 * @return string $response
	 */
	public function submit($referer = "") {
		// 未指定URL
		if (!$this -> url)
			return;

		$ch = curl_init();

		$post_fields = ($this -> isUpload) ? $this -> queries : http_build_query($this -> queries);
		
		$host = parse_url($this -> url, PHP_URL_HOST);
		$this -> setUrl($this -> url);
		@session_start();

		
		if( isset($_SESSION[$host . "_referer"]) ){
			$this -> setReferer($_SESSION[$host . "_referer"]);
		}
		if(trim($referer) != ""){
			$this -> setReferer($referer);
		}

		$options = array(
			CURLOPT_URL => $this -> url, 
			CURLOPT_FOLLOWLOCATION => true, 
			CURLOPT_HEADER => true, 
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_TIMEOUT => $this -> timeout, 
			CURLOPT_USERAGENT => $this -> userAgent, 
			CURLOPT_REFERER => $this -> referer);
		if ($this -> headers != array()) {
			$options[CURLOPT_HTTPHEADER] = $this -> headers;
		}
		if ($this -> isPost) {
			$options[CURLOPT_POST] = true;
			$options[CURLOPT_POSTFIELDS] = $post_fields;
		}
			
		if( isset($_SESSION[$host . "_session"]) ){
			$options[CURLOPT_COOKIE] = $_SESSION[$host . "_session"];
		}

		curl_setopt_array($ch, $options);
		$output = curl_exec($ch);
		
		
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($output, 0, $header_size);
		$body = substr($output, $header_size);
		
		curl_close($ch);
		unset($ch);

		// 儲存來源頁(最後一次網址)
		$_SESSION[$host . "_referer"] = $this -> url;
		
		// 儲存 Session
		if(preg_match_all('|Set-Cookie: (.*);|U', $header, $results)){
			$_SESSION[$host . "_session"] = $results[1][0];
		}
		
		session_write_close();
		
		return $body;
	}

}
?>