<?php

/**
 * Class ilReportingUsersPerCourseExcelExport
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */
class ilReportingUsersPerCourseLPExcelExport extends ilReportingExcelExport {

	public function __construct($filename = '') {
		parent::__construct($filename);
	}


	/**
	 * Generate export
	 *
	 * @param array $data
	 */
	public function execute(array $data) {
		$data = $this->formatData($data);
		ob_start();
		foreach ($data as $courses) {
			$title = $courses[0]['title'];
			$worksheet = $this->workbook->addWorksheet();
			// Write in worksheet
			$row = 0;
			$this->write($worksheet, $row, 0, $title, $this->h1);
			$row ++;
			$this->write($worksheet, ++ $row, 0, $this->pl->txt('path'));
			$this->write($worksheet, $row, 1, $courses[0]['path']);
			$this->write($worksheet, ++ $row, 0, $this->pl->txt('date_of_report'));
			$this->write($worksheet, $row, 1, $this->formatter->formatCurrentDate());
			$this->write($worksheet, ++ $row, 0, $this->pl->txt('owner_of_report'));
			$this->write($worksheet, $row, 1, $this->user->getFirstname() . ' ' . $this->user->getLastname());
			$row += 2;
			// List courses: Title of columns
			$this->write($worksheet, $row, 0, $this->pl->txt('firstname'), $this->h3);
			$this->write($worksheet, $row, 1, $this->pl->txt('lastname'), $this->h3);
			$this->write($worksheet, $row, 2, $this->pl->txt('country'), $this->h3);
			$this->write($worksheet, $row, 3, $this->pl->txt('department'), $this->h3);
			$this->write($worksheet, $row, 4, $this->pl->txt('grade'), $this->h3);
			$this->write($worksheet, $row, 5, $this->pl->txt('comments'), $this->h3);
			$this->write($worksheet, $row, 6, $this->pl->txt('active'), $this->h3);
			$this->write($worksheet, $row, 7, $this->pl->txt('user_status'), $this->h3);
			$this->write($worksheet, $row, 8, $this->pl->txt('status_changed'), $this->h3);
			foreach ($courses as $user) {
				$this->write($worksheet, ++ $row, 0, $user['firstname']);
				$this->write($worksheet, $row, 1, $user['lastname']);
				$this->write($worksheet, $row, 2, $user['country']);
				$this->write($worksheet, $row, 3, $user['department']);
				$this->write($worksheet, $row, 4, $user['grade']);
				$this->write($worksheet, $row, 5, $user['comments']);
				$this->write($worksheet, $row, 6, $this->formatter->format($user['active'], ilReportingFormatter::FORMAT_INT_YES_NO));
				$this->write($worksheet, $row, 7, $this->formatter->format($user['user_status'], ilReportingFormatter::FORMAT_INT_STATUS));
				$this->write($worksheet, $row, 8, $this->formatter->format($user['status_changed'], ilReportingFormatter::FORMAT_STR_DATE));
			}
			// Learning progress table
			$row += 3;
			$this->write($worksheet, $row, 0, $this->pl->txt('name'), $this->h3);
			$this->write($worksheet, $row, 1, $this->pl->txt('object_title'), $this->h3);
			$this->write($worksheet, $row, 2, $this->pl->txt('object_percentage'), $this->h3);
			$this->write($worksheet, $row, 3, $this->pl->txt('object_status'), $this->h3);
			$this->write($worksheet, $row, 4, $this->pl->txt('object_type'), $this->h3);
			$this->write($worksheet, $row, 5, $this->pl->txt('object_status_changed'), $this->h3);
			foreach ($courses as $user) {
				if (count($user['_objects'])) {
					$this->write($worksheet, ++ $row, 0, $user['firstname'] . ' ' . $user['lastname']);
					$i = 0;
					foreach ($user['_objects'] as $object) {
						if ($i) {
							$row ++;
						}
						$this->write($worksheet, $row, 1, $object['object_title']);
						$this->write($worksheet, $row, 2, (int)$object['object_percentage']);
						$this->write($worksheet, $row, 3, $this->formatter->format($object['object_status'], ilReportingFormatter::FORMAT_INT_STATUS));
						$type = ($object['object_type']) ? $this->lng->txt($object['object_type']) : '';
						$this->write($worksheet, $row, 4, $type);
						$this->write($worksheet, $row, 5, $this->formatter->format($object['object_status_changed'], ilReportingFormatter::FORMAT_STR_DATE));
						$i ++;
					}
				}
			}
			$this->autoSizeColumns($worksheet);
		}
		ob_end_clean();
		$this->workbook->close();
	}
}
