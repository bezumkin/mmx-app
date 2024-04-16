<?php
/** @var \MODX\Revolution\modX $modx */

if ($modx->services->has('mmxApp')) {
    /** @var array $scriptProperties */
    return $modx->services->get('mmxApp')->handleSnippet($scriptProperties);
}