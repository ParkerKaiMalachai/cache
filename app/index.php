<?php

declare(strict_types=1);

require 'vendor/autoload.php';

$redis = new Redis();

$redis->connect('redis', 6379);
