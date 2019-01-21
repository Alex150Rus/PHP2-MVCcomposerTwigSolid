<?php
/**
 * Created by PhpStorm.
 * User: Alex1
 * Date: 19.01.2019
 * Time: 4:45
 */

namespace app\services\renderers;

// отчечает за отображение пхп шаблонов
use app\interfaces\IRenderer;

class TemplateRenderer implements IRenderer
{
  public function render($template, $params = [])
{  //начинаем буферизацию
ob_start();
  //загоняем в переменную путь к шаблону
  if ($template == 'main') {
$templatePath = TEMPLATES_DIR . "layouts/$template" . ".php";
  //разворачиваем массив с параметрами и записываем их в переменные
  } else {
    $templatePath = TEMPLATES_DIR . "{$params['className']}/$template" . ".php";
  }
extract($params);
include $templatePath;
  // заканчиваем буферизацию и возвращаем данные в виде строки - без этого у нас произойдёт моментальная отрисовка в
  // этом месте
return ob_get_clean();}
}