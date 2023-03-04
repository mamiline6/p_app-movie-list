<?php
/**
 * htmlspecialchars()の簡易化関数
 * @param string $string
 *     XSS対策処理を行う文字列
 * @return string
 *     XSS対策処理後の文字列
 */
function h($string){
  return htmlspecialchars($string, ENT_QUOTES);
}

/**
 * DB登録がない（もしくは deleted_at がtrue）ページへの対応
 * ステータスコードと合わせてページを表示（ソフト404を回避）
 * @return void
 */
function err404() {
  header("HTTP/1.1 404 Not Found");
  require_once('./404.php');
  exit;
}
