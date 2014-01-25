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
    preg_match('/^＜材料＞([^\x{ff1c}]+)＜作り方＞([^\x{ff1c}]+)＜ＰＯＩＮＴ＞([^\x{ff1c}]+)$/u', $text, $matches);
    foreach ($matches as $i => $match) {
      $matches[$i] = explode("<br>", $match);
    }
    $items = array();
    foreach ($matches[1] as $i => $item) {
      preg_match('/^(.*?)・・+(.*)$/u', $item, $list);
      if (count($list) > 1) {
        $items[] = array(
          'name' => trim($list[1]),
          'quantity' => trim($list[2])
        );
      }
    }

    return array(
      'id' => $id,
      'title' => $title,
      'time' => self::formatTime($time),
      'image' => self::BASE_URL.$image,
      'items' => $items,
      'processes' => self::trimArray($matches[2]),
      'points' => self::trimArray($matches[3])
    );
  }

  private static function formatTime($time) {
    return preg_replace('/^(\d+)年(\d+)月(\d+)日放送/', '${1}-${2}-${3}', $time);
  }
  private static function trimArray($values) {
    $result = array();
    foreach ($values as $value) {
      $trimedValue = trim($value);
      if (!empty($trimedValue)) {
        $result[] = $trimedValue;
      }
    }
    return $result;
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
      $id = explode('.', $url)[0];
      $recipes[] = $id;
    }
    return $recipes;
  }
}
