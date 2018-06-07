<?php
require_once __DIR__ . "/../../vendor/autoload.php";

/**
 * GUI-Class ilReportingCoursesPerUserLPGUI
 *
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 * @version           $Id:
 *
 * @ilCtrl_IsCalledBy ilReportingUsersPerCourseLPGUI: ilRouterGUI, ilUIPluginRouterGUI
 */
class ilReportingUsersPerCourseLPGUI extends ilReportingGUI {

	function __construct() {
		parent::__construct();
		$this->model = new ilReportingUsersPerCourseLPModel();
		$this->tpl->addCss($this->pl->getDirectory() . '/templates/default/styles.css');
	}


	public function executeCommand() {
		parent::executeCommand();
	}


	/**
	 * Display table for searching the users
	 */
	public function search() {
		$this->tpl->setTitle($this->pl->txt('report_users_per_course'));
		$this->table = new ilReportingUsersPerCourseLPSearchTableGUI($this, ilReportingGUI::CMD_SEARCH);
		$this->table->setTitle($this->pl->txt('search_courses'));
		parent::search();
	}


	/**
	 * Display report table
	 */
	public function report() {
		parent::report();
		$this->tpl->setTitle($this->pl->txt('report_users_per_course'));
		if ($this->table === NULL) {
			$this->table = new ilReportingUsersPerCourseLPReportTableGUI($this, ilReportingGUI::CMD_REPORT);
		}
		$data = $this->model->getReportData($_SESSION[self::SESSION_KEY_IDS], $this->table->getFilterNames());
		$this->table->setData($data);
		if ($this->ctrl->getCmd() != self::CMD_APPLY_FILTER_REPORT
			&& $this->ctrl->getCmd() != self::CMD_RESET_FILTER_REPORT) {
			$onlyUnique = isset($_GET['pre_xpt']);
			$this->storeIdsInSession($data, $onlyUnique);
		}
		$this->tpl->setContent($this->table->getHTML());
	}


	public function applyFilterSearch() {
		$this->table = new ilReportingUsersPerCourseLPSearchTableGUI($this, $this->getStandardCmd());
		parent::applyFilterSearch();
	}


	public function resetFilterSearch() {
		$this->table = new ilReportingUsersPerCourseLPSearchTableGUI($this, $this->getStandardCmd());
		parent::resetFilterSearch();
	}


	public function applyFilterReport() {
		$this->table = new ilReportingUsersPerCourseLPReportTableGUI($this, ilReportingGUI::CMD_REPORT);
		parent::applyFilterReport();
	}


	public function resetFilterReport() {
		$this->table = new ilReportingUsersPerCourseLPReportTableGUI($this, ilReportingGUI::CMD_REPORT);
		parent::resetFilterReport();
	}


	public function getAvailableExports() {
		if ($this->isActiveJasperReports()) {
			$exports[self::EXPORT_PDF] = 'export_pdf';
		}

		return $exports;
	}
}
