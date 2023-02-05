<?php
header("Content-Type:text/html;charset=utf-8");
// require_once('../functions/userfunctions.php');
require_once('Dao.php');

$app_title = "movie searcher";
$page_title = "詳細";

if(isset($_GET["id"]) && $_GET["id"] !== "") {
  try {
    $dao = new Dao();
    $row = $dao->selectById($_GET["id"]);

    $id = $row["id"];

    if($id == null) {
      header("Location: list.php");
      exit;
    }

    $original_title = $row["original_title"];
    $title = $row["title"];
    $overview = $row["overview"];
    $memo = $row["memo"];
    $poster_path = $row['poster_path'];
    $backdrop_path = $row["backdrop_path"];
    $release_date = $row["release_date"];

  } catch (PDOException $e) {
    die("データベースエラー：".$e->getMessage());
  } finally {
    $dao->close();
  }
} else {
  $message = "不正なアクセスです";
}
?>
<?php require_once "./_inc/_header-search-bg.php"; ?>
    <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
    <?php if(isset($message)): ?>
      <ul class="text-white border p-5 mb-10 rounded" style="font-size: 14px;">
        <li><?php echo $message ?></li>
      </ul>
    <? endif; ?>

    <main class="bg-fixed img-wrapper bg-no-repeat bg-center bg-cover bg-black/[.60] bg-blend-darken" style="background-image: url(https://image.tmdb.org/t/p/original<?php echo $backdrop_path; ?>);"> -->
      <div class="container mx-auto pt-10 pb-10 px-10">
        <div class="max-w-lg mx-auto">
          <?php if(empty($poster_path)): ?>
            <img src="https://placehold.jp/300x440.png?text=No+image" alt="" class="w-full shadow-2xl rounded">
          <?php else: ?>
            <img src="https://image.tmdb.org/t/p/original<?php echo $poster_path; ?>" alt="<?php echo $title; ?>" class="shadow-2xl rounded">
          <? endif; ?>
        </div>

        <div class="max-w-lg mx-auto text-white">
          <div class="mt-8 mb-8 backdrop-blur-sm">
            <h1 class="text-6xl"><?php echo $original_title; ?></h1>
          <?php if($original_title !== $title): ?>
            <p class="text-2xl mt-5"><?php echo $title; ?></p>
          <?php endif; ?>
          </div>
          <?php if(isset($overview) && $overview !== ""): ?>
          <h2 class="text-2xl  backdrop-blur-sm leading-snug mt-8 mb-5">Overview</h2>
          <p class="text-xl backdrop-blur-sm leading-relaxed mb-8"><?php echo $overview; ?></p>
          <?php endif; ?>
          <hr>
          <?php if(isset($memo) && $memo !== ""): ?>
          <h2 class="text-2xl backdrop-blur-sm leading-snug mt-8 mb-5">Memo</h2>
          <p class="text-xl backdrop-blur-sm leading-relaxed"><?php echo $memo; ?></p>
          <?php endif; ?>
          <?php if(isset($release_date) && $release_date !== ""): ?>
          <ul class="mt-8">
            <li>公開日：<?php echo $release_date; ?></li>
          </ul>
          <?php endif; ?>
          <div class="btn btn-group flex flex-row space-x-6 space-x mt-10">
            <a href="delete.php?id=<?php echo $row["id"]; ?>" class="text-white bg-red-500 shadow-xl rounded px-10 py-2 font-bold">
              My List から外す
            </a>
            <a href="update.php?id=<?php echo $row["id"]; ?>" class="text-white bg-blue-500 shadow-xl rounded px-10 py-2 font-bold">
              編集する
            </a>
          </div>
        </div>
      </div>
    </main>
    <?php endif; ?>
    <footer class="flex justify-center sticky bottom-0 transition -z-10">
      <div class="pt-10 pb-10 text-white">
        <small>&copy; 2023- mamiline.net</small>
      </div>
    </footer>
  </div>
</body>
</html>