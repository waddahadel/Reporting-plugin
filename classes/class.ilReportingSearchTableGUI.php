<?php

/**
 * TableGUI ilReportingSearchTableGUI
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */
abstract class ilReportingSearchTableGUI extends ilTable2GUI {

	protected $filter_names = array();
	/**
	 * @var ilReportingPlugin
	 */
	protected $pl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var \ilToolbarGUI
	 */
	protected $toolbar;
	/**
	 * @var string
	 */
	protected $filter_cmd = ilReportingGUI::CMD_APPLY_FILTER_SEARCH;
	/**
	 * @var string
	 */
	protected $reset_cmd = ilReportingGUI::CMD_RESET_FILTER_SEARCH;
	/**
	 * @var ilReportingFormatter
	 */
	protected $formatter;


	/**
	 * @param ilReportingGUI $a_parent_obj
	 * @param string         $a_parent_cmd
	 */
	function __construct(ilReportingGUI $a_parent_obj, $a_parent_cmd) {
		global $DIC;
		$this->pl = ilReportingPlugin::getInstance();
		$this->ctrl = $DIC->ctrl();
		$this->toolbar = $DIC->toolbar();
		$this->setId($this->ctrl->getCmdClass());
		$this->setPrefix('pre');
		$this->formatter = ilReportingFormatter::getInstance();
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setRowTemplate('tpl.template_row.checkboxes.html', $this->pl->getDirectory());
		$this->setEnableHeader(true);
		$this->setEnableTitle(true);
		$this->setTopCommands(true);
		$this->setShowRowsSelector(true);
		$this->setFormAction($this->ctrl->getFormAction($a_parent_obj));
		$this->initFilter();
		$this->setDisableFilterHiding(true);
		// Setup columns
		$this->addColumn("", "", "1", true);
		$this->setSelectAllCheckbox("id[]");
		foreach ($this->getSelectableColumns() as $k => $v) {
			if ($this->isColumnSelected($k)) {
				$this->addColumn($v['txt'], $k, 'auto');
			}
		}
	}


	/**
	 * @param      $item
	 * @param bool $optional
	 */
	protected function addFilterItemWithValue($item, $optional = false) {
		/**
		 * @var $item ilFormPropertyGUI
		 */
		$this->addFilterItem($item, $optional);
		$item->readFromSession();
		switch (get_class($item)) {
			case ilSelectInputGUI::class:
				/** @var $item ilSelectInputGUI */
				$value = $item->getValue();
				break;
			case ilCheckboxInputGUI::class:
				/** @var $item ilCheckboxInputGUI */
				$value = $item->getChecked();
				break;
			case ilDateTimeInputGUI::class:
				/** @var $item ilDateTimeInputGUI */
				$value = $item->getDate();
				break;
			default:
				$value = $item->getValue();
				break;
		}
		$this->filter_names[$item->getPostVar()] = $value;
	}


	public function getFilterNames() {
		return $this->filter_names;
	}


	/**
	 * @param array $a_set
	 */
	public function fillRow($a_set) {
		$this->tpl->setVariable('ID', $a_set['id']);
		foreach ($this->getSelectableColumns() as $k => $v) {
			if ($this->isColumnSelected($k)) {
				if ($a_set[$k] != '') {
					$this->tpl->setCurrentBlock('td');
					$formatter = (isset($v['formatter'])) ? $v['formatter'] : NULL;
					$value = $this->formatter->format($a_set[$k], $formatter);
					$this->tpl->setVariable('VALUE', $value);
					$this->tpl->parseCurrentBlock();
				} else {
					$this->tpl->setCurrentBlock('td');
					$this->tpl->setVariable('VALUE', '&nbsp;');
					$this->tpl->parseCurrentBlock();
				}
			}
		}
	}
}
