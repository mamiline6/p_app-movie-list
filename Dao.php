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
    // $sql = "SELECT * FROM watch_list WHERE deleted_at=false AND title LIKE ? OR overview LIKE ?";
    $sql = "SELECT * FROM watch_list WHERE deleted_at=false AND title LIKE ?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array("%".$title."%"));
    $rows = array();
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      $rows[] = $row;
    }
    $statement = null;
    return $rows;
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
  public function update($id, $original_title, $title, $overview, $memo) {
    $sql = "UPDATE watch_list SET title=?, overview=?, memo=? WHERE id=?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array($title, $overview, $memo, $id));
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