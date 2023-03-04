<?php
header("Content-Type:text/html;charset=utf-8");
require_once('./functions/userfunctions.php');
require_once('./config/config.php');

$lang = $lang;
$search_category = $searchCategory;
$data = ""; // レスポンス結果取得用
$message = ""; // 案内メッセージ表示用

/**
 * 映画検索機能
 * - 映画情報データベース（TMDb）から映画情報を検索する
 * - 検索は映画のタイトルを元に提供 API に接続しその結果を表示する
 * - 結果は映画ポスターと映画タイトルを一覧で表示する
 */

/**
 * TMDb API へ接続と検索データの取得と表示
 * - API KEY（事前取得）を元に検索用の API を取得・利用する
 * - 入力フォームから検索クエリ（映画タイトル）を取得し、検索用の API に埋め込みリクエストをする
 * - レスポンスの結果は JSON で返ってくるのでデコードする
 * - PHPで扱う連想配列にしたデータから必要な情報を抜き出し表示する
 */

$options = array (
  'https' => array (
    'method'=> 'GET',
    'header'=> 'Content-type: application/json; charset=UTF-8'
  )
);

if (array_key_exists('title', $_POST) && $_POST['title'] != "") {
  $search_api = "https://api.themoviedb.org/3/search/{$search_category}?api_key=".APIKEY."&language={$lang}&query=".$_POST['title']."&page=1&include_adult=false";
  $context = stream_context_create($options);
  $raw_data = @file_get_contents($search_api, false, $context); // 以下、接続成功前提
  $data = json_decode($raw_data, true);

} elseif (isset($_POST['title']) && $_POST['title'] == "") {
    $msg = array (
      "例えば「アイ・アム・サム」は観ましたか？",
      "ホラー好きなら「Halloween」はどうでしょう？",
      "子供の頃、ディズニー映画は観ましたか？",
    );
    $message = array_rand($msg);

} else {
  $message = "気になる映画のタイトルを入力してください。";
}

$page_title = "検索";
?>
<?php require_once "./_inc/_header.php"; ?>

      <div class="flex justify-center text-white pt-10">
        <?php if(isset($_POST["title"])): ?>
          <?php if(isset($data["results"])): ?>
            <p>「<?php echo h($_POST["title"]); ?>」の検索結果<?php echo count($data["results"]); ?>件です。</p>
          <?php endif; ?>
          <?php if($_POST["title"] == ""): ?>
          <p><?php echo $msg[$message]; ?></p>
          <?php endif; ?>
        <?php else: ?>
          <p><?php echo $message; ?></p>
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
                <img src="https://image.tmdb.org/t/p/w500<?php echo $val['poster_path']; ?>" alt="<?php if(isset($val["title"])): ?><?php echo $val['title']; ?><?php else: ?><?php echo $val['name']; ?><?php endif; ?>" class="shadow-2xl rounded hover:scale-110 ease-out duration-100">
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
  </div><!-- /.wrapper -->

<?php require_once "./_inc/_search-form.php"; ?>
<?php require_once "./_inc/_footer.php"; ?>