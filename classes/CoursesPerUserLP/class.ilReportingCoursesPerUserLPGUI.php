<?php
require_once __DIR__ . "/../../vendor/autoload.php";

/**
 * GUI-Class ilReportingCoursesPerUserLPGUI
 *
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 * @version           $Id:
 *
 * @ilCtrl_IsCalledBy ilReportingCoursesPerUserLPGUI: ilRouterGUI, ilUIPluginRouterGUI
 */
class ilReportingCoursesPerUserLPGUI extends ilReportingGUI {

	function __construct() {
		parent::__construct();
		$this->model = new ilReportingCoursesPerUserLPModel();
		$this->tpl->addCss($this->pl->getDirectory() . '/templates/default/styles.css');
	}


	public function executeCommand() {
		parent::executeCommand();
	}


	/**
	 * Display table for searching the users
	 */
	public function search() {
		$this->tpl->setTitle($this->pl->txt('report_courses_per_user'));
		$this->table = new ilReportingCoursesPerUserLPSearchTableGUI($this, ilReportingGUI::CMD_SEARCH);
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
			$this->table = new ilReportingCoursesPerUserLPReportTableGUI($this, ilReportingGUI::CMD_REPORT);
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
		$this->table = new ilReportingCoursesPerUserLPSearchTableGUI($this, $this->getStandardCmd());
		parent::applyFilterSearch();
	}


	public function resetFilterSearch() {
		$this->table = new ilReportingCoursesPerUserLPSearchTableGUI($this, $this->getStandardCmd());
		parent::resetFilterSearch();
	}


	public function applyFilterReport() {
		$this->table = new ilReportingCoursesPerUserLPReportTableGUI($this, ilReportingGUI::CMD_REPORT);
		parent::applyFilterReport();
	}


	public function resetFilterReport() {
		$this->table = new ilReportingCoursesPerUserLPReportTableGUI($this, ilReportingGUI::CMD_REPORT);
		parent::resetFilterReport();
	}


	public function getAvailableExports() {
		/*$exports = array(
			self::EXPORT_EXCEL_FORMATTED => 'export_custom_excel',
		);
		if ($this->isActiveJasperReports()) $exports[self::EXPORT_PDF] = 'export_pdf';
		return $exports;*/
		return array();
	}
}

?>