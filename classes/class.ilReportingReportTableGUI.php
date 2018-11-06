<?php

/**
 * TableGUI ilReportingReportTableGUI
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 */
abstract class ilReportingReportTableGUI extends ilTable2GUI {

	/**
	 * @var array
	 */
	protected $ignored_cols = array();
	/**
	 * @var array
	 */
	protected $date_cols = array();
	/**
	 * @var string
	 */
	protected $date_format = ilReportingFormatter::DEFAULT_DATE_FORMAT;
	/**
	 * @var ilReportingPlugin
	 */
	protected $pl;
	/**
	 * @var ilToolbarGUI
	 */
	protected $toolbar;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilReportingFormatter
	 */
	protected $formatter;
	/**
	 * @var string
	 */
	protected $filter_cmd = ilReportingGUI::CMD_APPLY_FILTER_REPORT;
	/**
	 * @var string
	 */
	protected $reset_cmd = ilReportingGUI::CMD_RESET_FILTER_REPORT;
	protected $filter_names = array();


	/**
	 * @param ilReportingGUI $a_parent_obj
	 * @param string         $a_parent_cmd
	 */
	function __construct(ilReportingGUI $a_parent_obj, $a_parent_cmd) {
		global $DIC;
		$this->pl = ilReportingPlugin::getInstance();
		$this->toolbar = $DIC->toolbar();
		$this->ctrl = $DIC->ctrl();
		$this->formatter = ilReportingFormatter::getInstance();
		$this->setId('reporting_' . $this->ctrl->getCmdClass());
		$this->setPrefix('pre');
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setIgnoredCols(array( 'id', 'unique_id', 'obj_id', 'ref_id' ));
		$this->setDateCols(array( 'status_changed' ));
		$this->setRowTemplate('tpl.template_row.html', $this->pl->getDirectory());
		$this->setFormAction($this->ctrl->getFormAction($this->parent_obj));
		$this->setEnableHeader(true);
		$this->setEnableTitle(true);
		$this->setTopCommands(true);
		$this->setShowRowsSelector(true);
		$this->initColumns();
		$this->initToolbar();
		$this->parent_object = $a_parent_obj;
		$this->setExportFormats(array());
		$this->setDisableFilterHiding(true);
		$this->initFilter();
	}


	/**
	 * @inheritdoc
	 */
	public function numericOrdering($a_field) {
		return true;
	}


	/**
	 * @inheritdoc
	 */
	public function setExportFormats(array $formats) {
		parent::setExportFormats(array( self::EXPORT_EXCEL, self::EXPORT_CSV ));
		foreach ($this->parent_object->getAvailableExports() as $k => $format) {
			$this->export_formats[$k] = $this->pl->getPrefix() . '_' . $format;
		}
	}


	/**
	 * Add filters for status and status changed
	 */
	public function initFilter() {
		$item = new ilSelectInputGUI($this->pl->txt('user_status'), 'status');
		$states = array( '' => '' );
		for ($i = 1; $i <= 4; $i ++) {
			$k = $i - 1;
			$states[$i] = $this->pl->txt("status$k");
		}
		$item->setOptions($states);
		$this->addFilterItemWithValue($item);
		$item = new ilDateTimeInputGUI($this->pl->txt('status_changed_from'), 'status_changed_from');
		//$item->setMode(ilDateTimeInputGUI::MODE_INPUT);
		$this->addFilterItemWithValue($item);
		$item = new ilDateTimeInputGUI($this->pl->txt('status_changed_to'), 'status_changed_to');
		//$item->setMode(ilDateTimeInputGUI::MODE_INPUT);
		$this->addFilterItemWithValue($item);
	}


