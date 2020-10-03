<?php

session_start();

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/pokemon_db.php');

// get todos
$pokemonApp = new \MyApp\PokemonDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  try {
    $res = $pokemonApp->post();
    header('Content-Type: application/json');
    echo json_encode($res);
  } catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . '500 Internal Server Error', true, 500);
    echo $e->getMessage();
    exit;
  }
}