<?php

/**
 * CodeMommy GitHubPHP
 * @author  Candison November <www.kandisheng.com>
 */

require_once(__DIR__ . '/../source/GitHubPHP.php');

use CodeMommy\GitHubPHP;

$server = new GitHubPHP();
$server->setURL('https://github.com/KanDisheng/Home');
$result = $server->getUserInformation();
var_dump($result);