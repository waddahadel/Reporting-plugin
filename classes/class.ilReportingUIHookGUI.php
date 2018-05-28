<?php

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * User interface hook class for Reporting-Plugin
 *
 * @author  Alex Killing <alex.killing@gmx.de>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Martin Studer <ms@studer-raimann.ch>
 *
 * @version $Id$
 * @ingroup ServicesUIComponent
 */
class ilReportingUIHookGUI extends ilUIHookPluginGUI {

	const TAB_REPORTS = 'reports';
	/** @var  ilCtrl */
	protected $ctrl;
	/** @var  ilReportingPlugin */
	protected $pl;
	/** @var  ilAccessHandler */
	protected $access;


	function __construct() {
		global $DIC;
		$this->ctrl = $DIC->ctrl();
		$this->pl = ilReportingPlugin::getInstance();
		$this->access = $DIC->access();
		// Display error message in Administration if precondition is not valid
		if (!ilReportingPlugin::checkPreconditions() AND $_GET['ref_id'] == 9) {
			if (get_class($DIC->ui()->mainTemplate()) == ilTemplate::class) {
				ilUtil::sendFailure('Reporting plugin needs CtrlMainMenu (https://svn.ilias.de/svn/ilias/branches/sr/CtrlMainMenu) and either ilRouterGUI (https://svn.ilias.de/svn/ilias/branches/sr/Router)');
			}
		}
	}


	/**
	 * Add a new tab 'reports' for courses which shows the report table for all the members of a
	 * course
	 */
	public function modifyGUI($a_comp, $a_part, $a_par = array()) {
		if ($a_part == 'tabs') {
			if ($this->ctrl->getContextObjType() == 'crs') {
				$crsID = $this->ctrl->getContextObjId();
				if ($crsID) {
					$arr_refId = ilObject2::_getAllReferences($crsID);
					//check Permission "edit learning progress"
					$refId = array_values($arr_refId);
					if ($this->access->checkAccess("edit_learning_progress", "", $refId[0], "", $crsID)) {
						$this->ctrl->setParameterByClass(ilReportingUsersPerCourseGUI::class, 'rep_crs_ref_id', $refId[0]);
						$uri = $this->ctrl->getLinkTargetByClass(array(
							ilUIPluginRouterGUI::class,
							ilReportingUsersPerCourseGUI::class,
						), ilReportingGUI::CMD_REPORT);
						// Write the correct course ID into the session - this is used by the report table
						$_SESSION[ilReportingGUI::SESSION_KEY_IDS] = array( $crsID );
						/** @var ilTabsGUI $ilTabsGUI */
						$ilTabsGUI = $a_par['tabs'];
						$ilTabsGUI->addTab(self::TAB_REPORTS, $this->pl->txt('reports'), $uri, self::TAB_REPORTS);
					}
				}
			}
		}
	}
}

?>