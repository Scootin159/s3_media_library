<?php namespace s3ml;

require_once('includes/common.inc.php');

Loader::LoadLevel(Loader::LOAD_LEVEL_SESSION);

Logins::RequireLogin();

Output::HTML('index.html');