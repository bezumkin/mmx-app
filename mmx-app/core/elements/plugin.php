<?php
/** @var \MODX\Revolution\modX $modx */

if ($modx->services->has('mmxApp')) {
    $modx->services->get('mmxApp')->handleEvent($modx->event);
}