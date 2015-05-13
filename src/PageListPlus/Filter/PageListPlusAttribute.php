<?php
namespace Concrete\Package\SkybluesofaPageListPlusFilter\Src\PageListPlus\Filter;

use Concrete\Package\SkybluesofaPageListPlus\Src\PageListPlus\Filter\Contract\FilterContract;

defined('C5_EXECUTE') or die("Access Denied.");

class PageListPlusAttribute extends FilterContract
{

    public function run()
    {
        $pageAttributeKeyID = $this->pageAttribute->getAttributeKeyID();
        $pageAttributeTypeHandle = $this->pageAttribute->getAttributeTypeHandle();

        if (!is_array($this->searchFilters[$pageAttributeKeyID])) {
            $this->searchFilters[$pageAttributeKeyID] = array($this->searchFilters[$pageAttributeKeyID]);
        }
        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'equals') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . " LIKE '%\\n" . $this->currentAttributeFilter['val1'] . "\\n%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_equals') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . " NOT LIKE '%\\n" . $this->currentAttributeFilter['val1'] . "\\n%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'contains' && $this->currentAttributeFilter['val1']) {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_contains' && $this->currentAttributeFilter['val1']) {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%" . $this->currentAttributeFilter['val1'] . "%' OR " . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_exactly') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "')");
            } else {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_not_exactly') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['val1'] . "')");
            } else {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all' || $this->currentAttributeFilter['filterSelection'] == 'matches_any') {
            if ($this->currentAttributeFilter['currentValue']) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
            } else {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'querystring_all' || $this->currentAttributeFilter['filterSelection'] == 'querystring_any') {
            if (array_key_exists($pageAttributeKeyID, $this->searchFilters)) {
                if (isset($this->searchFilters[$pageAttributeKeyID]) && !empty($this->searchFilters[$pageAttributeKeyID])) {
                    if (!is_array($this->searchFilters[$pageAttributeKeyID])) {
                        $this->searchFilters[$pageAttributeKeyID] = array($this->searchFilters[$pageAttributeKeyID]);
                    }
                    $startWildcard = ($this->currentAttributeFilter['isDate'] && $this->currentAttributeFilter['dateDisplayMode'] == 'date') ? '' : '%';
                    $clauseParts = array();
                    if (count($this->searchFilters[$pageAttributeKeyID]) > 0) {
                        foreach ($this->searchFilters[$pageAttributeKeyID] as $this->pageAttributeElement) {
                            $this->pageAttributeElement = trim($this->pageAttributeElement);
                            $clausePart = false;
                            if ($this->pageAttributeElement) {
                                $clausePart = "(";
                                $clausePart .= $this->currentAttributeFilter['handle'] . " LIKE '" . $startWildcard . $this->escape($this->pageAttributeElement) . "%'";
                                $clausePart .= ")";
                            }
                            if ($clausePart) {
                                $clauseParts[] = $clausePart;
                            }
                        }
                        if (count($clauseParts) > 0) {
                            $concat = $this->currentAttributeFilter['filterSelection'] == 'querystring_all' ? ' AND ' : ' OR ';
                            $this->pageListPlus->filterByClause("(" . implode($concat, $clauseParts) . ")");
                        }
                    }
                }
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all') {
            if ($this->currentAttributeFilter['currentValue']) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
            } else {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_querystring_all') {
            if (array_key_exists($pageAttributeKeyID, $this->searchFilters)) {
                if (isset($this->searchFilters[$pageAttributeKeyID]) && !empty($this->searchFilters[$pageAttributeKeyID])) {
                    $this->pageAttributeElement = $this->escape($this->searchFilters[$pageAttributeKeyID][0]);
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%" . $this->pageAttributeElement . "%')");
                }
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'starts_with') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'ends_with') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_match') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_querystring') {
            if (array_key_exists($pageAttributeKeyID, $this->searchFilters) && isset($this->searchFilters[$pageAttributeKeyID][0]) && !empty($this->searchFilters[$pageAttributeKeyID][0])) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<'" . $this->searchFilters[$pageAttributeKeyID][0] . "')");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_or_equal_to') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_or_equal_to_match') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_or_equal_to_querystring') {
            if (array_key_exists($pageAttributeKeyID, $this->searchFilters) && isset($this->searchFilters[$pageAttributeKeyID][0]) && !empty($this->searchFilters[$pageAttributeKeyID][0])) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<='" . $this->searchFilters[$pageAttributeKeyID][0] . "')");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_match') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_querystring') {
            if (array_key_exists($pageAttributeKeyID, $this->searchFilters) && isset($this->searchFilters[$pageAttributeKeyID][0]) && !empty($this->searchFilters[$pageAttributeKeyID][0])) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->searchFilters[$pageAttributeKeyID][0] . "')");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_or_equal_to') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_or_equal_to_match') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_or_equal_to_querystring') {
            if (array_key_exists($pageAttributeKeyID, $this->searchFilters) && isset($this->searchFilters[$pageAttributeKeyID][0]) && !empty($this->searchFilters[$pageAttributeKeyID][0])) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->searchFilters[$pageAttributeKeyID][0] . "')");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_inclusive') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_exclusive') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_inclusive') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_exclusive') {
            $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val2'] . "')");
        }
    }
}
