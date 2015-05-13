<?php
defined('C5_EXECUTE') or die("Access Denied.");
$defaultValue = '';
if (isset($controller->__GET[$filter->getAttributeKeyHandle()]) && !empty($controller->__GET[$filter->getAttributeKeyHandle()])) {
    if (!is_array($controller->__GET[$filter->getAttributeKeyHandle()])) {
        $defaultValue = array($controller->__GET[$filter->getAttributeKeyHandle()]);
    } else {
        $defaultValue = $controller->__GET[$filter->getAttributeKeyHandle()];
    }
} elseif (isset($controller->searchDefaults[$filter->getAttributeKeyID()])) {
    if (!is_array($controller->searchDefaults[$filter->getAttributeKeyID()])) {
        $defaultValue = array($controller->searchDefaults[$filter->getAttributeKeyID()]);
    } else {
        $defaultValue = $controller->searchDefaults[$filter->getAttributeKeyID()];
    }
}
$showRange = false;
$controllerAttributes = $controller->attributes;
if (is_array($controllerAttributes) && isset($controllerAttributes[$filter->getAttributeKeyID()]['eval'])) {
    $rangeAttributes = array('between_inclusive_querystring', 'between_exclusive_querystring', 'not_between_inclusive_querystring', 'not_between_exclusive_querystring');
    if (in_array($controllerAttributes[$filter->getAttributeKeyID()]['eval'], $rangeAttributes)) {
        $showRange = true;
    }
}
if ($showRange) { ?>
    <span class="plp_<?php echo $filter->atHandle; ?>_range">
<input name="<?php echo $filter->getAttributeKeyHandle(); ?>[]" type="text"
       class="plp_<?php echo $filter->atHandle; ?>" value="<?php echo $defaultValue[0]; ?>"> to
<input name="<?php echo $filter->getAttributeKeyHandle(); ?>[]" type="text"
       class="plp_<?php echo $filter->atHandle; ?>" value="<?php echo $defaultValue[1]; ?>"></span>
<?php } else { ?>
    <input name="<?php echo $filter->getAttributeKeyHandle(); ?>" type="text"
           class="plp_<?php echo $filter->atHandle; ?>" value="<?php echo $defaultValue[0]; ?>">
<?php }
