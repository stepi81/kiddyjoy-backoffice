<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 class Cache_Manager {
     
     private $cacheDriver;
     
     public function __construct() {
         
         $memcached = new Memcached();
		 $memcached->addServer('localhost', 11211);
		
		 $this->cacheDriver = new \Doctrine\Common\Cache\MemcachedCache();
		 $this->cacheDriver->setMemcached($memcached);
     }
	 
	 public function deleteCache( $id ) {
	 	
		$this->cacheDriver->delete($id);
	 }
	 
	 public function deleteAllCache() {
	 	
		$this->cacheDriver->deleteAll();
	 }
	 
	 public function checkCache($id) {
	 		
	 	if ($this->cacheDriver->contains($id)) {
		    echo $id.' cache exists<br />';
		} else {
		    echo $id.' cache does not exist<br />';
		}	
	 }
	 
	 public function saveCache( $id, $data ) {
	 	$this->cacheDriver->save($id, $data);
	 }
 }
 
 /* End of file Cache_Manager.php */
 /* Location: ./system/applications/_backend/libraries/Cache_Manager.php */