<?php

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Reporting Configuration
 *
 * @author  Alex Killing <alex.killing@gmx.de>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id$
 *
 */
class ilReportingConfigGUI extends ilPluginConfigGUI {

	const CMD_CONFIGURE = 'configure';
	const CMD_SAVE = 'save';
	/** @var array */
	protected $fields = array();
	/**
	 * @var ilReportingPlugin
	 */
	protected $pl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;
	/**
	 * @var ilPropertyFormGUI
	 */
	protected $form;


	function __construct() {
		global $DIC;

		$this->ctrl = $DIC->ctrl();
		$this->tpl = $DIC->ui()->mainTemplate();
		$this->tabs = $DIC->tabs();
		$this->pl = ilReportingPlugin::getInstance();
	}


	/**
	 * Handles all commmands, default is 'configure'
	 */
	public function performCommand($cmd) {
		switch ($cmd) {
			case self::CMD_CONFIGURE:
			case self::CMD_SAVE:
				$this->$cmd();
				break;
		}
	}


	/**
	 * Configure screen
	 */
	public function configure() {
		$this->initConfigurationForm();
		$this->setFormValues();
		$this->tpl->setContent($this->form->getHTML());
	}


	/**
	 * Save config
	 */
	public function save() {
		$this->initConfigurationForm();
		if ($this->form->checkInput()) {
			foreach ($this->getFields() as $key => $item) {
				ilReportingConfig::setValue($key, $this->form->getInput($key));
				if (is_array($item['subelements'])) {
					foreach ($item['subelements'] as $subkey => $subitem) {
						ilReportingConfig::setValue($key . '_' . $subkey, $this->form->getInput($key . '_' . $subkey));
					}
				}
			}
			$this->saveAdditionalFields();
			ilUtil::sendSuccess($this->pl->txt('conf_saved'), true);
			$this->ctrl->redirect($this, self::CMD_CONFIGURE);
		} else {
			$this->form->setValuesByPost();
			$this->tpl->setContent($this->form->getHtml());
		}
	}


	protected function saveAdditionalFields() {
		ilReportingConfig::setValue('restricted_user_access', $this->form->getInput('restricted_user_access'));
	}


	/**
	 * Set form values
	 */
	protected function setFormValues() {
		foreach ($this->getFields() as $key => $item) {
			$values[$key] = ilReportingConfig::getValue($key);
			if (is_array($item['subelements'])) {
				foreach ($item['subelements'] as $subkey => $subitem) {
					$values[$key . '_' . $subkey] = ilReportingConfig::getValue($key . '_' . $subkey);
				}
			}
		}
		$this->setAdditionalFormValues($values);
		$this->form->setValuesByArray($values);
	}


	protected function setAdditionalFormValues(&$values) {
		$values['restricted_user_access'] = ilReportingConfig::getValue('restricted_user_access');
	}


	/**
	 * @return ilPropertyFormGUI
	 */
	protected function initConfigurationForm() {
		$this->form = new ilPropertyFormGUI();

		$this->initCustomConfigForm($this->form);

		foreach ($this->getFields() as $key => $item) {
			$field = new $item['type']($this->pl->txt($key), $key);
			if ($item['info']) {
				$field->setInfo($this->pl->txt($key . '_info'));
			}
			if (is_array($item['subelements'])) {
				foreach ($item['subelements'] as $subkey => $subitem) {
					$subfield = new $subitem['type']($this->pl->txt($key . '_' . $subkey), $key . '_' . $subkey);
					if ($subitem['info']) {
						$subfield->setInfo($this->pl->txt($key . '_info'));
					}
					$field->addSubItem($subfield);
				}
			}
			$this->form->addItem($field);
		}
		$this->form->addCommandButton(self::CMD_SAVE, $this->pl->txt('save'));
		$this->form->setTitle($this->pl->txt('configuration'));
		$this->form->setFormAction($this->ctrl->getFormAction($this));

		return $this->form;
	}


	/**
	 * For additional form elements which are not easily configurable.
	 *
	 * @param ilPropertyFormGUI $form
	 */
	protected function initCustomConfigForm(&$form) {
		$item = new ilRadioGroupInputGUI($this->pl->txt('restricted_user_access'), 'restricted_user_access');

		$option = new ilRadioOption($this->pl->txt('no_restriction'), ilReportingConfig::RESTRICTED_NONE);
		$item->addOption($option);

		$option = new ilRadioOption($this->pl->txt('restricted_by_local_readability'), ilReportingConfig::RESTRICTED_BY_LOCAL_READABILITY);
		$option->setInfo($this->pl->txt('restricted_by_local_readability_description'));
		$item->addOption($option);

		$option = new ilRadioOption($this->pl->txt('restricted_by_org_units'), ilReportingConfig::RESTRICTED_BY_ORG_UNITS);
		$option->setInfo($this->pl->txt('restricted_by_org_units_description'));
		$item->addOption($option);

		$form->addItem($item);
	}


	/**
	 * Return the configuration fields
	 *
	 * @return array
	 */
	protected function getFields() {
		$this->fields = array(
			'jasper_reports_templates_path' => array(
				'type' => ilTextInputGUI::class,
				'info' => true,
			),
			'header_image' => array(
				'type' => ilTextInputGUI::class,
				'info' => true,
			),
		);

		return $this->fields;
	}
}

?>