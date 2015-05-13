<?php
namespace Concrete\Package\SkybluesofaPageListPlusFilter;

use \Symfony\Component\ClassLoader\MapClassLoader as SymfonyMapClassloader;
use Concrete\Package\SkybluesofaPageListPlusFilter\Src\FilterInstaller;
use Package;
use Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;

class Controller extends Package
{
    protected $pkgHandle = 'skybluesofa_page_list_plus_filter';

    protected $appVersionRequired = '5.7.3';
    protected $pkgVersion = '0.9.0';

    public static $pageListPlusFilters = array(
        'page_list_plus_attribute' => 'PageListPlusAttribute',
    );

    public function getPackageName()
    {
        return t("Page List+ Filter Starter Package");
    }

    public function getPackageDescription()
    {
        return t("Starting point for creating a Page List+ Filter");
    }

    public function install()
    {
        // Setup some stuff that will make the installation work properly
        $this->setupForFilterInstallation();

        // Install the package and get a reference to it
        $package = parent::install();

        // Run some code that will install the preview attributes
        $this->installAttributes($package);

        // This is really what we've come here for; this shows how to install
        // filters into Page List+
        FilterInstaller::installFilters();
    }

    public function upgrade()
    {
        // Setup some stuff that will make the installation work properly
        $this->setupForFilterInstallation();

        // Upgrade the package and get a reference to it
        parent::upgrade();
        $package = $this->getByID($this->getPackageID());

        // Run some code that will install the preview attributes, if they haven't already been installed
        $this->installAttributes($package);

        // This is really what we've come here for; this shows how to install
        // filters into Page List+
        FilterInstaller::installFilters();
    }

    private function installAttributes(Package $package)
    {
        // Install a generic attribute type and associate it with Collections.
        
        // I prefer to put this in a separate class, but I'm trying to keep things as simple as possible.
        $this->installAttributeTypes($package);
        $this->associateAttributesWithCollection($package);
        $this->installCollectionAttributes($package);
    }

    private function installAttributeTypes(Package $package)
    {
        $attributesToInstall = array(
            'page_list_plus_attribute' => array('name' => 'Page List+ Attribute')
        );
        foreach ($attributesToInstall as $attributeHandle => $meta) {
            $existingAttribute = AttributeType::getByHandle($attributeHandle);
            if (!$existingAttribute) {
                AttributeType::add($attributeHandle, $meta['name'], $package);
            }
        }
    }

    private function associateAttributesWithCollection(Package $package)
    {
        $attributesTypesToAssociate = array(
            'page_list_plus_attribute'
        );

        $collectionCategory = AttributeKeyCategory::getByHandle('collection');

        foreach ($attributesTypesToAssociate as $attributeTypeHandle) {
            $attributeType = AttributeType::getByHandle($attributeTypeHandle);
            if (!$attributeType->isAssociatedWithCategory($collectionCategory)) {
                $collectionCategory->associateAttributeKeyType($attributeType);
            }
        }
    }

    private function installCollectionAttributes(Package $package)
    {
        $attributesToAddToCollections = array(
            'page_list_plus_attribute' => array('name' => 'Page List+ Attribute', 'type' => 'page_list_plus_attribute', 'properties' => array('akIsSearchable' => 1, 'akIsSearchableIndexed' => 1)),
        );

        foreach ($attributesToAddToCollections as $collectionAttributeHandle => $meta) {
            $attributeType = AttributeType::getByHandle($meta['type']);
            $properties = array('akHandle' => $collectionAttributeHandle, 'akName' => t($meta['name']));
            if (isset($meta['properties'])) {
                $properties = array_merge($properties, $meta['properties']);
            }
            $collectionAttribute = CollectionAttributeKey::getByHandle($collectionAttributeHandle);
            if (!is_object($collectionAttribute)) {
                CollectionAttributeKey::add($attributeType, $properties, $package);
            } else {
                $collectionAttribute->update($properties);
            }
        }
    }

    private function setupForFilterInstallation()
    {
        $this->registerFilterInstaller();
        $this->excludeFilesFromAnnotationChecks();
    }

    /*
     * We ran into some issues when Page List+ wasn't installed first. This resolves that issue.
     */
    private function excludeFilesFromAnnotationChecks()
    {
        $th = \Core::make("helper/text");
        $driver = $this->getEntityManager()->getConfiguration()->getMetadataDriverImpl();
        $excludePaths = array();
        foreach (self::$pageListPlusFilters as $handle => $name) {
            $excludePaths[] = DIR_PACKAGES . '/' . $this->pkgHandle . '/src/PageListPlus/Filter/' . $th->CamelCase($name) . '.php';
        }
        $driver->addExcludePaths($excludePaths);
    }

    /*
     * Sometimes, the FilterInstaller class isn't loaded in Symfony. This fixes that.
     */
    private function registerFilterInstaller()
    {
        $symfonyLoader = new SymfonyMapClassloader(array(
            NAMESPACE_SEGMENT_VENDOR . '\\Package\\' . camelcase($this->pkgHandle) . '\\Src\\FilterInstaller' =>
                DIR_PACKAGES . '/' . $this->pkgHandle . '/' . DIRNAME_CLASSES . '/FilterInstaller.php'
        ));
        $symfonyLoader->register();

    }
}
