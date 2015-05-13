<?php
namespace Concrete\Package\SkybluesofaPageListPlusFilter\Src;

use Package;

defined('C5_EXECUTE') or die("Access Denied.");

class FilterInstaller
{

    public static function installFilters($pkg)
    {
        $pageListPlusPackage = Package::getByHandle('skybluesofa_page_list_plus');
        if ($pageListPlusPackage) {
            \Concrete\Package\SkybluesofaPageListPlus\Src\Installer::refreshFilters();
        }
    }
}
