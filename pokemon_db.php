<?php

// CSRF対策
// Token発行してSessionに格納
// フォームからもTokenを発行、送信
// Check

namespace MyApp;

class PokemonDB {
  private $_db;

  public function __construct() {

    $this->_createToken();

    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  private function _createToken() {
    if(!isset($_SESSION['token'])){
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
  }

  public function getFirstPokemon() {
    $stmt = $this->_db->query("select * from pokemons where id < 10");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function getAll() {
    $stmt = $this->_db->query("select * from pokemons");
    //return $stmt->fetchAll(\PDO::FETCH_OBJ);
    var_dump($stmt->fetchAll(\PDO::FETCH_OBJ));
  }

  public function post() {
    $this->_validateToken();

    if( !isset($_POST['mode'])){
      throw new \Exception('mode not set!');
    }

    switch ($_POST['mode']) {
      case 'update':
        return $this->_update();
      case 'create':
        return $this->_create();
      case 'delete':
        return $this->_delete();
      case 'search':
        return $this->_search();
    }
  }

  private function _validateToken(){
    if(
      !isset($_SESSION['token']) ||
      !isset($_POST['token']) ||
      $_SESSION['token'] !== $_POST['token']
    ) {
      throw new \Exception('invalid token!\n' + $_SESSION['token'] + '\n' + $_POST['token']);
    }
  }

  private function _update(){
    if( !isset($_POST['id'])){
      throw new \Exception('[update] id not set!');
    }

    $this->_db->beginTransaction();

    $sql = sprintf("update todos set state = (state + 1) %% 2 where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    $sql = sprintf("select state from todos where id = %d", $_POST['id']);
    $stmt = $this->_db->query($sql);
    $state = $stmt->fetchColumn();

    $this->_db->commit();

    return [
      'state' => $state,
    ];
  } 

  private function _create(){
    if( !isset($_POST['title']) || $_POST['title'] === ''){
      throw new \Exception('[delete] id not set!');
    }

    $sql = sprintf("insert into todos (title) values (:title)");
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([':title' => $_POST['title']]);

    return [
      'id' => $this->_db->lastInsertId()
    ];
  } 

  private function _delete(){
    if( !isset($_POST['id'])){
      throw new \Exception('[delete] id not set!');
    }

    $sql = sprintf("delete from todos where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    return [];
  } 

  private function _search(){
    if( !isset($_POST['status'])){
      throw new \Exception('[search] status not set!');
    }

    $sql = sprintf("select * from pokemons where 1");

    $no=($_POST['status']['no']['value'] !== '') ? $_POST['status']['no']['value'] : null;
    $hp=($_POST['status']['hp']['value'] !== '') ? $_POST['status']['hp']['value'] : null;
    $attack=($_POST['status']['attack']['value'] !== '') ? $_POST['status']['attack']['value'] : null;
    $defence=($_POST['status']['defence']['value'] !== '') ? $_POST['status']['defence']['value'] : null;
    $spAttack=($_POST['status']['spAttack']['value'] !== '') ? $_POST['status']['spAttack']['value'] : null;
    $spDefence=($_POST['status']['spDefence']['value'] !== '') ? $_POST['status']['spDefence']['value'] : null;
    $speed=($_POST['status']['speed']['value'] !== '') ? $_POST['status']['speed']['value'] : null;

    if(!is_null($no)) $sql .=  " and pokemonNo" . $_POST['status']['no']['condition'] . $no;
    if(!is_null($hp)) $sql .=  " and hp" . $_POST['status']['hp']['condition'] . $hp;
    if(!is_null($attack)) $sql .=  " and attack" . $_POST['status']['attack']['condition'] . $attack;
    if(!is_null($defence)) $sql .=  " and defence" . $_POST['status']['defence']['condition'] . $defence;
    if(!is_null($spAttack)) $sql .=  " and spAttack" . $_POST['status']['spAttack']['condition'] . $spAttack;
    if(!is_null($spDefence)) $sql .=  " and spDefence" . $_POST['status']['spDefence']['condition'] . $spDefence;
    if(!is_null($speed)) $sql .=  " and speed" . $_POST['status']['speed']['condition'] . $speed;
    // if(!is_null($no)) $sql .=  " and pokemonNo" + $_POST['status']['no']['condition'] + sprintf("%d", $no);

    // var_dump($sql);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();
    
    // $stmt = $this->_db->query("select * from pokemons where id < 10");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  } 

}