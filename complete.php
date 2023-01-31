<?php
header("Content-Type:text/html;charset=utf-8");
// require_once('../functions/userfunctions.php');
require_once('Dao.php');

if(isset($_GET["id"])) {
    try {
        $dao = new Dao();
        $row = $dao->selectById($_GET["id"]);

        $id = $row["id"];
        $title = $row["title"];
        $overview = $row["overview"];
        $memo = $row["memo"];

    } catch (PDOException $e) {
        die("データベースエラー：".$e->getMessage());
    } finally {
        $dao->close();
    }
} elseif (isset($_POST["update"])) {
    if(empty($_POST["title"]) || empty($_POST["overview"]) || empty($_POST["memo"])) {

        $id = $_POST["id"];
        $title = $_POST["title"];
        $overview = $_POST["overview"];
        $memo = $_POST["memo"];

    } else {
        try {
            $dao = new Dao();
            $rowCount = $dao->update($_POST["id"], $_POST["title"], $_POST["overview"], $_POST["memo"]);
            header("Location: complete.php?manipulation=update&id=".$_POST["id"]);
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
?>
<?php
if(isset($_GET["manipulation"])) {
    if($_GET["manipulation"] === "insert") {
        $message = "ID:".$_GET["id"]."でデータ登録しました。";

      } elseif($_GET["manipulation"] === "update") {
        $message = "ID:".$_GET["id"]."をデータ更新しました。";
        $btn = '<div class="btn btn-group flex flex-row justify-center space-x-6 space-x mb-10">
        <a href="/>" class="text-white bg-blue-500 camelcase shadow-xl rounded px-10 py-2 font-bold flex space-between">
          to TOP
        </a></div>';

    } elseif($_GET["manipulation"] === "delete") {
        $message = "ID:".$_GET["id"]."をデータ削除しました。";
        $btn = '<div class="btn btn-group flex flex-row justify-center space-x-6 space-x mb-10">
        <a href="/>" class="text-white bg-blue-500 camelcase shadow-xl rounded px-10 py-2 font-bold flex space-between">
          to TOP
        </a></div>';

    } else {
        $message="不正なアクセスです。";
    }
} else {
    $message="不正なアクセスです。";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DETAIL：APP PHP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800">
  <div class="">
  <header class="header sticky top-0 flex justify-center pt-2 pb-2 text-white shadow-xl bg-gray-600 z-10">
    <!-- <header class="header sticky top-0 flex justify-center shadow-xl bg-white"> -->
      <div class=""><a href="/">
        <p class="flex end text-xl font-sans">
          <span>Movie <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 pr-2 inline-block font-">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0118 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0118 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 016 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5" />
          </svg>WATCH LIST</span>
        </p>
      </a></div>
    </header>

    <!-- <main class="flex bg-fixed bg-no-repeat bg-center bg-cover bg-black/[.60] bg-blend-darken" style="background-image: url(https://api.lorem.space/image/movie?w=900&h=1320); height: 100vh;"> -->
      <div class="bg-fixed img-wrapper bg-no-repeat bg-center bg-cover bg-black/[.60] bg-blend-darken" style="background-image: url(https://api.lorem.space/image/movie?w=900&h=1320);">
        <div class="container mx-auto pt-10 pb-10 px-10">
      <!-- <div class="container mx-auto max-w-screen-lg flex justify-center items-center px-10"> -->

      <p class="flex justify-center text-white pb-5"><?php echo $message; ?></p>
      <?php echo $btn; ?>

      <?php if(isset($_GET["id"]) || isset($_POST["id"])): ?>
          <div class="max-w-lg mx-auto">
          <?php if(isset($_GET["manipulation"])): ?>

            <?php if($_GET["manipulation"] === "delete"): ?>
                <img src="https://api.lorem.space/image/movie?w=900&h=1320" alt="<?php echo $title; ?>" class="shadow-2xl rounded grayscale w-1/2 mx-auto">

            <?php else: ?>
                <img src="https://api.lorem.space/image/movie?w=900&h=1320" alt="<?php echo $title; ?>" class="shadow-2xl rounded ">

            <?php endif; ?>
          </div>

        <?php if(isset($_GET["manipulation"])): ?>
          <?php if($_GET["manipulation"] === "update"): ?>
          <div class="max-w-lg mx-auto text-white">
            <h1 class="text-5xl backdrop-blur-sm leading-snug mt-8 mb-8"><?php echo $title; ?></h1>

            <h2 class="text-2xl  backdrop-blur-sm leading-snug mt-8 mb-5">Overview</h2>
            <p class="text-xl backdrop-blur-sm leading-relaxed mb-8"><?php echo $overview; ?></p>
            <hr>
            <h2 class="text-2xl  backdrop-blur-sm leading-snug mt-8 mb-5">Memo</h2>
            <p class="text-xl backdrop-blur-sm leading-relaxed"><?php echo $memo; ?></p>

            <ul class="mt-8">
              <li>公開日：${20**/**/**}</li>
              <!-- <li>観覧日：20**/**/**</li> -->
            </ul>

            <div class="btn btn-group flex flex-row justify-center space-x-6 space-x mt-10">
              <a href="update.php?id=<?php echo $row["id"]; ?>" class="text-white bg-blue-500 uppercase shadow-xl rounded px-10 py-2 font-bold flex space-between">
                Edit
              </a>
            </div>
        <?php endif; ?>
        <?php endif; ?>
          </div>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </main>
    <footer class="flex justify-center sticky bottom-0 transition -z-10">
      <div class="pt-10 pb-10">
        footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer
      </div>
    </footer>
  </div>
</body>
</html>