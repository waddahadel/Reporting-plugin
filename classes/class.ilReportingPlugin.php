<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use srag\DIC\Reporting\DICTrait;
use srag\Plugins\Reporting\Menu\Menu;

/**
 * Reporting Plugin
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id$
 *
 */
class ilReportingPlugin extends ilUserInterfaceHookPlugin {
    use DICTrait;
	const PLUGIN_ID = 'reporting';
	const PLUGIN_NAME = 'Reporting';
	/**
	 * @var ilReportingPlugin
	 */
	protected static $instance;


	/**
	 * @return ilReportingPlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @var ilDB
	 */
	protected $db;


	/**
	 *
	 */
	public function __construct() {
		parent::__construct();

		global $DIC;

		$this->db = $DIC->database();
	}


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return string
	 */
	public static function getRootDir() {
		return dirname(dirname(__FILE__)) . '/';
	}


	/**
	 * @return int[] All users that the current user has access to concerning the permissions
	 *               view_learning_progress[_rec]
	 */
	public function getRestrictedByOrgUnitsUsers() {
		$orgunits = ilObjOrgUnitTree::_getInstance();
		$users = array();

		//get the users that are directly accessible
		$orgus = $orgunits->getOrgusWhereUserHasPermissionForOperation('view_learning_progress');
		foreach ($orgus as $orgu) {
			$users = array_merge($users, $orgunits->getEmployees($orgu, false));
		}

		//get users that are recursivly accessible.
		$orgus = $orgunits->getOrgusWhereUserHasPermissionForOperation('view_learning_progress_rec');
		foreach ($orgus as $orgu) {
			$users = array_merge($users, $orgunits->getEmployees($orgu, true));
		}

		return $users;
	}


	/**
	 * @return bool
	 */
	protected function beforeUninstall() {
		$this->db->dropTable(ilReportingConfig::TABLE_NAME, false);

		return true;
	}


    /**
     * @inheritDoc
     */
    public function promoteGlobalScreenProvider() : AbstractStaticPluginMainMenuProvider {
        return new Menu(self::dic()->dic(), $this);
    }
}
