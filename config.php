<?php

ini_set('display_errors', 1);

switch (true) {
  case ($_SERVER["HTTP_HOST"] === 'pokemon-zukan.herokuapp.com'):
      // 本番環境の設定
      define('DSN', 'mysql:host=us-cdbr-east-06.cleardb.net;dbname=heroku_ed440b15b403d71;charset=utf8');
      define('DB_USERNAME', 'bf467d3cc56ed9');
      define('DB_PASSWORD', '60cf0df1');
      break;
  default:
      // ローカル開発環境の設定
      define('DSN', 'mysql:host=localhost;dbname=pokemon_db');
      define('DB_USERNAME', 'dbuser');
      define('DB_PASSWORD', 'poruno&&0200');
      break;
}
