<?php
header("Content-Type:text/html;charset=utf-8");
// require_once('../functions/userfunctions.php');
require_once('Dao.php');

$app_title = "movie searcher";
$page_title = "My List から削除しますか？";

// 検索画面から削除画面へのアクセス時の処理
if( isset($_GET["id"]) ) {
  try {
    $dao = new Dao();
    $row = $dao->selectById($_GET["id"]);
    $message = "My List から外しますか？";
    $id = $row["id"];
    if($id == null) {
      header("Location: list.php");
      exit;
    }
    $original_title = $row["original_title"];
    $title = $row["title"];
    $overview = $row["overview"];
    $memo = $row["memo"];
    $poster_path = $row["poster_path"];

  } catch(PDOException $e) {
    die("データベースエラー：".$e.getMessage());
  } finally {
    $dao->close();
  }
} elseif (isset($_POST["delete"]) ) {
  try {
    $dao = new Dao();
    $rowCount = $dao->delete($_POST["id"]);
    header("Location: complete.php?manipulation=delete&id=".$_POST["id"]);
    exit();
  } catch(PDOException $e) {
    die("データベースエラー：".$e->getMessage());
  } finally {
    $dao.close();
  }
} else {
  $message = "不正なアクセスです";
}
?>
<?php require_once "./_inc/_header-list.php"; ?>
      <div class="container mx-auto max-w-screen-lg px-10">
        <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
          <p class="py-10 font-bold text-white text-2xl"><?php echo $page_title; ?></p>

          <form id="updateform" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="flex flex-wrap justify-center">
            <div class="w-2/6 pr-10">
            <?php if(empty($poster_path)): ?>
              <img src="https://placehold.jp/300x440.png?text=No+image" class="w-full shadow-2xl rounded">
            <?php else: ?>
              <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="<?php echo $title; ?>" class="shadow-2xl rounded">
            <? endif; ?>
            </div>
            <div class="w-4/6 text-xl text-gray">
              <input type="hidden" name="id" value="<?php echo $id; ?>">

              <div class="flex flex-col mb-3 w-full">
                <label for="exampleFormControlInput1" class="form-label inline-block mb-2 text-white">タイトル／原題<?php if($original_title !== $title): ?>：<?php echo $original_title; ?><?php endif; ?></label>
                <input type="text" disabled class="form-control block w-full mb-3 px-3 py-1.5 text-base font-normal text-gray-700 bg-gray-300 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                  name="title" value="<?php echo $title; ?>">
              </div>
              <div class="flex flex-col mb-3 w-full">
                <label for="exampleFormControlTextarea1" class="form-label inline-block mb-2 text-white">あらすじ</label>
                <textarea disabled class="form-control block w-full mb-3 px-3 py-1.5 text-base font-normal text-gray-700 bg-gray-300 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleFormControlTextarea1" rows="3"
                  name="overview"><?php echo $overview; ?></textarea>
              </div>
              <div class="flex flex-col mb-3 w-full">
                <label for="exampleFormControlTextarea2" class="form-label inline-block mb-2 text-white">メモ</label>
                <textarea disabled class="form-control block w-full mb-3 px-3 py-1.5 text-base font-normal text-gray-700 bg-gray-300 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleFormControlTextarea2" rows="3"
                  name="memo"><?php echo $memo; ?></textarea>
              </div>

              <div class="btn btn-group flex flex-row space-x-6 space-x mt-10">
                <a href="detail.php?id=<?php echo $id; ?>" class="text-white bg-green-500 camelcase px-6 py-2 rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out flex items-center">
                  保存したままにする
                </a>
                <button class="text-white bg-red-500 uppercase shadow-xl rounded shadow-md px-6 py-2 hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg transition duration-150 ease-in-out flex items-center"
                  type="submit"
                  name="delete">
                  MY List から外す
                </button>
              </div>
            </form>
          </div>

        <?php endif; ?>
      </div>
    </main>
<?php require_once "./_inc/_footer.php"; ?>