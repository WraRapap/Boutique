<?php
/**
 * 檔案處理 模組
 */
class File_Tool extends CS_Tool {

	private $isTemp = false;

	/**
	 * 將檔案從暫存區移到目的資料夾
     * @param string $tmp_name 暫存區路徑
     * @param string $target_path 目的資料夾路徑
     * @return boolean 成功|失敗
     */
	private function moveFromTempToTarget($tmpName, $targetPath){

		$filePath = $this -> config_env -> basePath . $this -> config_env -> filePath;

		$oldmask = umask(0);
		$dirs = preg_split("/[\\\\\/]/", dirname($targetPath));
        $dirPath = "";
        foreach ($dirs as $dir) {
			if ($dir != "") {
            	$dirPath .= $dir . "/";
                if (!file_exists($filePath . "/" . $dirPath)) {
					mkdir($filePath . "/" . $dirPath);
				}
			}
		}
        umask($oldmask);

        // 將圖片從暫存區複製至指定資料夾
        return (move_uploaded_file($tmpName, $filePath . "/" . $targetPath));
	}


	private function fileUpdate($file){

		// 沒有檔案
		if( $file -> error == 4 ){
			return "";
		}
			
		// 隨時產生檔案名稱
		$fileName = uniqid(rand(), true) . "." . $file -> ext;
			
		$dir = ($this -> isTemp) ? "/temp/" : "/";
			
		// 依網域來區分檔案上傳區
		@session_start();
		if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
			$dir .= $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"] . "/";
		}
		@session_write_close();
			
		// 將檔案從上傳暫存區 複製到 指定目錄
		if($this -> moveFromTempToTarget($file -> tmp_name, $dir . $fileName)){
			return $dir . $fileName;
		}
		else{
			return "";
		}
		
	}

	/**
	 * 上傳檔案
     * @param string $key 參數名稱
	 * @param Array $allow_type 允許的類型
     */
	public function upload($key, $allow_type = array()) {
        	
		// 是否存有檔案上傳
		if(isset($_FILES[$key])){
				
			$file = $_FILES[$key];
			$files = array();
			if(is_array($file["name"])){
				$count = count($file["name"]);
					
				for ($i = 0; $i < $count; $i++) {
                	$ext = strtolower(substr(strrchr($file["name"][$i], "."), 1));
                    $files[$i] = (object) array("name" => $file["name"][$i], "type" => $file["type"][$i], "tmp_name" => $file["tmp_name"][$i], "error" => $file["error"][$i], "size" => $file["size"][$i], "ext" => $ext);
						
					// 篩選檔案類型
					if(count($allow_type) > 0){
						if(!in_array($ext, $allow_type)){
							continue;
						}
					}
						
					// 上傳檔案
					$files[$i] -> path = $this -> fileUpdate($files[$i]);
				}
			}
			else{
				$ext = strtolower(substr(strrchr($file["name"], "."), 1));
                $files[0] = (object) array("name" => $file["name"], "type" => $file["type"], "tmp_name" => $file["tmp_name"], "error" => $file["error"], "size" => $file["size"], "ext" => $ext);
					 
				// 篩選檔案類型
				if(count($allow_type) > 0){
					if(!in_array($ext, $allow_type)){
						continue;
					}
				}
					 
				// 上傳檔案
				$files[0] -> path = $this -> fileUpdate($files[0]);
			}
			
			return $files;
		}
			
		return array();

	}

	public function upload_to_temp($key, $allow_type = array()){
		$this -> isTemp = true;
		return $this -> upload($key, $allow_type);
	}



    /**
     * 檔案下載
     * @param string $path 真實檔案路徑
     * @param string $download_file_name 希望下載的檔案名稱
     */
    public function download($fileRealPath, $download_file_name) {

    	$filePath = $this -> config_env -> filePath;

    	if(substr($fileRealPath,0,1) != "/"){
    		$fileRealPath = "/" . $fileRealPath;
    	}

    	$path = $filePath . $fileRealPath;
        header("Content-type:application");
        header("Content-Disposition: attachment; filename=" . $download_file_name);
        readfile($url . str_replace("@", "", $path));
        exit ;
	}

	public function delete($filePath){
		$filePath = $this -> config_env -> basePath . $this -> config_env -> filePath . $filePath;
		
		if(file_exists($filePath)){
			unlink($filePath);
			return true;
		}

		return false;
	}
}
?>