<?php

/**
 * Overkill class to read environment variables in from .env file
 * Taken from here: https://dev.to/fadymr/php-create-your-own-php-dotenv-3k2i
 * Class types removed for compatibility with legacy php
 */
class DotEnv
{
  /**
   * The directory where the .env file can be located.
   *
   * @var string
   */
  protected $path;


  public function __construct($path)
  {
    if (!file_exists($path)) {
      throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
    }
    $this->path = $path;
  }

  public function load()
  {
    if (!is_readable($this->path)) {
      throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
    }

    $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
      if (strpos(trim($line), '#') === 0) {
        continue;
      }

      list($name, $value) = explode('=', $line, 2);
      $name = trim($name);
      $value = trim($value);

      if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
      }
    }
  }
}


/**
 * This is used in production to read in env variables from root directory
 * NOTE: this will raise exception in development because only the
 * dir with the php code is mounted for serving, leaving behind the .env
 * file. For development with docker, the env variables are read in from
 * the docker-compose.*.yml files
 */
try {
  // Load all env vars into $_ENV
  (new DotEnv(__DIR__ . '/../.env'))->load();
  // echo ">>>>> " . getenv('MY_TEST_ENV'). "<br><br>";
} catch (Exception $e) {
  // Exceptions always raised in development; just ignore
}
