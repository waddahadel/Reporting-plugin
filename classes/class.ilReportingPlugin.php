<?php
require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Reporting Plugin
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id$
 *
 */
class ilReportingPlugin extends ilUserInterfaceHookPlugin {

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
	 * Only activate plugin if CtrlMainMenu and ilRouterGUI are installed and active
	 *
	 * @return bool
	 * @throws ilPluginException
	 */
	protected function beforeActivation() {
		if (!self::checkPreconditions()) {
			ilUtil::sendFailure("Cannot activate plugin: Make sure that you have installed the CtrlMainMenu plugin. Please read the documentation: http://www.ilias.de/docu/goto_docu_wiki_1357_Reporting_Plugin.html", true);

			//throw new ilPluginException("Cannot activate plugin: Make sure that you have installed the CtrlMainMenu plugin. Please read the documentation: http://www.ilias.de/docu/goto_docu_wiki_1357_Reporting_Plugin.html");
			return false;
		}

		return true;
	}


	/**
	 * Create the MainMenu entries if not yet existing
	 *
	 */
	protected function afterActivation() {
		self::createReportMainMenuEntries();
	}


	/**
	 * @return string
	 */
	public static function getRootDir() {
		return dirname(dirname(__FILE__)) . '/';
	}


	/**
	 * Check if CtrlMainMenu Plugin and ilRouter are installed and active
	 *
	 * @return bool
	 */
	public static function checkPreconditions() {
		global $DIC;
		$ilPluginAdmin = $DIC["ilPluginAdmin"];
		$existCtrlMainMenu = $ilPluginAdmin->exists(IL_COMP_SERVICE, 'UIComponent', 'uihk', 'CtrlMainMenu');
		$isActiveCtrlMainMenu = $ilPluginAdmin->isActive(IL_COMP_SERVICE, 'UIComponent', 'uihk', 'CtrlMainMenu');

		return ($existCtrlMainMenu && $isActiveCtrlMainMenu);
	}


	/**
	 * Create the main menu entries of the reporting plugin.
	 * Abort if they were already created before...
	 */
	public static function createReportMainMenuEntries() {
		require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/CtrlMainMenu/classes/Menu/class.ctrlmmMenu.php');
		require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/CtrlMainMenu/classes/Entry/class.ctrlmmEntry.php');
		global $DIC;
		$ilUser = $DIC->user();

		ctrlmmMenu::includeAllTypes();
		$entries_cpu = ctrlmmEntry::getEntriesByCmdClass(ilReportingCoursesPerUserGUI::class);
		$entries_upc = ctrlmmEntry::getEntriesByCmdClass(ilReportingUsersPerCourseGUI::class);
		$entries_upt = ctrlmmEntry::getEntriesByCmdClass(ilReportingUsersPerTestGUI::class);
		$entries_cpu_lp = ctrlmmEntry::getEntriesByCmdClass(ilReportingCoursesPerUserLPGUI::class);
		$entries_upc_lp = ctrlmmEntry::getEntriesByCmdClass(ilReportingUsersPerCourseLPGUI::class);

		$main_reports_created = false;
		$additional_reports_created = false;

		if (count($entries_cpu) || count($entries_upc) || count($entries_upt)) {
			$main_reports_created = true;
		}

		if (count($entries_cpu_lp) || count($entries_upc_lp)) {
			$additional_reports_created = true;
		}

		$langUser = $ilUser->getLanguage();

		if (!$main_reports_created) {
			$dropdown = new ctrlmmEntryDropdown();
			$trans = array( "$langUser" => 'Reports' );
			$trans = array_merge($trans, array( 'de' => 'Reports', 'en' => 'Reports' ));
			$dropdown->setTranslations($trans);
			$dropdown->setUseImage(true);
			$dropdown->create();

			$trans = array( "$langUser" => 'Courses per User' );
			$trans = array_merge($trans, array(
				'de' => 'Kurse pro Benutzer',
				'en' => 'Courses per User',
			));
			self::createMainMenuEntry($trans, ilUIPluginRouterGUI::class . ',' . ilReportingCoursesPerUserGUI::class, $dropdown->getId());

			$trans = array( "$langUser" => 'Users per Course' );
			$trans = array_merge($trans, array(
				'de' => 'Benutzer pro Kurs',
				'en' => 'Users per Course',
			));
			self::createMainMenuEntry($trans, ilUIPluginRouterGUI::class . ',' . ilReportingUsersPerCourseGUI::class, $dropdown->getId());

			$trans = array( "$langUser" => 'Users per Test' );
			$trans = array_merge($trans, array(
				'de' => 'Benutzer pro Test',
				'en' => 'Users per Test',
			));
			self::createMainMenuEntry($trans, ilUIPluginRouterGUI::class . ',' . ilReportingUsersPerTestGUI::class, $dropdown->getId());
		}

		if (!$additional_reports_created) {
			// Find dropdown ID
			$entries_cpu = ctrlmmEntry::getEntriesByCmdClass(ilReportingCoursesPerUserGUI::class);
			/** @var ctrlmmEntry $entry */
			$entry = $entries_cpu[0];
			$dropdown_id = $entry->getParent();

			$trans = array( "langUser" => 'Courses per User, detailed' );
			$trans = array_merge($trans, array(
				'de' => 'Kurse pro Benuzter, detailliert',
				'en' => 'Courses per User, detailed',
			));
			self::createMainMenuEntry($trans, ilUIPluginRouterGUI::class . ',' . ilReportingCoursesPerUserLPGUI::class, $dropdown_id);

			$trans = array( "langUser" => 'Users per Course, detailed' );
			$trans = array_merge($trans, array(
				'de' => 'Benutzer pro Kurs, detailliert',
				'en' => 'Users per Course, detailed',
			));
			self::createMainMenuEntry($trans, ilUIPluginRouterGUI::class . ',' . ilReportingUsersPerCourseLPGUI::class, $dropdown_id);
		}
	}


	/**
	 * Create a Report Entry in MainMenu
	 *
	 * @param array $trans
	 * @param       $gui_class
	 * @param int   $dropdown_id
	 */
	private static function createMainMenuEntry(array $trans, $gui_class, $dropdown_id) {
		$entry = new ctrlmmEntryCtrl();
		$entry->setTranslations($trans);
		$entry->setGuiClass($gui_class);
		$entry->setCmd(ilReportingGUI::CMD_SEARCH);
		$entry->setPermissionType(ctrlmmMenu::PERM_ROLE);
		$entry->setPermission('["2"]');
		$entry->setParent($dropdown_id);
		$entry->create();
	}


	/**
	 *
	 */
	public static function removeReportMainMenuEntries() {
		require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/CtrlMainMenu/classes/Menu/class.ctrlmmMenu.php');
		require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/CtrlMainMenu/classes/Entry/class.ctrlmmEntry.php');

		foreach (array_merge(ctrlmmEntry::getEntriesByCmdClass(ilReportingCoursesPerUserGUI::class), ctrlmmEntry::getEntriesByCmdClass(ilReportingUsersPerCourseGUI::class), ctrlmmEntry::getEntriesByCmdClass(ilReportingUsersPerTestGUI::class), ctrlmmEntry::getEntriesByCmdClass(ilReportingCoursesPerUserLPGUI::class), ctrlmmEntry::getEntriesByCmdClass(ilReportingUsersPerCourseLPGUI::class)) as $entry) {
			/**
			 * @var ctrlmmEntry $entry
			 */
			$entry->delete();
		}
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

		// TODO Remove any folders?

		if (self::checkPreconditions()) {
			self::removeReportMainMenuEntries();
		}

		return true;
	}
}
