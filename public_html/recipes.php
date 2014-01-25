<?php
require_once('../recipe_api.php');

if (empty($_GET['id'])) {
  $recipes = RecipeAPI::allList();
} else {
  $recipes = array();
  $idList = explode(',', $_GET['id']);
  foreach ($idList as $id) {
    $recipes[] = RecipeAPI::getInfo($id);
  }
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
echo json_encode($recipes);
