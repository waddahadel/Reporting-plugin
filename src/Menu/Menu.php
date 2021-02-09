<?php

namespace srag\Plugins\Reporting\Menu;

use ILIAS\GlobalScreen\Scope\MainMenu\Factory\AbstractBaseItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ilReportingCoursesPerUserGUI;
use ilReportingCoursesPerUserLPGUI;
use ilReportingPlugin;
use ilReportingUsersPerCourseGUI;
use ilReportingUsersPerCourseLPGUI;
use ilReportingUsersPerTestGUI;
use ilUIPluginRouterGUI;
use ilUtil;
use srag\DIC\Reporting\DICTrait;

/**
 * Class Menu
 *
 * @package srag\Plugins\Reporting\Menu
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Menu extends AbstractStaticPluginMainMenuProvider
{

    use DICTrait;

    const PLUGIN_CLASS_NAME = ilReportingPlugin::class;


    /**
     * @inheritDoc
     */
    public function getStaticTopItems() : array
    {
        return [
            $this->symbol($this->mainmenu->topParentItem($this->if->identifier(ilReportingPlugin::PLUGIN_ID . "_top"))
                ->withTitle(self::plugin()->translate("reports"))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                }))
        ];
    }


    /**
     * @inheritDoc
     */
    public function getStaticSubItems() : array
    {
        $parent = $this->getStaticTopItems()[0];

        return [
            $this->symbol($this->mainmenu->link($this->if->identifier(ilReportingPlugin::PLUGIN_ID . "_courses_per_user"))
                ->withParent($parent->getProviderIdentification())
                ->withTitle(self::plugin()->translate("courses_per_user"))
                ->withAction(self::dic()->ctrl()->getLinkTargetByClass([
                    ilUIPluginRouterGUI::class,
                    ilReportingCoursesPerUserGUI::class
                ]))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return ilReportingCoursesPerUserGUI::hasAccess();
                })),
            $this->symbol($this->mainmenu->link($this->if->identifier(ilReportingPlugin::PLUGIN_ID . "_users_per_course"))
                ->withParent($parent->getProviderIdentification())
                ->withTitle(self::plugin()->translate("users_per_course"))
                ->withAction(self::dic()->ctrl()->getLinkTargetByClass([
                    ilUIPluginRouterGUI::class,
                    ilReportingUsersPerCourseGUI::class
                ]))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return ilReportingUsersPerCourseGUI::hasAccess();
                })),
            $this->symbol($this->mainmenu->link($this->if->identifier(ilReportingPlugin::PLUGIN_ID . "_users_per_test"))
                ->withParent($parent->getProviderIdentification())
                ->withTitle(self::plugin()->translate("users_per_test"))
                ->withAction(self::dic()->ctrl()->getLinkTargetByClass([
                    ilUIPluginRouterGUI::class,
                    ilReportingUsersPerTestGUI::class
                ]))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return ilReportingUsersPerTestGUI::hasAccess();
                })),
            $this->symbol($this->mainmenu->link($this->if->identifier(ilReportingPlugin::PLUGIN_ID . "_courses_per_user_detailed"))
                ->withParent($parent->getProviderIdentification())
                ->withTitle(self::plugin()->translate("courses_per_user_detailed"))
                ->withAction(self::dic()->ctrl()->getLinkTargetByClass([
                    ilUIPluginRouterGUI::class,
                    ilReportingCoursesPerUserLPGUI::class
                ]))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return ilReportingCoursesPerUserLPGUI::hasAccess();
                })),
            $this->symbol($this->mainmenu->link($this->if->identifier(ilReportingPlugin::PLUGIN_ID . "_users_per_course_detailed"))
                ->withParent($parent->getProviderIdentification())
                ->withTitle(self::plugin()->translate("users_per_course_detailed"))
                ->withAction(self::dic()->ctrl()->getLinkTargetByClass([
                    ilUIPluginRouterGUI::class,
                    ilReportingUsersPerCourseLPGUI::class
                ]))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return ilReportingUsersPerCourseLPGUI::hasAccess();
                }))
        ];
    }


    /**
     * @param AbstractBaseItem $entry
     *
     * @return AbstractBaseItem
     */
    protected function symbol(AbstractBaseItem $entry) : AbstractBaseItem
    {
        if (self::version()->is6()) {
            $entry = $entry->withSymbol(self::dic()->ui()->factory()->symbol()->icon()->custom(ilUtil::getImagePath("outlined/icon_enrl.svg"), ilReportingPlugin::PLUGIN_NAME));
        }

        return $entry;
    }
}
