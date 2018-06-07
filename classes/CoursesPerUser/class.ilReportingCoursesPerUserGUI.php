<?php
require_once __DIR__ . "/../../vendor/autoload.php";

/**
 * GUI-Class ilReportingCoursesPerUserGUI
 *
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 * @version           $Id:
 *
 * @ilCtrl_IsCalledBy ilReportingCoursesPerUserGUI: ilRouterGUI, ilUIPluginRouterGUI
 */
class ilReportingCoursesPerUserGUI extends ilReportingGUI {

	const CMD_SHOW_OBJECTS_IN_COURSE = 'showObjectsInCourse';


	function __construct() {
		parent::__construct();
		$this->model = new ilReportingCoursesPerUserModel();
	}


	public function executeCommand() {
		parent::executeCommand();
	}


	/**
	 * Redirect to CoursesPerUserLP report which shows objects in courses which are relevant for LP
	 */
	public function showObjectsInCourse() {
		$this->ctrl->setParameterByClass(ilReportingCoursesPerUserLPGUI::class, "from", self::class);
		$this->ctrl->redirectByClass(ilReportingCoursesPerUserLPGUI::class, self::CMD_REPORT);
	}


	/**
	 * Display table for searching the users
	 */
	public function search() {
		$this->tpl->setTitle($this->pl->txt('report_courses_per_user'));
		$this->table = new ilReportingCoursesPerUserSearchTableGUI($this, ilReportingGUI::CMD_SEARCH);
		$this->table->setTitle($this->pl->txt('search_users'));
		parent::search();
	}


	/**
	 * Display report table
	 */
	public function report() {
		parent::report();
		$this->tpl->setTitle($this->pl->txt('report_courses_per_user'));
		if ($this->table === NULL) {
			$this->table = new ilReportingCoursesPerUserReportTableGUI($this, ilReportingGUI::CMD_REPORT);
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
		$this->table = new ilReportingCoursesPerUserSearchTableGUI($this, $this->getStandardCmd());
		parent::applyFilterSearch();
	}


	public function resetFilterSearch() {
		$this->table = new ilReportingCoursesPerUserSearchTableGUI($this, $this->getStandardCmd());
		parent::resetFilterSearch();
	}


	public function applyFilterReport() {
		$this->table = new ilReportingCoursesPerUserReportTableGUI($this, ilReportingGUI::CMD_REPORT);
		parent::applyFilterReport();
	}


	public function resetFilterReport() {
		$this->table = new ilReportingCoursesPerUserReportTableGUI($this, ilReportingGUI::CMD_REPORT);
		parent::resetFilterReport();
	}


	public function getAvailableExports() {
		if ($this->isActiveJasperReports()) {
			$exports[self::EXPORT_PDF] = 'export_pdf';
		}

		return $exports;
	}
}
