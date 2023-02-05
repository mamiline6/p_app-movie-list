<?php
header("Content-Type:text/html;charset=utf-8");
// require_once("../functions/userfunctions.php");
require_once("Dao.php");

$app_title = "movie searcher";
$page_title = "My List";

if(isset($_GET["title"])) {
  try {
    $dao = new dao();
    $rows = $dao->selectByName($_GET["title"]);

  } catch(PDOException $e) {
    die("データベースエラー:".mb_convert_encoding($e->getMessage(), 'UTF-8', 'ASCII,JIS,UTF-8,CP51932,SJIS-win'));
  } finally {
    $dao->close();
  }
} else {
  $message = "不正なアクセスです。";
}
?>
<?php require_once "./_inc/_header-list.php"; ?>
      <div class="flex flex-wrap justify-center pl-10 pr-10">
        <?php if(isset($_POST["search"]) || isset($_GET["search"])): ?>

          <!-- <div class="flex justify-center text-white pt-10">
            <?php if(isset($_GET["title"])): ?>
              <?php if(isset($data["results"])): ?>
                <p class="">「<?php echo $_GET["title"]; ?>」の検索結果<?php echo count($data["results"]); ?>件です。</p>
              <?php endif; ?>
            <?php else: ?>
              <p class=""><?php echo $message; ?></p>
            <?php endif; ?>
          </div> -->

          <!-- <?php if(!empty($message)): ?><p class="flex-none w-full text-center text-white mt-5"><?php echo $message; ?></p><?php endif; ?>
          <?php if(!empty($rows)): ?><p class="flex-none w-full text-center text-white mt-5 mb-5"><?php echo '該当データは<b class="text-lg">'.count($rows).'</b>件です</p>'; ?><?php endif; ?> -->

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
<?php require_once "./_inc/_footer-list.php"; ?>