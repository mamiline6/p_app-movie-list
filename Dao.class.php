<?php
class Dao {
  private $db;

  // データベース接続
  public function __construct(){
    $dsn = "mysql:dbname=movie_db;host=localhost;charset=utf8";
    $user = "root";
    $password = "root";
    $this->db = new PDO($dsn, $user, $password);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  // データベース切断
  public function close(){
    $this->db = null;
  }
  // データベース内名前検索（SELECT）
  public function selectByName($title){
    // $sql = "SELECT * FROM watch_list WHERE deleted_at=false AND title LIKE ? OR overview LIKE ?"; // タイトル以外を検索対象にする
    // $sql = "SELECT * FROM watch_list WHERE deleted_at=false AND title LIKE ? ORDER BY title DESC"; // タイトル順
    $sql = "SELECT * FROM watch_list WHERE deleted_at=false AND title LIKE ? ORDER BY id DESC"; // 登録順（新しい順）
    $statement = $this->db->prepare($sql);
    $statement->execute(array("%".$title."%"));
    $rows = array();
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      $rows[] = $row;
    }
    $statement = null;
    return $rows;
  }
  // データベース登録前確認（重複登録の防止）
  public function checkedByTmdbId($tmdb_id){
    // リクエストされた $tmdb_id が DB に存在するか確認
    $sql = "SELECT EXISTS (SELECT * FROM watch_list WHERE tmdb_id=?)";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($tmdb_id));
    $sqlFlag = $statement->fetch(PDO::FETCH_ASSOC); // 結果形式：連想配列
    $sqlFlag = in_array(1, $sqlFlag); // 連想配列中の値(=>1)の有無を確認
    $statement = null; // 変数を空にする
    return $sqlFlag;   // 配列値(=>1)の有無(bool)を返す
  }
  // 映画 API_ID からデータベース内の ID検索(データ復帰）
  public function searchById($tmdb_id) {
    $sql = "SELECT id FROM watch_list WHERE tmdb_id=?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($tmdb_id));
    $id = $statement->fetch(PDO::FETCH_ASSOC);
    $id = array_values($id); // 連想配列中の値（主キー）を取得（配列型）
    $id= implode($id); // 配列を文字列に変換（String型 ID）
    $statement = null; // 変数を空にする
    return $id;        // 復帰したい ID を返す
  }
  // データベースID検索から deleted_at の状態を確認
  public function checkByDeleteFrag($id) {
    $sql = "SELECT deleted_at FROM watch_list WHERE id=?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($id));
    $deletedFlag = $statement->fetch(PDO::FETCH_ASSOC);
    $deletedFlag = in_array(1, $deletedFlag);
    $statement = null;
    return $deletedFlag;
  }
  // データベースの削除データの復元
  public function restore($deleted_at, $id) {
    $sql = "UPDATE watch_list SET deleted_at=? WHERE id=?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($deleted_at, $id));
    $rowCount = $statement->rowCount();
    $statement = null;
    return $rowCount;
  }
  // データベース内ID検索（SELECT）
  public function selectById($id){
    $sql = "SELECT * FROM watch_list WHERE id=?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($id));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    $statement = null;
    return $row;
  }
  // データベースのデータ登録
  public function insert($tmdb_id, $original_title, $title, $overview, $memo, $poster_path, $backdrop_path, $release_date, $deleted_at) {
    $sql = "INSERT INTO watch_list(tmdb_id, original_title, title, overview, memo, poster_path, backdrop_path, release_date, deleted_at) VALUES(?,?,?,?,?,?,?,?,?)";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($tmdb_id, $original_title, $title, $overview, $memo, $poster_path, $backdrop_path, $release_date, $deleted_at));
    $statement = null;
    return $this->db->lastInsertId();
  }
  // データベースのデータ更新
  public function update($title, $overview, $memo, $deleted_at, $id) {
    $sql = "UPDATE watch_list SET title=?, overview=?, memo=?, deleted_at=? WHERE id=?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($title, $overview, $memo, $deleted_at, $id));
    $rowCount = $statement->rowCount();
    $statement = null;
    return $rowCount;
  }
  // データベースからのデータ削除
  public function delete($id) {
    $sql = "UPDATE watch_list SET deleted_at=true WHERE id=?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($id));
    $rowCount = $statement->rowCount();
    $statement = null;
    return $rowCount; // 削除した行数を返す
  }
}