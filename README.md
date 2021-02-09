ILIAS Reporting Plugin
======================

The Reporting Plugin gives you the ability to provide detailed reports for your management.

Available reports:
- Courses per Users
- Users per Course
- Users per Test

Please read the install instructions in the documentation here:
http://www.ilias.de/docu/goto_docu_wiki_1357_Reporting_Plugin.html

Installation
------------
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/
git clone https://github.com/studer-raimann/Reporting.git
```
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.

### Requirements
* ILIAS 5.4 or ILIAS 6
* PHP >=7.0
* Java 8 (Using JasperReport for PDF export)

### Adjustment suggestions
* Adjustment suggestions by pull requests
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLREPORT
* Bug reports under https://jira.studer-raimann.ch/projects/PLREPORT
* For external users you can report it at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLREPORT

### ILIAS Plugin SLA
Wir lieben und leben die Philosophie von Open Source Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.
