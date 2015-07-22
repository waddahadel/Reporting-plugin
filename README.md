ILIAS Reporting Plugin
======================

The Reporting Plugin gives you the ability to provide detailed reports for your management.

Available reports:
- Courses per Users
- Users per Course
- Users per Test

##IMPORTANT NOTES
This plugin has dependencies on other plugins and services which must be installed
before the Reporting plugin:
* [CtrlMainMenu Plugin](https://github.com/studer-raimann/CtrlMainMenu)

ILIAS < 5.x
* [ilRouterGUI Service](https://github.com/studer-raimann/RouterService)

Optionally, to create PDF reports
* [JasperReport Library](https://github.com/studer-raimann/JasperReport)

Please read the install instructions in the documentation here:
http://www.ilias.de/docu/goto_docu_wiki_1357_Reporting_Plugin.html

### Update from ILIAS 4.x to 5.x
Version `1.2.0` of this plugin adds support for `ILIAS 5.x`. If you updated your ILIAS installation from version `4.x` to `5.x` and this plugin was installed before, the mainmenu entries for the reports probably don't work correct anymore. This is due to the Router Service which is now part of the ILIAS core. To fix it, navigate to `Administration > Plugins > CtrlMainMenu` and configure the entries. Click on "Edit Entries" on the dropdown entry "Reports". Edit each entry and make the following changes:

Replace each occurence of `ilRouterGUI` with `ilUIPluginRouterGUI` under the setting "GUI classes, comma separated". E.g. `ilRouterGUI,ilReportingCoursesPerUserGUI` becomes `ilUIPluginRouterGUI,ilReportingCoursesPerUserGUI`

Make sure that the links are working correctly after saving the entries.

Installation
------------
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/
git clone https://github.com/studer-raimann/Reporting.git
```
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.

###Contact
studer + raimann ag  
Waldeggstrasse 72  
3097 Liebefeld  
Switzerland 

info@studer-raimann.ch  
www.studer-raimann.ch  
