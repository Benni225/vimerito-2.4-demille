<?php
class RainTplAdapter extends aViewAdapter{
	private $ressource = NULL;
	private $rainTpl = Null;

	public function __construct($file = ''){
		$this->rainTpl = new RainTPL;
		if($file != ''){
			$this->load($file);
		}
		$this->configure();
		return $this;
	}

	public function load($file){
		$this->ressource = new FileData($file);
		return $this;
	}

	public function configure(Array $settings=Array()){
		if(!empty($settings)){
			$this->rainTpl->configure(array(
				'cache_dir'=>	$settings['cacheDir'],
				'black_list'=>$settings['blackList'],
				'check_template_update'=>$settings['checkCacheUpdate'],
				'tpl_ext'=>$settings['viewExtension'],
				'php_enabled'=>$settings['phpEnabled'],
				'debug'	=>$settings['debug']
			));
		}else{
			$this->rainTpl->configure(array(
				'cache_dir'=>	Package::get("cache"),
				'tpl_ext'=>'',
				'tpl_dir'=>	Package::get("view"),
				'check_template_update'=>true,
				'php_enabled'=>true,
				'debug'	=>false,
				'path_replace'=>false
			));
		}
		return $this;
	}

	public function render(Array $options=Array()){
		return $this->rainTpl->draw($this->ressource->get(), TRUE);
	}

	public function assign($name, $value = NULL){
		$this->rainTpl->assign($name, $value);
		return $this;
	}
}