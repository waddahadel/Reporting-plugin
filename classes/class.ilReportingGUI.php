<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\Plugins\CtrlMainMenu\Entry\ctrlmmEntry;

/**
 * Abstract GUI-Class ilReportingGUI
 * Each report must have a separate GUI class which extends this class and implement the abstract
 * methods
 *
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 * @version           $Id:
 *
 * @ilCtrl_IsCalledBy ilReportingGUI: ilRouterGUI, ilUIPluginRouterGUI
 */
abstract class ilReportingGUI {

	/** Addition exports available */
	const EXPORT_PDF = 19;
	const CMD_REPORT = 'report';
	const CMD_SEARCH = 'search';
	const CMD_APPLY_FILTER_SEARCH = 'applyFilterSearch';
	const CMD_RESET_FILTER_SEARCH = 'resetFilterSearch';
	const CMD_APPLY_FILTER_REPORT = 'applyFilterReport';
	const CMD_RESET_FILTER_REPORT = 'resetFilterReport';
	/** Session keys used to store the ids of courses/tests/users and filters status, last status changed */
	const SESSION_KEY_IDS = 'reporting_ids';
	/** @var ilTemplate */
	protected $tpl;
	/** @var ilReportingPlugin */
	protected $pl;
	/** @var ilCtrl */
	protected $ctrl;
	/** @var ilTabsGUI */
	protected $tabs;
	/** @var ilPluginAdmin */
	protected $ilPluginAdmin;
	/** @var ilReportingModel */
	protected $model;
	/** @var ilAccessHandler */
	protected $access;
	/**
	 * @var ilTable2GUI Either search or report table is assigned to this variable
	 */
	protected $table = NULL;


	public function __construct() {
		global $DIC;
		$this->tpl = $DIC->ui()->mainTemplate();
		$this->pl = ilReportingPlugin::getInstance();
		//$this->pl->updateLanguages();
		$this->ctrl = $DIC->ctrl();
		$this->tabs = $DIC->tabs();
		$this->ilPluginAdmin = $DIC["ilPluginAdmin"];
		$this->access = $DIC->access();

		if (!$this->pl->isActive()) {
			ilUtil::sendFailure($this->pl->txt("plugin_not_activated"), true);
			$this->ctrl->redirectByClass(ilPersonalDesktopGUI::class, 'jumpToSelectedItems');
		}
		$this->checkAccess();
	}


	public function getStandardCmd() {
		return self::CMD_SEARCH;
	}


	public function executeCommand() {
		$this->tpl->getStandardTemplate();

		$next_class = $this->ctrl->getNextClass($this);
		switch ($next_class) {
			case '':
				$cmd = $this->ctrl->getCmd($this->getStandardCmd());
				if (!in_array($cmd, get_class_methods($this))) {
					$this->{$this->getStandardCmd()}();
					if (DEBUG) {
						ilUtil::sendInfo("COMMAND NOT FOUND! Redirecting to standard class in ilReportingGUI executeCommand()");
					}

					return true;
				}
				switch ($cmd) {
					default:
						$this->$cmd();
						break;
				}
				break;
			default:
				require_once($this->ctrl->lookupClassPath($next_class));
				$gui = new $next_class();
				$this->ctrl->forwardCommand($gui);
				break;
		}

		$this->tpl->show();

		return true;
	}


	/**
	 * Display the search table
	 *
	 * @return mixed
	 */
	public function search() {
		$data = $this->model->getSearchData($this->table->getFilterNames());
		$this->table->setData($data);
		// Store the user/object ids in the session so the report() can reuse them
		$this->storeIdsInSession($data);
		$this->tpl->setContent($this->table->getHTML());
	}


	/**
	 * Return the available exports (additional to standard CSV and Excel)
	 * Format: key=ID, value=Text
	 *
	 * @return array
	 */
	abstract public function getAvailableExports();


	/**
	 * Display report table
	 *
	 * @return mixed
	 */
	public function report() {
		// If the user has reduced data with checkboxes, store those new check ids in the session
		if (isset($_POST['id']) && count($_POST['id'])) {
			$_SESSION[self::SESSION_KEY_IDS] = $_POST['id'];
		}
	}


	/**
	 * Apply a filter to search table
	 */
	public function applyFilterSearch() {
		$this->table->writeFilterToSession();
		$this->table->resetOffset();
		$this->search();
	}


	/**
	 * Reset filter from search table
	 */
	public function resetFilterSearch() {
		$this->table->resetOffset();
		$this->table->resetFilter();
		$this->ctrl->redirect($this, self::CMD_SEARCH);
	}


	/**
	 * Apply a filter to report table
	 */
	public function applyFilterReport() {
		$this->table->writeFilterToSession();
		$this->table->resetOffset();
		$this->report();
	}


	/**
	 * Reset filter from report table
	 */
	public function resetFilterReport() {
		$this->table->resetOffset();
		$this->table->resetFilter();
		$this->report();
	}


	public function __toString() {
		return strtolower(get_class($this));
	}


	/**
	 * Check read permission for the report of current request defined in MainMenu plugin.
	 * If the user is coming from a course, he can also view the reports if he/she has permission
	 * 'edit_learning_progress' Redirect if user has no access
	 *
	 */
	protected function checkAccess() {
		$hasAccess = false;

		// Coming from a course?
		if (isset($_GET['rep_crs_ref_id'])) {
			$hasAccess = $this->access->checkAccess("edit_learning_progress", "", $_GET['rep_crs_ref_id'], '', $_SESSION[self::SESSION_KEY_IDS][0]);
		}
		// Still no access? Check for permissions defined in MainMenu plugin
		if (!$hasAccess) {
			$entries = ctrlmmEntry::getEntriesByCmdClass($this->ctrl->getCmdClass());
			/** @var ctrlmmEntry $entry */
			foreach ($entries as $entry) {
				if ($entry->checkPermission()) {
					$hasAccess = true;
					break;
				}
			}
		}
		if (!$hasAccess) {
			ilUtil::sendFailure($this->pl->txt("permission_denied"), true);
			$this->ctrl->redirectByClass(ilPersonalDesktopGUI::class, 'jumpToSelectedItems');
		}
	}


	/**
	 * Set if the JasperReport library is available
	 *
	 * @return bool
	 */
	protected function isActiveJasperReports() {
		return true;
	}


	/**
	 * Store IDs from data array in session
	 *
	 * @param array $data
	 */
	protected function storeIdsInSession($data) {
		if (!count($data)) {
			return;
		}
		$ids = array();
		foreach ($data as $v) {
			if (isset($v['id'])) {
				$ids[] = (int)$v['id'];
			}
		}
		$_SESSION[self::SESSION_KEY_IDS] = array_unique($ids);
	}
}
