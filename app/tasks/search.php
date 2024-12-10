<?php

declare(strict_types=1);

use src\classes\RedisCache;

require '../vendor/autoload.php';

$redis = new Redis();

$redis->connect('redis', 6379);


$redisManager = RedisCache::getInstance();
$redisManager->setHelper($redis);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['value'])) {

    $query = "SELECT post_name FROM table_name WHERE description LIKE '%" . $_POST['value'] . "%' LIMIT 5";

    $response = $redisManager->get(md5($query));

    if ($response) {

        header("Content-type:application/json");

        echo json_encode(['value' => unserialize($response), 'cache' => true], JSON_PRETTY_PRINT);

        return;
    }

    $response = $db->query($query)->fetchAll();

    $redisManager->set(md5($query), $response);

    header("Content-type:application/json");

    echo json_encode(['value' => unserialize($response), 'cache' => false], JSON_PRETTY_PRINT);
}
