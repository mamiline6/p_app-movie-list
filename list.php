<?php
/**
 * My リスト検索/一覧表示
 * - ローカルのデータベース（watch_list）から映画情報を検索/表示する
 * - 検索は映画のタイトルを元にDB接続しその結果を表示する
 * - 結果は映画ポスターと映画タイトルを一覧で表示する
 */
header("Content-Type:text/html;charset=utf-8");
require_once('./functions/userfunctions.php');
require_once('./config/config.php');
require_once('Dao.class.php');

$message = "気になる映画のタイトルを入力してください。";

if (isset($_POST["search"])) {

  if(isset($_POST['title']) && $_POST['title'] !== "") {
    try {
      $dao = new dao();
      $rows = $dao->selectByName($_POST["title"]);
      // var_dump("$rows");

    } catch(PDOException $e) {
      die("データベースエラー:".mb_convert_encoding($e->getMessage(), 'UTF-8', 'ASCII,JIS,UTF-8,CP51932,SJIS-win'));

    } finally {
      $dao->close();
    }

  } else {
    // $message = $list_name."から「映画タイトル」で絞り込めます。"; // 案内メッセージ表示用
  }

// } elseif(isset($_POST['title']) && $_POST['title'] !== "") {
//   try {
//     $dao = new dao();
//     $rows = $dao->selectByName($_POST["title"]);

//     if($rows === false) {
//       $message = "入力がないです";
//     }

//   } catch(PDOException $e) {
//     die("データベースエラー:".mb_convert_encoding($e->getMessage(), 'UTF-8', 'ASCII,JIS,UTF-8,CP51932,SJIS-win'));

//   } finally {
//     $dao->close();
//   }
// } elseif ($_POST['title'] == "null") {
//   $message = "気になる映画のタイトルを入力してください。";

} else {
  $message = "不正なアクセスです。";
}

$page_title = $list_name;
?>
<?php require_once "./_inc/_header.php"; ?>
<div class="flex justify-center text-white pt-10">
        <?php //if(isset($_POST["title"])): ?>
          <?php if(isset($data["results"])): ?>
            <p class="text-white">「<?php echo $_POST["title"]; ?>」の検索結果<?php echo count($data["results"]); ?>件です。</p>
          <?php endif; ?>
        <?php //if($_POST["title"] == ""): ?>
          <p class="text-white"><?php echo $message; ?></p>
        <?php // endif; ?>
        <?php // else: ?>
          <p><?php // echo $message; ?></p>
        <?php // endif; ?>
</div>


      <div class="flex flex-wrap justify-center pl-10 pr-10">
          <?php if(isset($_POST["search"])): ?>
            <?php if(empty($rows) === false): ?>
              <?php foreach($rows as $row): ?>
                <div class="pt-5 pb-5 w-1/3 md:w-1/4 min-w-min p-4"><a href="detail.php?id=<?php echo $row["id"]; ?>">
                  <?php if(empty($row['poster_path'])): ?>
                    <img src="https://placehold.jp/300x440.png?text=No+image" class="w-full shadow-2xl rounded">
                  <?php else: ?>
                    <img src="https://image.tmdb.org/t/p/w500<?php echo $row['poster_path']; ?>" alt="<?php echo $row['original_title']; ?>" class="shadow-2xl rounded hover:scale-105 ease-out duration-100">
                  <?php endif; ?>
                  <div class="text-white">
                    <h1 class="text-lg leading-snug mt-2 mb-2"><?php echo $row["title"]; ?></h1>
                  </div>
                </a></div>
              <?php endforeach; ?>
            <?php endif; ?>

        <?php endif; ?>

      </div>
    </main>
  </div>
<?php require_once "./_inc/_search-form.php"; ?>
<?php require_once "./_inc/_footer.php"; ?>