	/**
	 * @param array $a_set
	 */
	public function fillRow($a_set) {
		$this->tpl->setVariable('ID', $a_set['id']);
		foreach ($this->getColumns() as $k => $v) {
			if (isset($a_set[$k])) {
				if (!in_array($k, $this->getIgnoredCols())) {
					if (in_array($k, $this->getDateCols())) {
						$value = $this->formatter->format($a_set[$k], ilReportingFormatter::FORMAT_STR_DATE, [
							'format' => $this->getDateFormat()
						]);
					} else {
						$formatter = isset($v['formatter']) ? $v['formatter'] : NULL;
						$value = $this->formatter->format($a_set[$k], $formatter);
					}
					$this->tpl->setCurrentBlock('td');
					$this->tpl->setVariable('VALUE', $value);
					$this->tpl->parseCurrentBlock();
				}
			} else {
				$this->tpl->setCurrentBlock('td');
				$this->tpl->setVariable('VALUE', '&nbsp;');
				$this->tpl->parseCurrentBlock();
			}
		}
	}


	/**
	 * Method each subclass must implement to handle custom exports
	 *
	 * @param int  $format Format constant from ilReportingGUI EXPORT_PDF
	 * @param bool $send
	 */
	public abstract function exportDataCustom($format, $send = false);


	/**
	 * Apply custom report downloads
	 *
	 * @param int  $format
	 * @param bool $send
	 */
	public function exportData($format, $send = false) {
		if (in_array($format, array_keys($this->parent_object->getAvailableExports()))) {
			$this->exportDataCustom($format, $send);
		} else {
			parent::exportData($format, $send);
		}
	}


	/**
	 * Init toolbar containing a back link
	 * Determine if the user has clicked on the report tab in a course ($_GET['rep_crs_ref_id'] is
	 * set) or the back link should go to the parent's search form...
	 */
	protected function initToolbar() {
		if (isset($_GET['rep_crs_ref_id'])) {
			$this->ctrl->setParameterByClass(ilObjCourseGUI::class, 'ref_id', $_GET['rep_crs_ref_id']);
			$url = $this->ctrl->getLinkTargetByClass(array( ilRepositoryGUI::class, ilObjCourseGUI::class ));
			$txt = $this->pl->txt('back_to_course');
		} else {
			$url = $this->ctrl->getLinkTarget($this->parent_obj);
			$txt = $this->pl->txt('back');
		}
		$button = ilLinkButton::getInstance();
		$button->setCaption('<b>&lt; ' . $txt . '</b>', false);
		$button->setUrl($url);
		$this->toolbar->addButtonInstance($button);
	}


	/**
	 * Setup columns based on data
	 */
	protected function initColumns() {
		foreach ($this->getColumns() as $k => $v) {
			$this->addColumn($v['txt'], $k, 'auto');
		}
	}


	/**
	 * @return array
	 */
	protected function getColumns() {
		return array(
			'title' => array( 'txt' => $this->pl->txt('title') ),
			'path' => array( 'txt' => $this->pl->txt('path') ),
			'firstname' => array( 'txt' => $this->pl->txt('firstname') ),
			'lastname' => array( 'txt' => $this->pl->txt('lastname') ),
			'country' => array( 'txt' => $this->pl->txt('country') ),
			'department' => array( 'txt' => $this->pl->txt('department') ),
			'org_units' => array( 'txt' => $this->pl->txt('org_units'), 'default' => true ),
			'grade' => array( 'txt' => $this->pl->txt('grade') ),
			'comments' => array( 'txt' => $this->pl->txt('comments') ),
			'active' => array(
				'txt' => $this->pl->txt('active'),
				'formatter' => ilReportingFormatter::FORMAT_INT_YES_NO,
			),
			'status_changed' => array( 'txt' => $this->pl->txt('status_changed') ),
			'user_status' => array(
				'txt' => $this->pl->txt('user_status'),
				'formatter' => ilReportingFormatter::FORMAT_INT_STATUS,
			),
		);
	}


	/**
	 * Excel Version of Fill Header. Likely to
	 * be overwritten by derived class.
	 *
	 * @param ilExcel $a_excel excel wrapper
	 * @param int     $a_row   row counter
	 */
	protected function fillHeaderExcel(ilExcel $a_excel, &$a_row) {
		$col = 0;
		foreach ($this->getColumns() as $column) {
			$title = strip_tags($column["txt"]);
			if ($title) {
				$a_excel->setCell($a_row, $col ++, $title);
			}
		}
		$a_excel->setBold("A" . $a_row . ":" . $a_excel->getColumnCoord($col - 1) . $a_row);
	}


