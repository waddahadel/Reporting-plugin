<#1>
<?php
	require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Reporting/vendor/autoload.php";

	ilReportingConfig::updateDB();

	ilReportingConfig::setValue('header_image', ilReportingPlugin::getRootDir() . 'jasper_report/logo.jpg');
?>
<#2>
<?php
/* */
?>
<#3>
<?php
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Reporting/vendor/autoload.php";

ilReportingConfig::updateDB();

if (ilReportingConfig::getValue('jasper_reports_templates_path') === false) {
	ilReportingConfig::setValue('jasper_reports_templates_path', ilReportingPlugin::getRootDir() . 'jasper_report/');
}
?>
