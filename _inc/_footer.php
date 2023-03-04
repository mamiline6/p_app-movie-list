<?php if(strstr($_SERVER['REQUEST_URI'], 'manipulation')): ?>
    <footer class="sticky bottom-0 transition">
      <div class="flex flex-wrap justify-center m-10 pb-10 text-white">
        <p><small>&copy; 2023 Movie Search & Store. Powered by <a href="https://www.themoviedb.org/documentation/api">TMDb</a></small></p>
      </div>
    </footer>
<?php elseif(strstr($_SERVER['REQUEST_URI'], 'list') || strstr($_SERVER['REQUEST_URI'], 'detail') || strstr($_SERVER['REQUEST_URI'], 'update')): ?>
     <footer>
      <div class="flex flex-wrap justify-center m-10 pb-10 text-white">
        <p><small>&copy; 2023 Movie Search & Store. Powered by <a href="https://www.themoviedb.org/documentation/api">TMDb</a></small></p>
      </div>
    </footer>
<?php elseif(strstr($_SERVER['REQUEST_URI'], 'index')): ?>
      <footer class="sticky bottom-0 transition">
        <div class="flex flex-wrap justify-center m-10 pb-10 text-white">
          <p><small>&copy; 2023 Movie Search & Store. Powered by <a href="https://www.themoviedb.org/documentation/api">TMDb</a></small></p>
        </div>
      </footer>
    </div>
<?php else: ?>
      <footer class="fixed bottom-10 w-full">
        <div class="flex flex-wrap justify-center m-10 text-white">
          <p><small>&copy; 2023 Movie Search & Store. Powered by <a href="https://www.themoviedb.org/documentation/api">TMDb</a></small></p>
        </div>
      </footer>
    </div>
<?php endif; ?>
  </body>
</html>