<?php
require_once('../recipe_api.php');

if (empty($_GET['id'])) {
  if (empty($_GET['count'])) {
    $_GET['count'] = 20;
  }
  $recipes = array();
  $recipeList = RecipeAPI::allList();
  $i = 0;
  foreach ($recipeList as $recipe) {
    if (++$i > $_GET['count']) {
      break;
    }
    $recipes[] = RecipeAPI::getInfo($recipe);
  }
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
