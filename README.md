ILIAS Reporting Plugin
======================

The Reporting Plugin gives you the ability to provide detailed reports for your management.

Available reports:
- Courses per Users
- Users per Course
- Users per Test

IMPORTANT NOTES
---------------
This plugin has dependencies on other plugins and services which must be installed
before the Reporting plugin:
- [CtrlMainMenu Plugin](https://github.com/studer-raimann/CtrlMainMenu)
- [ilRouterGUI Service](https://github.com/studer-raimann/RouterService)
- [JasperReport Library](https://github.com/studer-raimann/JasperReport) (optional, only needed in order to create PDF reports)

Please read the install instructions in the documentation here:
http://www.ilias.de/docu/goto_docu_wiki_1357_Reporting_Plugin.html

###Update to v. 1.0.5
If you update the plugin to this version, make sure also to update the CtrlMainMenu plugin and the Jasper Report library

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
