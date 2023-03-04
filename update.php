<?php
header("Content-Type:text/html;charset=utf-8");
require_once('./functions/userfunctions.php');
require_once('./config/config.php');
require_once('Dao.class.php');

/**
 * DB表示用　パラメータIDでDB検索、結果をもとに必要項目を表示
 */
if(isset($_GET["id"])) {
  try {
    $dao = new Dao();
    $row = $dao->selectById($_GET["id"]);

    if($row === false) { // id 検索の結果がなかったら
      err404();
    } elseif ($row["deleted_at"] == 1) { // id 検索があり、非表示指定の場合
      err404();
    } else {
      $id = $_GET["id"];
      $original_title = $row["original_title"];
      $title = $row["title"];
      $overview = $row["overview"];
      $memo = $row["memo"];
      $poster_path = $row["poster_path"];
    }

  } catch (PDOException $e) {
    die("データベースエラー：".$e->getMessage());

  } finally {
    $dao->close();
  }
// } else {
//   $message = "不正なアクセスです";
// }

/**
 * データベース更新機能
 * 入力フォームの項目が空ではないか確認する
 * 空の場合、入力を諭す
 * それ以外は、入力値をデータベースに接続した後完了画面へリダイレクトする
 */
} elseif(isset($_POST["regist"]) || $_POST["regist"] == "update") {
  // タイトルが空の場合
  if(empty($_POST["title"])) {
    $message = 'タイトルを入力ください';

    $id = $_POST["id"];
    $original_title = $_POST["original_title"];
    $title = $_POST["title"];
    $overview = $_POST["overview"];
    $memo = $_POST["memo"];
    $poster_path = $_POST["poster_path"];

  } else {
    try {
      $dao = new Dao();
      $rowCount = $dao->update($_POST["title"], $_POST["overview"], $_POST["memo"], hex2bin(0), $_POST["id"]);
      // header("Location: complete.php?manipulation=update&id=".$_POST["id"]);
      header("Location: complete.php", true, 307);      
      exit();

    } catch(PDOException $e) {
      die("データベースエラー：".$e->getMessage());

    } finally {
      $dao.close();
    }
  }
} else {
  $message = "不正なアクセスです";
}

$page_title = "「".$title."」の映画情報を更新しますか？";

?>
<?php require_once "./_inc/_header.php"; ?>

      <div class="container mx-auto max-w-screen-lg px-10">
        <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
          <p class="py-10 font-bold text-white text-2xl"><?php echo h($page_title); ?></p>

          <?php if(isset($message)): ?>
            <div id="alert-1" class="flex p-4 mb-10 text-blue-800 border border-gray-300 rounded-lg  bg-gray-50 dark:bg-gray-800 dark:text-gray-100" role="alert">
              <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
              <span class="sr-only">Info</span>
              <div class="ml-3 text-sm font-medium"><?php echo $message; ?>
              </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-gray-50 text-gray-200 rounded-lg focus:ring-2 focus:ring-gray-400 p-1.5 hover:bg-gray-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700" data-dismiss-target="#alert-1" aria-label="Close">
                  <span class="sr-only">Close</span>
                  <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
              </button>
            </div>
          <?php endif; ?>

          <form id="updateform" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="flex flex-wrap justify-center">
              <div class="w-3/6 pr-10">
                <?php if(empty($poster_path)): ?>
                  <img src="https://placehold.jp/300x440.png?text=No+image" class="w-full shadow-2xl rounded">
                <?php else: ?>
                  <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="<?php echo $title; ?>" class="shadow-2xl rounded">
                <?php endif; ?>
              </div><!-- image -->

              <div class="w-3/6 text-white text-xl">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <!-- <input type="hidden" name="manipulation" value="update"> -->
                <input type="hidden" name="original_title" value="<?php echo h($original_title); ?>">
                <input type="hidden" name="poster_path" value="<?php echo $poster_path; ?>">

                <div class="flex flex-col mb-3 w-full">
                  <div class="relative mb-10">
                    <input type="text" id="title" class="block rounded-lg px-2.5 pb-2.5 pt-5 p-2.5 w-full text-xl text-gray-900 bg-gray-100 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" name="title" value="<?php echo h($title); ?>" />
                    <label for="title" class="absolute text-base text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">タイトル(必須)</label>
                  </div>
                  <div class="relative mb-10 z-0">
                    <label for="overview" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">あらすじ</label>
                    <textarea id="overview" rows="4" class="block p-2.5 w-full text-base text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" name="overview"><?php echo h($overview); ?></textarea>
                  </div>
                  <div class="relative mb-10 z-0">
                    <label for="memo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">メモ</label>
                    <textarea id="memo" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="・観た日
・印象に残った言葉" name="memo"><?php echo h($memo); ?></textarea>
                  </div>
                </div><!-- contents -->

                <div class="btn btn-group flex justify-center space-x-4">
                  <a href="detail.php?id=<?php echo $id; ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">詳細表示へ戻る</a>
                  <button
                  type="submit" name="regist" value="update" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800"
                  >更新する</button>
                </div><!-- /.btn-group -->
              </div><!-- /.content -->
            </div><!-- layout -->
          </form>
        <?php endif; ?>
      </div><!-- /.container -->
    </main>
  <?php require_once "./_inc/_footer.php"; ?>