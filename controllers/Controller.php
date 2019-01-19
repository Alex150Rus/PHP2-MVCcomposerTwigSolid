<?php
/**
 * Created by PhpStorm.
 * User: Alex1
 * Date: 19.01.2019
 * Time: 0:55
 */

namespace app\controllers;


use app\interfaces\IRenderer;

abstract class Controller
{
  //храним текущий экшн
  protected $action;
  //храним дефолтный экшн
  protected $defaultAction = 'index';
  //храним статическую часть сайта (хэдер, футер)
  protected $layout = "main";
  //храним состояние использования статической части сайта
  protected $useLayout = true;

  //композиция - делегирование части задач другому классу
  protected $renderer;

  public function __construct(IRenderer $renderer)
  {
    $this->renderer = $renderer;
  }

  //метод запускает действие, которое примает в параметр из папки public index от объекта этого класса
  public function runAction($action = null)
  {
    //записываем текущий экшн, если он передан, если нет - используем дефолтный
    $this->action = $action ?: $this->defaultAction;
    //формируем наименование метода конкатенацией и увеличиваем первую букву
    $method = "action" . ucfirst($this->action);

    if (method_exists($this, $method)) {
      //если метод существет, то запускаем его
      $this->$method();
      //иначе
    } else {
      echo "404";
    }
  }

  /*метод для отрисовки принимает имя вьюхи и массив с параметрами. В методе принимается решение об отображении
  темплейта с лэйаутом или без него*/
  protected function render($template, $params = [])
  {
    //проверяем использовать статическуб часть сайта или нет
    if ($this->useLayout) {
      // если true - возвращаем шаблон, отрисованный методом renderTemplate и статическую часть сайта
      return $this->renderTemplate(
        "{$this->layout}", ['content' => $content = $this->renderTemplate($template, $params)]);
    }
    // если не использовать, то просто отрисовываем шаблон
    return $this->renderTemplate($template, $params);
  }

//метод для отрисовки шаблона - делегирован объекту класса TemplateRenderer
  protected function renderTemplate($template, $params = [])
  {
    return $this->renderer->render($template, $params);
  }
}