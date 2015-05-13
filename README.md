# Page List Plus Filter
A starter package to show how to implement a filter that extends Page List+.

## Setup the Package Controller
#### Add the $pageListPlusFilters Property
This property must be public and static. It indicates what filters are installed by this package.
```
public static $pageListPlusFilters = array(
    'page_list_plus_attribute' => 'PageListPlusAttribute',
);
```
In this instance, there is one filter with a handle of 'page_list_plus_attribute'. This handle matches the handle of the attribute
that comes with this package, but it could override a core attribute as well.

#### Add supporting methods:
* *setupForFilterInstallation()* This method calls the next two methods. Just makes the controller cleaner.
* *registerFilterInstaller()* This method registers classes to autoload
* *excludeFilesFromAnnotationChecks()* Some annotations are run on files when the database is updated for the package. This fixes some issues.

## Setup the Filter
In /src/PageListPlus/Filter you will find a PageListPlusAttribute.php file. This file takes values from the system and creates SQL clauses to enable the filtering.

## Setup the Form and Search Elements
You'll find the HTML used in the Page List+ block dialog box at /elements/blocks/page_list_plus/form/filters

You'll find the HTML used in the Page List+ search at /elements/blocks/page_list_plus/search/filters


