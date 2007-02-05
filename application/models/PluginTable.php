<?php
require_once LIB_DIR.DIRECTORY_SEPARATOR.'Kea'.DIRECTORY_SEPARATOR.'Plugin.php';
/**
 * Special Plugin finder (to generate the correct plugins from the db)
 *
 * @package Sitebuilder
 * @author Kris Kelly
 **/
class PluginTable extends Doctrine_Table
{
	public function findActive() {
		return $this->findBySql("active = 1");
	}
	
	public function activeArray($router) {
		$records = $this->findActive();
		$plugins = array();
		foreach( $records as $record )
		{
			$name = $record->name;
			require_once $record->path;
			$plugins[] = new $name($router, $record);
		}
		return $plugins;
	}
} // END class PluginTable extends Doctrine_Table

?>