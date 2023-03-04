<?php
header("Content-Type:text/html;charset=utf-8");
require_once('./functions/userfunctions.php');
require_once('./config/config.php');

$app_title = "Moviearch";
$list_name = "My リスト";
$search_name = "映画検索";
$page_title = "ページが存在しません";
?>
<?php require_once "./_inc/_header.php"; ?>
    <div class="container mx-auto pt-10 pb-10 px-10">
      <div class="max-w-lg mx-auto text-white text-center">
        <p class="text-white text-5xl mb-5">404 Not Found.</p>
        <p>お探しのページは<?php echo $list_name; ?>に存在しないか、削除されています。<br>
      お手数ですが、以下「映画タイトル検索」をお勧めします。</p>
        <?php include_once "./_inc/_search-form.php"; ?>

        <div class="btn btn-group flex justify-center space-x-4">
        </div><!-- /.btn-group -->
        </div>
      </div><!-- /.content -->
    </div><!-- /.container -->
  </main>
<?php require_once "./_inc/_footer.php"; ?>