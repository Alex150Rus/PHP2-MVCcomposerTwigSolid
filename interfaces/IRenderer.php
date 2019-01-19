<?php
/**
 * Created by PhpStorm.
 * User: Alex1
 * Date: 19.01.2019
 * Time: 5:20
 */

namespace app\interfaces;


interface IRenderer
{
function render ($template, $params=[]);
}