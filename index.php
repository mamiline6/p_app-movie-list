<?php
/**
 * 映画検索機能
 * - 映画情報データベース（TMDb）から映画情報を検索する
 * - 検索は映画のタイトルを元に提供 API に接続しその結果を表示する
 * - 結果は映画ポスターと映画タイトルを一覧で表示します
 */

header("Content-Type:text/html;charset=utf-8");
// require_once("../functions/userfunctions.php");

$app_title = "movie searcher";
$page_title = "検索";

/**
 * TMDb API へ接続と検索データの取得と表示
 * - API KEY（事前取得）を元に検索用の API を取得・利用する
 * - 入力フォームから検索クエリ（映画タイトル）を取得し、検索用の API に埋め込みリクエストをする
 * - レスポンスの結果は JSON で返ってくるのでデコードする
 * - PHPで扱う連想配列にしたデータから必要な情報を抜き出し表示する
 */

$lang = "en"; // ja-JP
$search_category = "movie"; // tv
$data = ""; // レスポンス結果取得用
$message = ""; // 案内メッセージ表示用

$options = array(
  'http' => array (
    'method'=> 'GET',
    'header'=> 'Content-type: application/json; charset=UTF-8'
  )
);

if (array_key_exists('title', $_GET) && $_GET['title'] != "") {
  $search_api = "https://api.themoviedb.org/3/search/{$search_category}?api_key=".APIKEY."&language={$lang}&query=".$_GET['title']."&page=1&include_adult=false";
  $context = stream_context_create($options);
  $raw_data = file_get_contents($search_api, false, $context);
  $data = json_decode($raw_data, true);
} else {
  $message = "気になる映画のタイトルを入力してください。";
}

?>
<?php require_once "./_inc/_header-search.php"; ?>
      <div class="flex justify-center text-white pt-10">
        <?php if(isset($_GET["title"])): ?>
          <?php if(isset($data["results"])): ?>
            <p class="">「<?php echo $_GET["title"]; ?>」の検索結果<?php echo count($data["results"]); ?>件です。</p>
          <?php endif; ?>
        <?php else: ?>
          <p class=""><?php echo $message; ?></p>
        <?php endif; ?>
      </div>

      <div class="flex flex-wrap justify-center pl-10 pr-10">
      <?php foreach((array)$data as $key => $value): ?>
        <?php if(is_array($value)): ?>
          <?php foreach($value as $val): ?>

            <div class="pt-10 pb-5 w-1/3 md:w-1/4 min-w-min p-4">
              <a href='insert.php?id=<?php echo $val["id"]; ?>'>
              <?php if(empty($val['poster_path'])):?>
                <img src="https://placehold.jp/300x440.png?text=No+image" class="w-full shadow-2xl rounded">
              <?php else: ?>
                <img src="https://image.tmdb.org/t/p/w500<?php echo $val['poster_path']; ?>" alt="<?php if(isset($val["title"])): ?><?php echo $val['title']; ?><?php else: ?><?php echo $val['name']; ?><?php endif; ?>" class="shadow-2xl rounded hover:scale-105 ease-out duration-100">
              <?php endif; ?>
                <div class="text-white">
                  <h1 class="text-lg leading-snug mt-2 mb-2">
                <?php if(isset($val["title"]) || isset($val["original_title"])): ?>
                  <?php echo $val['original_title']; ?>／<?php echo $val['title']; ?>
                <?php else: ?>
                  <?php echo $val['name']; ?>
                <?php endif; ?>
                  </h1>
                </div>
              </a>
            </div>

          <?php endforeach; ?>
        <?php endif; ?>
      <?php endforeach; ?>
      </div>

    </main>
    <?php require_once "./_inc/_search-api.php"; ?>
    <?php require_once "./_inc/_footer.php"; ?>