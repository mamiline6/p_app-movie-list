<?php
header("Content-Type:text/html;charset=utf-8");
// require_once("../functions/userfunctions.php");
require_once("dao.php");

if(isset($_POST["search"])){ // 検索ボタン押下時の処理
  try {
    $dao = new dao();
    $rows = $dao->selectByName($_POST["title"]);
  } catch(PDOException $e) {
    //die("データベースエラー:".$e->getMessage());
    die("データベースエラー:".mb_convert_encoding($e->getMessage(), 'UTF-8', 'ASCII,JIS,UTF-8,CP51932,SJIS-win'));
  } finally {
    $dao->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HOME：APP PHP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800">
  <div class="">
    <header class="header sticky top-0 flex justify-center pt-2 pb-2 text-white shadow-xl bg-gray-600">
      <div class=""><a href="/">
        <h1 class="flex text-xl font-sans">
          <span>Movie <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 pr-2 inline-block">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0118 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0118 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 016 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5" />
          </svg>WATCH LIST</span>
        </h1>
      </a></div>
    </header>

    <main class="bg-fixed bg-no-repeat bg-center bg-cover bg-black/[.60] bg-blend-darken" style="background-image: url(https://api.lorem.space/image/movie?w=900&h=1320);">

        <div class="flex flex-wrap justify-center pl-10 pr-10">

          <?php if(isset($_POST["search"])): ?>

            <p class="flex-none w-full text-center text-white mt-5 mb-5">該当データは<b class="text-lg"><?php echo count($rows); ?></b>件です</p>

            <?php if(empty($rows) === false): ?>

              <?php foreach($rows as $row): ?>
                <div class="pt-5 pb-5 w-1/3 md:w-1/4 min-w-min p-4"><a href="detail.php?id=<?php echo $row["id"]; ?>">
                  <div class="">
                    <img src="https://api.lorem.space/image/movie?random/w=900&h=1320" alt="<?php echo $row["title"]; ?>" class="shadow-2xl rounded">
                  </div>
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
  <footer class="sticky bottom-0">
    <div class="flex justify-center pt-8 pb-10">
      <form id="searchform" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="w-96">
        <label for="default-search" class="w-full mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          </div>
          <input type="text" name="title" id="default-search" class="block w-full p-4 pl-10 text-m text-gray-900 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-white-700 dark:placeholder-gray-400 dark:text-gray-800 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search movies title...">
          <button type="submit" name="search" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
        </div>
      </form>
    </div>
  </footer>
</body>
</html>