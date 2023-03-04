<?php
header("Content-Type:text/html;charset=utf-8");
require_once('./functions/userfunctions.php');
require_once('./config/config.php');
require_once('Dao.class.php');

if(isset($_GET["id"]) && $_GET["id"] !== "") {
  try {
    $dao = new Dao();
    $row = $dao->selectById($_GET["id"]);

    if($row === false) { // id 検索の結果がなかったら
      err404();
    } elseif ($row["deleted_at"] == 1) { // id 検索があり、非表示指定の場合
      err404();
    } else {
      $original_title = $row["original_title"];
      $title = $row["title"];
      $overview = $row["overview"];
      $memo = $row["memo"];
      $poster_path = $row['poster_path'];
      $backdrop_path = $row["backdrop_path"];
      $release_date = $row["release_date"];
    }

  } catch (PDOException $e) {
    die("データベースエラー：".$e->getMessage());

  } finally {
    $dao->close();
  }
} else {
  $message = "不正なアクセスです";
}

$page_title = $title;
// var_dump($title);
?>
<?php require_once "./_inc/_header.php"; ?>

    <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
      <div class="container mx-auto pt-10 pb-10 px-10">
        <?php if(isset($message)): ?>
          <ul class="text-white border p-5 mb-10 rounded" style="font-size: 14px;">
            <li><?php echo $message ?></li>
          </ul>
        <?php endif; ?>

        <div class="max-w-lg mx-auto">
          <?php if(empty($poster_path)): ?>
            <img src="https://placehold.jp/300x440.png?text=No+image" alt="" class="w-full shadow-2xl rounded">
          <?php else: ?>
            <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="<?php echo $title; ?>" class="shadow-2xl rounded">
          <?php endif; ?>
        </div><!-- /.image -->

        <div class="max-w-lg mx-auto text-white">
          <div class="mt-8 mb-8 backdrop-blur-sm">
            <h1 class="text-6xl"><?php echo $original_title; ?></h1>
            <?php if($original_title !== $title): ?>
              <p class="text-2xl mt-5"><?php echo h($title); ?></p>
            <?php endif; ?>
          </div>
          <?php if(isset($overview) && $overview !== ""): ?>
            <h2 class="text-2xl  backdrop-blur-sm leading-snug mt-10 mb-5">Overview</h2>
            <p class="text-xl backdrop-blur-sm leading-relaxed mb-8"><?php echo h($overview); ?></p>
          <?php endif; ?>
          <hr>
          <?php if(isset($memo) && $memo !== ""): ?>
            <h2 class="text-2xl backdrop-blur-sm leading-snug mt-8 mb-5">Memo</h2>
            <p class="text-xl backdrop-blur-sm leading-relaxed"><?php echo h($memo); ?></p>
          <?php endif; ?>
          <?php if(isset($release_date) && $release_date !== ""): ?>
            <ul class="mt-8">
              <li>公開日：<?php echo h($release_date); ?></li>
            </ul>
          <?php endif; ?>

          <div class="btn btn-group flex justify-center space-x-4 mt-10">
            <a href="delete.php?id=<?php echo $row["id"]; ?>" class="text-gray-900 border border-white-900 focus:outline-none hover:bg-gray-10 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 _dark:bg-gray-800 dark:text-white dark:border-white-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 bg-transparent"><?php echo $list_name; ?>から外す</a>
            <a href="update.php?id=<?php echo $row["id"]; ?>" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800">編集する</a>
          </div><!-- /.btn-group -->
        </div><!-- /.content -->
      </div><!-- /.container -->
    </main>
    <?php endif; ?>
  </div><!-- /.wrapper -->
<?php require_once "./_inc/_footer.php"; ?>