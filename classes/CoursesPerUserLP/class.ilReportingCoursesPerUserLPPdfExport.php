<?php
require_once(dirname(dirname(__FILE__)) . '/class.ilReportingPdfExport.php');

/**
 * Class ilReportingCoursesPerUserPdfExport.
 * PDF Export Courses per User
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */

class ilReportingCoursesPerUserLPPdfExport extends ilReportingPdfExport {

    /**
     * @var array
     */
    protected $csv_object_columns = array();


    public function __construct() {
        parent::__construct();
        $this->report_title = $this->pl->txt('courses_per_user');
        $this->template_filename = 'courses_per_user_lp.jrxml';
        $this->output_filename = 'courses_per_user_lp_' . date('Y-m-d');
    }


    /**
     * Overwrite method because csv data array is formatted differently
     *
     * @return string
     */
    protected function buildCSVFile() {
        $csv = new ilCSVWriter();
        $csv->setSeparator(self::CSV_SEPARATOR);
        ob_start();
        $columns = array_merge(array_values($this->getReportGroups()), array_keys($this->getCsvColumns()), array_keys($this->getCsvObjectColumns()));
        foreach ($columns as $column) {
            $csv->addColumn($column);
        }
        $csv->addColumn('group_object');
        $csv->addRow();
        foreach ($this->getCsvData() as $data) {
            $standard_columns = array();
            foreach ($this->getReportGroups() as $field => $group) {
                $csv->addColumn($data[$field]);
                $standard_columns[] = $data[$field];
            }
            foreach ($this->getCsvColumns() as $k => $v) {
                $formatter = isset($v['formatter']) ? $v['formatter'] : null;
                $value = $this->formatter->format($data[$k], $formatter);
                $csv->addColumn(strip_tags($value));
                $standard_columns[] = strip_tags($value);
            }
            if (isset($data['_objects'])) {
                foreach ($data['_objects'] as $j => $object) {
                    if ($j > 0) {
                        $csv->addRow();
                        $this->writeColumnsToCsv($csv, $standard_columns);
                    }
                    foreach ($this->getCsvObjectColumns() as $k => $v) {
                        $formatter = isset($v['formatter']) ? $v['formatter'] : null;
                        $value = $this->formatter->format($object[$k], $formatter);
                        $csv->addColumn(strip_tags($value));
                    }
                    $csv->addColumn($object['object_id']);
                }
            }
            $csv->addRow();
        }
        ob_end_clean();
        $csv_file = $this->getCsvFilePath();
        file_put_contents($csv_file, $csv->getCSVString());
        return $csv_file;
    }


    /**
     * @param $csv
     * @param array $columns
     */
    protected function writeColumnsToCsv($csv, array $columns) {
        foreach ($columns as $column) {
            $csv->addColumn($column);
        }
    }


    /**
     * @param array $csv_object_columns
     */
    public function setCsvObjectColumns($csv_object_columns)
    {
        $this->csv_object_columns = $csv_object_columns;
    }

    /**
     * @return array
     */
    public function getCsvObjectColumns()
    {
        return $this->csv_object_columns;
    }

} 