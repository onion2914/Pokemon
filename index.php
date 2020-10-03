<?php

session_start();

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/pokemon_db.php');

// get todos
$pokemonApp = new \MyApp\PokemonDB();
$poekmons = $pokemonApp->getFirstPokemon();

$searchStatus = [
  "no" => "No",
  "hp" => "HP",
  "attack" => "攻撃",
  "defence" => "防御",
  "spAttack" => "特殊攻撃",
  "spDefence" => "特殊防御",
  "speed" => "すばやさ"
];

$displayStatus = [
  "image" => "",
  "no" => "No",
  "name" => "名前",
  "type" => "タイプ",
  "hp" => "HP",
  "attack" => "攻撃",
  "defence" => "防御",
  "spAttack" => "特殊攻撃",
  "spDefence" => "特殊防御",
  "speed" => "すばやさ"
];

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Pokemon Zukan</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div id="container">
      <form action="" id="pokemon_search_form">
        <table class="pokemon_search">
          <?php foreach ($searchStatus as $key => $state) : ?>
            <tr>
              <th>
               <input type="text" id="pokemon_search_<?= h($key); ?>" placeholder="<?= h($state); ?>">
              </th>
              <th>
                <div class="searchConditions">
                  <input type="radio" name="selector_<?= h($key); ?>" id="selector_<?= h($key); ?>_1" value="<" checked="">
                  <label for="selector_<?= h($key); ?>_1">↓</label>
                  <input type="radio" name="selector_<?= h($key); ?>" id="selector_<?= h($key); ?>_2" value="=" >
                  <label for="selector_<?= h($key); ?>_2">=</label>
                  <input type="radio" name="selector_<?= h($key); ?>" id="selector_<?= h($key); ?>_3" value=">" >
                  <label for="selector_<?= h($key); ?>_3">↑</label>
                </div>
              </th>
            </tr>
          <?php endforeach; ?>
        </table>
        <div class="btn" id="pokemon_search_btn" class="pokemon_search">Search</div>
      </form>
      <table id="searched_info" class="pokemon_search">
        <thead>
          <tr>
            <!-- <?php foreach ($displayStatus as $key => $state) : ?>
              <th>
                <?= h($state); ?>
                <?php if ($key !== 'image') :?>
                  <span id="sorting_btn_<?= h($key); ?>" class="sorting_btn" >▼</span>
                <?php endif; ?>
              </th>
            <?php endforeach; ?> -->
          </tr>
          <!-- <tr>
            <th></th>
            <th>No<span></span></th>
            <th>なまえ</th>
            <th>タイプ</th>
            <th>HP</th>
            <th>攻撃</th>
            <th>防御</th>
            <th>特殊攻撃</th>
            <th>特殊防御</th>
            <th>すばやさ</th>
          </tr> -->
        </thead>
        <tbody>
        </tbody>
        <!-- <?php foreach ($poekmons as $pokemon) : ?>
          <tr>
            <th><?= h($pokemon->id); ?></th>
            <th><?= h($pokemon->name); ?></th>
            <th><?= h($pokemon->types); ?></th>
          </tr>
        <?php endforeach; ?> -->
      </table>
    </div>
    <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="pokemon.js"></script>
  </body>
</html>