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
}
?>