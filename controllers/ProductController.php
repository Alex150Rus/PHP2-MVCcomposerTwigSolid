<?php
/**
 * Created by PhpStorm.
 * User: Alex1
 * Date: 18.01.2019
 * Time: 7:13
 */

namespace app\controllers;

use app\models\Product;

class ProductController extends Controller
{
  //дефолтный экшн - рисует каталог
  public function actionIndex() {
    $this->useLayout = false;
    //создаём необходимую сущность для отрисовки, вытаскивая нужную инфу из БД
    $product = Product::getAll();
    // отправляем на отрисовку
    echo $this->render("gallery", ['product'=>$product, 'className'=>$this->getClassName()]);
  }

  //рисует карточку товара
  public function actionCard() {
    // для этого метода не применяем статическую часть сайта
    $this->useLayout = false;
    //получаем id us url (прилетит туда гет запросом)
    $id = $_GET['id'];
    //создаём необходимую сущность для отрисовки, вытаскивая нужную инфу из БД
    $product = Product::getOne($id);
    // отправляем на отрисовку
   echo $this->render("card", ['product'=>$product, 'className'=>$this->getClassName()]);
  }

  public function getClassName() {
    return 'product';
  }
}