	/**
	 * Excel Version of Fill Row. Most likely to
	 * be overwritten by derived class.
	 *
	 * @param ilExcel $a_excel excel wrapper
	 * @param int     $a_row   row counter
	 * @param array   $a_set   data array
	 */
	protected function fillRowExcel(ilExcel $a_excel, &$a_row, $a_set) {
		$col = 0;

		foreach ($this->getColumns() as $key => $column) {
			$formatter = isset($column['formatter']) ? $column['formatter'] : NULL;
			$value = $this->formatter->format($a_set[$key], $formatter);

			if (is_array($a_set[$key])) {
				$value = implode(', ', $a_set[$key]);
			}
			$a_excel->setCell($a_row, $col ++, $value);
		}
	}


	/**
	 * @param object $a_csv
	 * @param array  $a_set
	 */
	protected function fillRowCSV($a_csv, $a_set) {
		foreach ($this->getColumns() as $k => $v) {
			if (!in_array($k, $this->getIgnoredCols())) {
				if (isset($a_set[$k])) {
					$formatter = isset($v['formatter']) ? $v['formatter'] : NULL;
					$value = $this->formatter->format($a_set[$k], $formatter);
				} else {
					$value = '';
				}
				$a_csv->addColumn(strip_tags($value));
			}
		}
		$a_csv->addRow();
	}


	/**
	 * @param ilFormPropertyGUI $item
	 * @param bool              $optional
	 */
	protected function addFilterItemWithValue($item, $optional = false) {
		$this->addFilterItem($item, $optional);
		$value = $this->getFilterItemValue($item);
		$this->filter_names[$item->getPostVar()] = $value;
	}


	/**
	 * Return value of a filter depending on the InputGUI class
	 *
	 * @param ilFormPropertyGUI $item
	 *
	 * @return bool|object|string
	 */
	protected function getFilterItemValue($item) {
		/**
		 * @var ilFormPropertyGUI $item
		 */
		$value = '';
		$item->readFromSession();
		switch (get_class($item)) {
			case ilSelectInputGUI::class:
				/** @var ilSelectInputGUI $item */
				$value = $item->getValue();
				break;
			case ilCheckboxInputGUI::class:
				/** @var ilCheckboxInputGUI $item */
				$value = $item->getChecked();
				break;
			case ilDateTimeInputGUI::class:
				/** @var ilDateTimeInputGUI $item */
				// Why is this necessary? Bug? ilDateTimeInputGUI::clearFromSession() has no effect...
				if ($this->ctrl->getCmd() == ilReportingGUI::CMD_RESET_FILTER_REPORT) {
					$item->setDate(NULL);
				}
				$date = $item->getDate();
				if ($date) {
					$value = $date;
				}
				break;
			default:
				$value = $item->getValue();
				break;
		}

		return $value;
	}

	/******************************************************
	 * Getters & Setters
	 ******************************************************/

	/**
	 * Get all the filter with the current value from session
	 *
	 * @return array
	 */
	public function getFilterNames() {
		foreach ($this->getFilterItems() as $item) {
			$this->filter_names[$item->getPostVar()] = $this->getFilterItemValue($item);
		}

		return $this->filter_names;
	}


	/**
	 * @param array $ignored_cols
	 */
	public function setIgnoredCols($ignored_cols) {
		$this->ignored_cols = $ignored_cols;
	}


	/**
	 * @return array
	 */
	public function getIgnoredCols() {
		return $this->ignored_cols;
	}


	/**
	 * @param array $date_cols
	 */
	public function setDateCols($date_cols) {
		$this->date_cols = $date_cols;
	}


	/**
	 * @return array
	 */
	public function getDateCols() {
		return $this->date_cols;
	}


	/**
	 * @param string $date_format
	 */
	public function setDateFormat($date_format) {
		$this->date_format = $date_format;
	}


	/**
	 * @return string
	 */
	public function getDateFormat() {
		return $this->date_format;
	}
}
