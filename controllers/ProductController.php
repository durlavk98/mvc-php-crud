<?php

namespace app\controllers;
use app\Router;
use app\models\Product;

class ProductController
{
  public static function index(Router $router)
  { 
    $keyword = $_GET['search'] ?? '';
    $products = $router->db->getProducts($keyword);
    $router->renderView('products/index', [
      'products'=> $products,
      'keyword'=> $keyword
    ]);
  }

  public static function create(Router $router)
  {
    $productData = [
      'title'=>'',
      'description'=>'',
      'price'=>'',
      'image' => ''
    ];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $productData['title'] = $_POST['title'];
      $productData['description'] = $_POST['description'];
      $productData['price'] = (float)$_POST['price'];
      $productData['imageFile'] = $_FILES['image'] ?? null;

      $product = new Product();
      $product->load($productData);
      $product->save();
      header('Location: /products');
      exit;
    }
    $router->renderView('products/create', [
      'product'=> $productData,
    ]);
  }
  public static function update(Router $router)
  {
    $id = $_GET['id'] ?? null;
    if(!$id){
      header('Location: /products');
      exit;
    }
    $productData = $router->db->getProductById($id);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $productData['title'] = $_POST['title'];
      $productData['description'] = $_POST['description'];
      $productData['price'] = $_POST['price'];
      $productData['imageFile'] = $_FILES['image'] ?? null;

      $product = new Product();
      $product->load($productData);
      $product->save();
      header('Location: /products');
      exit;
    }

    $router->renderView('products/update', [
      'product' => $productData
    ]);
  }
  public static function delete(Router $router)
  {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        header('Location: /products');
        exit;
    }

    if ($router->db->deleteProduct($id)) {
        header('Location: /products');
        exit;
    }
  }
}