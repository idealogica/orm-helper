<?php
namespace Idealogica\OrmHelper;

/**
 * @param mixed $val
 *
 * @return string
 */
function mixedToString($val)
{
    if (is_object($val)) {
        $val = 'Instance of ' . get_class($val);
    }
    return '"' . @(string)$val . '"';
}
