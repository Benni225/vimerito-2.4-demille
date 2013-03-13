<?php
class FileData{
	private $ressource = '';
	public $fileName = '';
	public $filePath = '';
	public $fileExtension = '';
	public $fileSize = '';
	public $fileContent = '';
	public function __construct($path = ''){
		if(!empty($path))
			$this->set($path);
	}
	public function set($path){
		if(!empty($path)){
			$this->ressource = $path;
			$this->fileName = basename($path);
			$__tmpExt = explode('.', $path);
			$this->fileExtension = $__tmpExt[count($__tmpExt)-1];
			$this->filePath = dirname($this->ressource).DS;
			if(file_exists($path))
				$this->fileSize = filesize($path);
		}else{
			throw new Exception('Can not set file!');
		}
	}

	public function get(){
		return !empty($this->ressource)?$this->ressource:NULL;
	}

	public function setContent($content){
		$this->fileContent = $content;
	}

	public function setFilename($name){
		$this->fileName = $name;
	}

	public function setExtension($ext){
		$this->fileExtension = $ext;
	}

	public function save(){
		mkdir($this->filePath, 0755, true);
		$file = fopen($this->filePath.DS.$this->fileName.'.'.$this->fileExtension, 'a+');
		fwrite($file, $this->fileContent);
		fclose($file);
	}

	public function getContent(){
		return file_exists($this->ressource)?file_get_contents($this->ressource):NULL;
	}
}