<?php
namespace Concrete\Package\SkybluesofaPageListPlusFilter\Src;

use Package;

defined('C5_EXECUTE') or die("Access Denied.");

class FilterInstaller
{

    /*
     * The functionality encapsulated in this method is all you need to install your filters.
     */
    public static function installFilters()
    {
        // Get the Page List+ package first
        $pageListPlusPackage = Package::getByHandle('skybluesofa_page_list_plus');

        // If the page List+ Package exists, then we'll refresh all filters for all installed packages
        if ($pageListPlusPackage) {
            \Concrete\Package\SkybluesofaPageListPlus\Src\Installer::refreshFilters();
        }
    }
}
