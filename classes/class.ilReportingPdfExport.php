<?php

use srag\JasperReport\Reporting\JasperReport;

/**
 * Class ilReportingPdfExport
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */
abstract class ilReportingPdfExport {

	const CSV_FILENAME = 'report_temp.csv';
	const CSV_SEPARATOR = ';';
	/**
	 * @var ilReportingPlugin
	 */
	protected $pl;
	/**
	 * @var bool|string
	 */
	protected $templates_path = '';
	/**
	 * @var string
	 */
	protected $report_title = '';
	/**
	 * @var string
	 */
	protected $output_filename = '';
	/**
	 * @var string
	 */
	protected $template_filename = '';
	/**
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var array
	 */
	protected $report_groups = array();
	/**
	 * @var array
	 */
	protected $csv_data = array();
	/**
	 * @var array
	 */
	protected $csv_columns = array();
	/**
	 * @var ilReportingFormatter
	 */
	protected $formatter;
	/**
	 * @var string
	 */
	protected $temp_dir = '';


	public function __construct() {
		global $DIC;
		$this->pl = ilReportingPlugin::getInstance();
		$this->templates_path = ilReportingConfig::getValue('jasper_reports_templates_path');
		$this->user = $DIC->user();
		$this->formatter = ilReportingFormatter::getInstance();
	}


	/**
	 * Execute the report
	 */
	public function execute() {
		$csv_file = $this->buildCSVFile();
		$report = new JasperReport($this->templates_path . $this->template_filename, $this->output_filename);
		$report->setDataSource(JasperReport::DATASOURCE_CSV);
		$report->setCsvFieldDelimiter(';');
		$report->setCsvFile($csv_file);
		$params = $this->getPdfReportParameters();
		$report->setParameters($params);
		$report->downloadFile(true);
	}


	/**
	 * Remove temp dir
	 */
	public function __destruct() {
		ilUtil::delDir($this->temp_dir);
	}


	/**
	 * Return parameters that are passed to Jasper Report
	 *
	 * @return array
	 */
	protected function getPdfReportParameters() {
		$params = array(
			'firstname' => $this->pl->txt('firstname'),
			'lastname' => $this->pl->txt('lastname'),
			'department' => $this->pl->txt('department'),
			'country' => $this->pl->txt('country'),
			'grade' => $this->pl->txt('grade'),
			'comments' => $this->pl->txt('comments'),
			'status_changed' => $this->pl->txt('status_changed'),
			'user_status' => $this->pl->txt('user_status'),
			'title' => $this->pl->txt('title'),
			'path' => $this->pl->txt('path'),
			'owner_report' => $this->pl->txt('owner_of_report'),
			'owner_name' => $this->user->getPresentationTitle(),
			'report_title' => $this->report_title
		);
		// Check if a header image is specified
		$img = ilReportingConfig::getValue('header_image');
		if ($img && is_file($img)) {
			$params['header_image'] = $img;
		}

		return $params;
	}


	/**
	 * Create a temporary csv file containing the report data
	 *
	 * @return string Path to CSV file
	 */
	protected function buildCSVFile() {
		$csv = new ilCSVWriter();
		$csv->setSeparator(self::CSV_SEPARATOR);
		ob_start();
		$columns = array_merge(array_values($this->getReportGroups()), array_keys($this->getCsvColumns()));
		foreach ($columns as $column) {
			$csv->addColumn($column);
		}
		$csv->addRow();
		foreach ($this->getCsvData() as $data) {
			foreach ($this->getReportGroups() as $field => $group) {
				$csv->addColumn($data[$field]);
			}
			foreach ($this->getCsvColumns() as $k => $v) {
				$formatter = isset($v['formatter']) ? $v['formatter'] : NULL;
				$value = $this->formatter->format($data[$k], $formatter);
				$csv->addColumn(strip_tags($value));
			}
			$csv->addRow();
		}
		ob_end_clean();
		$csv_file = $this->getCsvFilePath();
		file_put_contents($csv_file, $csv->getCSVString());

		return $csv_file;
	}


	/**
	 * Make temporary dir in ilias directory
	 */
	protected function makeTempDir() {
		if (!$this->temp_dir) {
			$this->temp_dir = ilUtil::ilTempnam();
			ilUtil::makeDir($this->temp_dir);
		}
	}


	/**
	 * @return string
	 */
	protected function getCsvFilePath() {
		$this->makeTempDir();

		return $this->temp_dir . DIRECTORY_SEPARATOR . self::CSV_FILENAME;
	}



	/**
	 * Getters & Setters
	 */

	/**
	 * @param string $output_filename
	 */
	public function setOutputFilename($output_filename) {
		$this->output_filename = $output_filename;
	}


	/**
	 * @return string
	 */
	public function getOutputFilename() {
		return $this->output_filename;
	}


	/**
	 * @param array $report_groups
	 */
	public function setReportGroups($report_groups) {
		$this->report_groups = $report_groups;
	}


	/**
	 * @return array
	 */
	public function getReportGroups() {
		return $this->report_groups;
	}


	/**
	 * @param string $report_title
	 */
	public function setReportTitle($report_title) {
		$this->report_title = $report_title;
	}


	/**
	 * @return string
	 */
	public function getReportTitle() {
		return $this->report_title;
	}


	/**
	 * @param string $template_filename
	 */
	public function setTemplateFilename($template_filename) {
		$this->template_filename = $template_filename;
	}


	/**
	 * @return string
	 */
	public function getTemplateFilename() {
		return $this->template_filename;
	}


	/**
	 * @param bool|string $templates_path
	 */
	public function setTemplatesPath($templates_path) {
		$this->templates_path = $templates_path;
	}


	/**
	 * @return bool|string
	 */
	public function getTemplatesPath() {
		return $this->templates_path;
	}


	/**
	 * @param array $csv_data
	 */
	public function setCsvData($csv_data) {
		$this->csv_data = $csv_data;
	}


	/**
	 * @return array
	 */
	public function getCsvData() {
		return $this->csv_data;
	}


	/**
	 * @param array $csv_columns
	 */
	public function setCsvColumns($csv_columns) {
		$this->csv_columns = $csv_columns;
	}


	/**
	 * @return array
	 */
	public function getCsvColumns() {
		return $this->csv_columns;
	}
}
