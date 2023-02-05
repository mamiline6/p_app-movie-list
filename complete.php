<?php
/**
 * 完了機能（登録・更新）
 * 登録画面からPOSTで insert メソッドを呼び出し、DB接続する。
 * 
 */
header("Content-Type:text/html;charset=utf-8");
// require_once('../functions/userfunctions.php');
require_once('Dao.php');

$app_title = "movie searcher";
$page_title = "My List に追加しました";

if(isset($_GET["id"])) {
  try {
    $dao = new Dao();
    $row = $dao->selectById($_GET["id"]);

    $id = $row["id"];
    if($id == null) {
      header("Location: index.php");
      exit;
    }

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
  $message = "不正なアクセスです";
}

if(isset($_GET["manipulation"])) {
  if($_GET["manipulation"] === "insert") {
    $message = "ID:".$_GET["id"]."「{$original_title}／<br>{$title}」をMY LISTに追加しました。";
  } elseif($_GET["manipulation"] === "update") {
    $message = "ID:".$_GET["id"]."「{$original_title}／<br>{$title}」をMY LISTを更新しました。";
  } elseif($_GET["manipulation"] === "delete") {
    $message = "ID:".$_GET["id"]."「{$original_title}／<br>{$title}」をMY LISTから削除しました。";
  } else {
    $message = "不正なアクセスです。";
  }
} else {
  $message = "不正なアクセスです。";
}
?>
<?php require_once "./_inc/_header-search-bg.php"; ?>
      <div class="container mx-auto pt-10 pb-10 px-10 text-white">
        <p class="flex justify-center text-center text-white pb-5"><?php echo $message; ?></p>

        <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
          <div class="max-w-lg mx-auto">
            <?php if(($_GET["manipulation"] === "update" || $_GET["manipulation"] === "insert") && $poster_path !== ""): ?>
              <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="<?php echo $title; ?>" class="shadow-2xl rounded">
            <?php elseif($_GET["manipulation"] === "delete"): ?>
              <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="" class="grayscale shadow-2xl rounded">
            <?php else: ?>
              <img src="https://placehold.jp/300x440.png?text=No+image" alt="" class="w-full shadow-2xl rounded">
              <?php endif; ?>
          </div>

          <?php if(isset($_GET["manipulation"])): ?>
            <?php if($_GET["manipulation"] === "update" || $_GET["manipulation"] === "insert"): ?>
              <div class="max-w-lg mx-auto text-white">
                <div class="mt-8 mb-8 backdrop-blur-sm">
                  <h1 class="text-6xl"><?php echo $original_title; ?></h1>
                  <?php if($original_title !== $title): ?>
                    <p class="text-2xl mt-5"><?php echo $title; ?></p>
                  <?php endif; ?>
                </div>
              <?php if(isset($overview) && $overview !== ""): ?>
                <h2 class="text-2xl  backdrop-blur-sm leading-snug mt-8 mb-5">あらすじ</h2>
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
            <?php endif; ?>
              <div class="btn btn-group flex flex-row justify-center space-x-6 space-x mt-10">
                <a href="list.php?title=&search=" class="text-white bg-blue-500 uppercase shadow-xl rounded px-10 py-2 font-bold flex space-between">
                  MY List へ
                </a>
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    <?php require_once "./_inc/_footer.php"; ?>