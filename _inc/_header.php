<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
  <?php if(isset($page_title) || $page_title !== "") echo $page_title; ?>｜<?php echo $app_title; ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
</head>
<body class="bg-gray-800">
  <div class="wrapper relative z-20">
    <header class="header sticky top-0 pt-2 pb-2 shadow-xl bg-gray-700 z-30">
      <div class="flex items-center justify-between px-5">
        <h1 class="text-xl font-sans"><a href="index.php" class="">
          <div class="flex items-center text-3xl font-extrabold text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="white" class="w-8 h-8 items-center">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0118 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0118 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 016 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5" />
            </svg>
            <span class="pl-1 pr-1 bg-clip-text text-transparent bg-gradient-to-r from-sky-400 to-indigo-400">
              <?php echo $app_title; ?>
            </span>
            <?php echo $search_name; ?>
          </div>
        </a></h1>
        <ul class="flex text-white space-x-4">
          <li><a href="index.php" class="<?php if(strstr($_SERVER['REQUEST_URI'], 'index') || strstr($_SERVER['REQUEST_URI'], 'insert') || strstr($_SERVER['REQUEST_URI'], 'restore')): ?>underline underline-offset-8<?php endif; ?>"><?php echo $search_name; ?></a></li>
          <li><a href="list.php" class="<?php if(strstr($_SERVER['REQUEST_URI'], 'list') || strstr($_SERVER['REQUEST_URI'], 'detail') || strstr($_SERVER['REQUEST_URI'], 'delete') || strstr($_SERVER['REQUEST_URI'], 'update.php')): ?>underline underline-offset-8<?php endif; ?>"><?php echo $list_name; ?></a></li>
        </ul>
      </div>
    </header>
<?php if(isset($backdrop_path) && $backdrop_path !== ""): ?>
  <main class="bg-fixed img-wrapper bg-no-repeat bg-center bg-cover bg-black/[.70] bg-blend-darken" style="background-image: url(https://image.tmdb.org/t/p/original<?php echo $backdrop_path; ?>);">
<?php else: ?>
  <main class="bg-gray-800">
<?php endif; ?>