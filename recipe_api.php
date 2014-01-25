<?php
require_once(__DIR__.'/simple_html_dom/simple_html_dom.php');

class RecipeAPI {
  const BASE_URL = 'http://www.ntv.co.jp/zip/mokomichi/';
  const CACHE_DIR = '/cache/';

  private static function getHtml($file) {
    $cachePath = __DIR__.self::CACHE_DIR.$file;
    if (!file_exists($cachePath)) {
      $content = file_get_contents(self::BASE_URL.$file);
      file_put_contents($cachePath, $content);
    }
    return file_get_html($cachePath);
  }

  public static function getInfo($id) {
    $html = self::getHtml($id.'.html');
    if (empty($html)) {
      return array();
    }
    $postElem = $html->find('.post', 0);
    if (empty($postElem)) {
      return array();
    }
    $postHeaderElem = $postElem->find('.postHeader', 0);
    $bodyElem = $postElem->find('.text', 0)->find('.block', 0);
    $time = $postHeaderElem->find('time', 0)->innertext;
    $title = $postHeaderElem->find('h2', 0)->innertext;
    $image = $bodyElem->find('img', 0)->src;
    $text = $bodyElem->find('p', 0)->innertext;

    return array(
      'id' => $id,
      'title' => $title,
      'time' => $time,
      'image' => self::BASE_URL.$image,
      'text' => $text
    );
  }

  public static function allList() {
    $allRecipes = array();
    $html = self::getHtml('select.html');
    $months = $html->find('.archive', 0)->find('ul', 0)->find('li');
    foreach ($months as $month) {
      $recipes = self::monthly($month->find('a', 0)->href);
      foreach ($recipes as $recipe) {
        $allRecipes[] = $recipe;
      }
    }
    return $allRecipes;
  }

  private static function monthly($file) {
    $recipes = array();
    $html = self::getHtml($file);
    foreach ($html->find('.archive', 0)->find('ul', 0)->find('li') as $post) {
      $url = $post->find('a', 0)->href;
      $date = $post->find('time', 0)->innertext;
      $id = explode('.', $url)[0];
      $recipes[] = array(
        'id' => $id,
        'date' => $date
      );
    }
    return $recipes;
  }
}
