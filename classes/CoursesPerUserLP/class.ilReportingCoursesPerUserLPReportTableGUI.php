<?php
require_once(dirname(dirname(__FILE__)) . '/class.ilReportingReportTableGUI.php');
require_once('class.ilReportingCoursesPerUserLPPdfExport.php');
require_once('class.ilReportingCoursesPerUserLPExcelExport.php');

/**
 * Report table for the report CoursesPerUser
 *
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 * @version           $Id:
 */
class ilReportingCoursesPerUserLPReportTableGUI extends ilReportingReportTableGUI {

	public function __construct($a_parent_obj, $a_parent_cmd) {
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setRowTemplate('tpl.template_row.colspan.html', $this->pl->getDirectory());
		$this->ignored_cols = array_merge($this->ignored_cols, array( 'sort_user', '_objects' ));
	}


	public function exportDataCustom($format, $send = false) {
		switch ($format) {
			case ilReportingGUI::EXPORT_PDF:
				$export = new ilReportingCoursesPerUserLPPdfExport();
				$export->setCsvData($this->getData());
				$export->setCsvColumns($this->getColumns());
				$export->setCsvObjectColumns($this->getAdditionalColumns());
				$export->setReportGroups(array( 'id' => 'group_user', 'obj_id' => 'group_course' ));
				$export->execute();
				break;
			case ilReportingGUI::EXPORT_EXCEL_FORMATTED:
				$export = new ilReportingCoursesPerUserLPExcelExport('courses_per_user_'
				                                                     . date('Y-m-d'));
				$export->execute($this->getData());
				break;
		}
	}


	/**
	 * @param array $a_set
	 */
	public function fillRow($a_set) {
		parent::fillRow($a_set);
		if (count($a_set['_objects'])) {
			$this->tpl->setCurrentBlock('colspan');
			$this->tpl->setVariable('N_COLUMNS', count($this->getColumns()));
			$this->tpl->setVariable('ROW_DETAILS', $this->renderObjectsTable($a_set['_objects']));
			$this->tpl->parseCurrentBlock();
		}
	}


	/**
	 * @param object $a_csv
	 * @param array  $a_set
	 */
	protected function fillRowCSV($a_csv, $a_set) {
		parent::fillRowCSV($a_csv, $a_set);
		// Display each object in course as row
		if (count($a_set['_objects'])) {
			foreach ($a_set['_objects'] as $object) {
				foreach ($this->getColumns() as $v) {
					$a_csv->addColumn('');
				}
				foreach ($this->getAdditionalColumns() as $k => $v) {
					$formatter = isset($v['formatter']) ? $v['formatter'] : null;
					$value = $this->formatter->format($object[$k], $formatter);
					$a_csv->addColumn($value);
				}
				$a_csv->addRow();
			}
		}
	}


	/**
	 * @return array
	 */
	protected function getAdditionalColumns() {
		return array(
			'object_title'          => array( 'txt' => $this->pl->txt('object_title') ),
			'object_percentage'     => array(
				'txt'       => $this->pl->txt('object_percentage'),
				'formatter' => ilReportingFormatter::FORMAT_STR_PERCENTAGE,
			),
			'object_status'         => array(
				'txt'       => $this->pl->txt('object_status'),
				'formatter' => ilReportingFormatter::FORMAT_INT_STATUS,
			),
			'object_type'           => array(
				'txt'       => $this->pl->txt('object_percentage'),
				'formatter' => ilReportingFormatter::FORMAT_STR_OBJECT_TYPE,
			),
			'object_status_changed' => array( 'txt'       => $this->pl->txt('object_status_changed'),
			                                  'formatter' => ilReportingFormatter::FORMAT_STR_DATE,
			),
		);
	}


	/**
	 * Excel Version of Fill Header. Likely to
	 * be overwritten by derived class.
	 *
	 * @param    ilExcel $a_excel excel wrapper
	 * @param    int     $a_row   row counter
	 */
	protected function fillHeaderExcel(ilExcel $a_excel, &$a_row) {
		$col = 0;
		$all_columns = array_merge($this->getColumns(), $this->getAdditionalColumns());
		foreach ($all_columns as $column) {
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
	 * @param    ilExcel $a_excel excel wrapper
	 * @param    int     $a_row   row counter
	 * @param    array   $a_set   data array
	 */
	protected function fillRowExcel(ilExcel $a_excel, &$a_row, $a_set) {
		parent::fillRowExcel($a_excel, $a_row, $a_set);

		if (count($a_set['_objects'])) {
			foreach ($a_set['_objects'] as $object) {
				$col = count($this->getColumns());
				$a_row ++;
				foreach ($this->getAdditionalColumns() as $k => $v) {
					$formatter = isset($v['formatter']) ? $v['formatter'] : null;
					$value = $this->formatter->format($object[$k], $formatter);
					$a_excel->setCell($a_row, $col ++, $value);
				}
			}
		}
	}


	/**
	 * Render a table displaying the objects in course
	 *
	 * @param array $objects
	 *
	 * @return string
	 */
	protected function renderObjectsTable(array $objects) {
		$table = $this->pl->getTemplate('tpl.objects_table.html', true, true);
		foreach (array_keys($this->getAdditionalColumns()) as $column) {
			$table->setVariable(strtoupper("TH_{$column}"), $this->pl->txt($column));
		}
		foreach ($objects as $object) {
			$table->setCurrentBlock('rows');
			foreach ($this->getAdditionalColumns() as $k => $v) {
				$formatter = isset($v['formatter']) ? $v['formatter'] : null;
				$value = $this->formatter->format($object[$k], $formatter);
				$table->setVariable(strtoupper($k), $value);
			}
			$table->parseCurrentBlock();
		}

		return $table->get();
	}


	/**
	 * CSV Version of Fill Header. Likely to
	 * be overwritten by derived class.
	 *
	 * @param   object $a_csv current file
	 */
	protected function fillHeaderCSV($a_csv) {
		$all_columns = array_merge($this->getColumns(), $this->getAdditionalColumns());
		foreach ($all_columns as $column) {
			$a_csv->addColumn($column['txt']);
		}
		$a_csv->addRow();
	}


	/**
	 * Modify back link when coming from ilreportingcoursesperusergui
	 */
	protected function initToolbar() {
		if (isset($_GET['from'])) {
			$class = $_GET['from'];
			$url = $this->ctrl->getLinkTargetByClass($class, 'report');
			$txt = $this->pl->txt('back');
			$this->toolbar->addButton('<b>&lt; ' . $txt . '</b>', $url);
		} else {
			parent::initToolbar();
		}
	}
}