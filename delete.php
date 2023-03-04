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
    die("データベースエラー：".$e.getMessage());

  } finally {
    $dao->close();
  }
// } else {
//   $message = "不正なアクセスです";
// }

/**
 * DB削除用 POSTメソッドで各name属性を引っ張って、header関数のリダイレクト先に渡す
 */
} elseif (isset($_POST["regist"]) || $_POST["regist"] == "deleted") {
  try {
    $dao = new Dao();
    $rowCount = $dao->delete($_POST["id"]);
    //header("Location: complete.php?manipulation=deleted&id=".$_POST["id"]);
    header("Location: complete.php", true, 307);
    exit();

  } catch(PDOException $e) {
    die("データベースエラー：".$e->getMessage());

  } finally {
    $dao.close();
  }
} else {
  $message = "不正なアクセスです";
}

$page_title = $list_name."から削除しますか？";

?>
<?php require_once "./_inc/_header.php"; ?>

      <div class="container mx-auto max-w-screen-lg px-10 z-20">
        <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
          <p class="py-10 font-bold text-white text-2xl"><?php echo $page_title; ?></p>

          <form id="updateform" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
          <!-- <form id="updateform" action="complete.php" method="POST"> -->
            <div class="flex flex-wrap justify-center">
              <div class="w-3/6 pr-10">
                <?php if(empty($poster_path)): ?>
                  <img src="https://placehold.jp/300x440.png?text=No+image" class="w-full shadow-2xl rounded">
                <?php else: ?>
                  <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="<?php echo $title; ?>" class="shadow-2xl rounded">
                <?php endif; ?>
              </div><!-- /. image -->

              <div class="w-3/6 text-xl text-gray">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <!-- <input type="hidden" name="manipulation" value="delete"> -->

                <div class="flex flex-col mb-3 w-full">
                  <div class="relative mb-10">
                    <input type="text" id="title" aria-label="disabled input 2" class="bg-gray-100 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $title; ?>" disabled readonly>
                    <label for="title" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">タイトル</label>
                  </div>
                  <div class="relative mb-10">
                    <label for="overview" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">あらすじ</label>
                    <!-- <label for="exampleFormControlTextarea1" class="form-label inline-block mb-2 text-white">あらすじ</label> -->
                    <textarea id="overview" rows="4" disabled class="cursor-not-allowed block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" name="overview"><?php echo $overview; ?></textarea>
                  </div>
                  <div class="relative mb-10 z-0">
                    <label for="memo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">メモ</label>
                    <textarea id="memo" rows="4" disabled class="cursor-not-allowed block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="・観た日
・印象に残った言葉" name="memo"><?php echo $memo; ?></textarea>
                  </div>
                </div><!-- contents -->

                <div class="btn btn-group flex justify-center space-x-4">
                  <a href="detail.php?id=<?php echo $id; ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">詳細表示へ戻る</a>
                  <button 
                    type="submit" name="regist" value="deleted" class="text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-black dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">
                    削除する</button>
                </div><!-- /.btn-group -->
              </div><!-- /.contents -->
            </div><!-- layout -->
          </form>
        <?php endif; ?>
      </div><!-- /.container -->
    </main>
  </div><!-- /.wrapper -->
<?php require_once "./_inc/_footer.php"; ?>