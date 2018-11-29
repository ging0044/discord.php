<?php
namespace p7g\Discord\Gateway;

use Amp\Promise;

interface ConnectorInterface {
  public function __construct(string $url, array $options = []);

  public function connect(): Promise;
}