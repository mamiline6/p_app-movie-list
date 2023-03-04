<?php
header("Content-Type:text/html;charset=utf-8");
require_once('./functions/userfunctions.php');
require_once('./config/config.php');
require_once('Dao.class.php');

/**
 * DB表示用　パラメータIDでDB検索、結果をもとに必要項目を表示
 */
if(isset($_POST["id"])) {
  try {
    $dao = new Dao();
    $row = $dao->selectById($_POST["id"]);

    $original_title = $row["original_title"];
    $title = $row["title"];
    $poster_path = $row["poster_path"];
    $backdrop_path = $row["backdrop_path"];
    $overview = $row["overview"];
    $release_date = $row["release_date"];
    $memo = $row["memo"];

  } catch (PDOException $e) {
    die("データベースエラー：".$e->getMessage());

  } finally {
    $dao->close();
  }

} elseif(isset($_POST["tmdb_id"])) {
  try {
    $dao = new Dao();
    $id = $dao->searchById($_POST["tmdb_id"]);
    $row = $dao->selectById($id);

    $_POST["id"] = $id;
    $tmdb_id = $row["tmdb_id"];
    $original_title = $row["original_title"];
    $title = $row["title"];
    $poster_path = $row["poster_path"];
    $backdrop_path = $row["backdrop_path"];
    $overview = $row["overview"];
    $release_date = $row["release_date"];
    $memo = $row["memo"];

    } catch (PDOException $e) {
      die("データベースエラー：".$e->getMessage());

    } finally {
      $dao->close();
    }
} else {
  $message = "不正なアクセスですd";
}

/**
 * 完了機能（登録・更新）
 * 登録画面からPOSTで insert メソッドを呼び出し、DB接続する。
 */
 if(isset($_POST["regist"])) {
  if($_POST["regist"] === "insert") {
    $message = "「{$title}／原題：{$original_title}」を ".$list_name."に追加しました。";
  } elseif($_POST["regist"] === "update") {
    $message = "「{$title}／原題：{$original_title}」の情報を更新しました。";
  } elseif($_POST["regist"] === "restore") {
    $message = "「{$title}／原題：{$original_title}」を ".$list_name."に再追加しました。";
  } elseif($_POST["regist"] === "deleted") {
    $message = "「{$title}／原題：{$original_title}」を ".$list_name."から削除しました。";
  } else {
    $message = "不正なアクセスです。";
  }
} else {
  $message = "不正なアクセスです。";
}

$page_title = "完了";

?>
<?php require_once "./_inc/_header.php"; ?>

    <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
      <div class="mx-auto pt-10 pb-10 px-10 text-white">
        <?php if(isset($message)): ?>
          <div id="alert-1" class="flex p-4 mb-10 text-blue-800 border border-gray-300 rounded-lg  bg-gray-50 dark:bg-gray-800 dark:text-gray-100" role="alert">
            <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <span class="sr-only">Info</span>
            <div class="ml-3 text-sm font-medium"><?php echo $message; ?></div>
              <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-gray-50 text-gray-200 rounded-lg focus:ring-2 focus:ring-gray-400 p-1.5 hover:bg-gray-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700" data-dismiss-target="#alert-1" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
          </div>
        <?php endif; ?>

        <?php if(isset($_POST["regist"])): ?>
          <div class="max-w-lg mx-auto">
            <?php if(($_POST["regist"] === "update" || $_POST["regist"] === "insert" || $_POST["regist"] === "restore") && $poster_path !== ""): ?>
              <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="<?php echo $title; ?>" class="shadow-2xl rounded">
            <?php elseif($_POST["regist"] === "deleted"): ?>
              <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="" class="grayscale shadow-2xl rounded">
            <?php else: ?>
              <img src="https://placehold.jp/300x440.png?text=No+image" alt="" class="w-full shadow-2xl rounded">
            <?php endif; ?>
          </div><!-- /.image -->

        <?php if(isset($_POST["regist"])): ?>
            <?php if($_POST["regist"] === "update" || $_POST["regist"] === "insert" || $_POST["regist"] === "restore"): ?>
            <div class="max-w-lg mx-auto text-white">
              <div class="mt-8 mb-8 backdrop-blur-sm">
                <h1 class="text-6xl"><?php echo $original_title; ?></h1>
                <?php if($original_title !== $title): ?>
                  <p class="text-2xl mt-5"><?php echo $title; ?></p>
                <?php endif; ?>
              </div>
              <?php if(isset($overview) && $overview !== ""): ?>
                <h2 class="text-2xl  backdrop-blur-sm leading-snug mt-10S mb-5">Overview</h2>
                <p class="text-xl backdrop-blur-sm leading-relaxed mb-8"><?php echo $overview; ?></p>
              <?php endif; ?>
              <hr>
              <?php if(isset($memo) && $memo !== ""): ?>
                <h2 class="text-2xl  backdrop-blur-sm leading-snug mt-8 mb-5">メモ</h2>
                <p class="text-xl backdrop-blur-sm leading-relaxed"><?php echo $memo; ?></p>
              <?php endif; ?>
              <?php if(isset($release_date) && $release_date !== ""): ?>
                <ul class="mt-8">
                  <li>公開日：<?php echo $release_date; ?></li>
                </ul>
              <?php endif; ?>
            </div><!-- content -->
            <?php endif; ?>

            <div class="btn btn-group sticky bottom-0 pb-20 flex flex-row justify-center space-x-6 space-x mt-10">
              <a href="list.php" class="text-white bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg px-4 py-2 dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">
                <?php echo $list_name; ?>一覧へ
              </a>
            </div><!-- /.btn-group -->
          </div><!-- /.container -->
          <?php endif; ?>
        <?php endif; ?>
      <?php endif; ?>
    </div><!-- wrapper -->
  <?php require_once "./_inc/_footer.php"; ?>
