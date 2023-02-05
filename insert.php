<?php
/**
 * 映画情報詳細引用機能
 * - 検索機能から選択した映画の詳細を表示する
 * - 詳細情報は ID をもとに提供 API に接続しその結果を表示する
 * - 結果は入力フォームに反映する
 * - ローカル環境下に作成したデータベースと項目・型・順を揃える
 */
header("Content-Type:text/html;charset=utf-8");
require_once('Dao.php');

$app_title = "movie searcher";
$page_title = "My List に追加しますか？";

$lang = "ja-JP"; // en
$api_data = ""; // レスポンス結果取得用
$caution = ""; // 案内メッセージ表示用

$options = array(
  'http' => array (
    'method'=> 'GET',
    'header'=> 'Content-type: application/json; charset=UTF-8'
  )
);

if (isset($_GET["id"])) {
  $tmdb_id = $_GET["id"];
  $film_api = "https://api.themoviedb.org/3/movie/".$tmdb_id."?api_key=".APIKEY."&language={$lang}";
  $context  = stream_context_create($options);
  $raw_data = file_get_contents($film_api, false, $context);
  $api_data = json_decode($raw_data, true);

/**
 * データベース登録機能
 * 入力フォームの項目が空ではないか確認する
 * 空の場合、すべて入力を諭す
 * それ以外は、入力値とAPI値をデータベースに接続した後完了画面へリダイレクトする
 */
} elseif(isset($_POST["regist"])) {
  // タイトルが空の場合
  if(empty($_POST["title"])) {
    $message = 'タイトルを入力してください';

    $tmdb_id = $_POST["tmdb_id"];
    $original_title = $_POST["original_title"];
    $title = $_POST["title"];
    $overview = $_POST["overview"];
    $memo = $_POST["memo"];
    $poster_path = $_POST["poster_path"];
    $backdrop_path = $_POST["backdrop_path"];
    $release_date = $_POST["release_date"];
    $deleted_at = $_POST["deleted_at"];

  } else {
    try {
      $dao = new Dao();
      $insertId = $dao->insert($_POST["tmdb_id"], $_POST["original_title"], $_POST["title"], $_POST["overview"], $_POST["memo"], $_POST["poster_path"], $_POST["backdrop_path"], $_POST["release_date"], $_POST["deleted_at"]);
      header("Location: complete.php?manipulation=insert&id=".$insertId);
      exit();
    } catch(PDOException $e) {
      die("データベースエラー：". $e->getMessage());
    } finally {
      $dao->close();
    }
  }
} else {
  $message = "不正なアクセスです";
}
?>
<?php require_once "./_inc/_header-search.php"; ?>
      <div class="container mx-auto max-w-screen-lg px-10">
        <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
          <p class="py-10 font-bold text-white text-2xl"><?php echo $page_title; ?></p>

          <?php if(isset($message)): ?>
            <ul class="w-full text-white border p-5 mb-10" style="font-size: 14px;">
              <li><?php echo $message ?></li>
            </ul>
          <? endif; ?>

          <form id="insertform" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="flex flex-wrap justify-center">
              <div class="w-2/6 pr-10">
              <?php if(empty($api_data["poster_path"])): ?>
                <img src="https://placehold.jp/300x440.png?text=No+image" class="w-full shadow-2xl rounded">
              <?php else: ?>
                <img src="https://image.tmdb.org/t/p/original<?php echo $api_data["poster_path"]; ?>" alt="<?php echo $api_data["title"]; ?>" class="shadow-2xl rounded">
              <? endif; ?>
              </div>
              <div class="w-4/6 text-white text-xl">
                <input type="hidden" name="tmdb_id" value="<?php echo $_GET["id"]; ?>">
                <input type="hidden" name="original_title" value="<?php echo $api_data["original_title"]; ?>">
                <input type="hidden" name="poster_path" value="<?php echo $api_data["poster_path"]; ?>">
                <input type="hidden" name="backdrop_path" value="<?php echo $api_data["backdrop_path"]; ?>">
                <input type="hidden" name="release_date" value="<?php echo $api_data["release_date"]; ?>">
                <input type="hidden" name="deleted_at">

                <div class="flex flex-col mb-3 w-full">
                  <label for="exampleFormControlInput1" class="form-label inline-block mb-2 text-white-700"><span class=" text-orange-400 text-xs rounded border p-1 mr-2">必須</span>タイトル／原題<?php if($api_data["original_title"] !== $api_data["title"]): ?>：<?php echo $api_data["original_title"]; ?><?php endif; ?></label>
                  <input type="text" class="form-control block w-full mb-3 px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleFormControlInput1" placeholder="<?php echo $api_data["title"]; ?>"
                    name="title" value="<?php echo $api_data["title"]; ?>"
                  />
                </div>
                <div class="flex flex-col mb-3 w-full">
                  <label for="exampleFormControlTextarea1" class="form-label inline-block mb-2 text-white-700">あらすじ</label>
                  <textarea class="form-control block w-full mb-3 px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleFormControlTextarea1" rows="3"
                    name="overview"><?php echo $api_data["overview"]; ?></textarea>
                </div>
                <div class="flex flex-col mb-3 w-full">
                  <label for="exampleFormControlTextarea2" class="form-label inline-block mb-2 text-white-700">メモ</label>
                  <textarea class="form-control block w-full mb-3 px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleFormControlTextarea2" rows="3"
                    name="memo"></textarea>
                </div>

                <div class="btn btn-group flex flex-row space-x-6 space-x mt-10">
                  <a href="index.php?title=<?php if(isset($_GET["title"])) { echo $_GET["title"]; } ?>" class="text-white bg-green-500 camelcase px-6 py-2 rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out flex items-center">
                    戻る
                  </a>
                  <button class="text-white bg-blue-500 uppercase shadow-xl rounded shadow-md px-6 py-2 hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out flex items-center"
                    type="submit"
                    name="regist">
                    My List に追加する
                  </button>
                </div>
              </div>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </main>
    <?php require_once "./_inc/_footer.php"; ?>