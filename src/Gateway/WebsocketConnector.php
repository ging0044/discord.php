<?php
namespace p7g\Discord\Gateway;

use Amp\Promise;
use Amp\Websocket;

class WebsocketConnector implements ConnectorInterface {
  public const DEFAULT_OPTIONS = [
    'version' => 6,
    'encoding' => GatewayConnection::ENCODING_JSON,
  ];

  /** @var string $url */
  private $url;

  /** @var array $options */
  private $options;

  public function __construct(string $url, array $options = []) {
    $this->url = $url;
    $this->options = array_replace_recursive(self::DEFAULT_OPTIONS, $options);
  }

  public function connect(): Promise {
    return \Amp\call(function () {
      $version = $this->options['version'];
      $encoding = $this->options['encoding'];
      $url = "{$this->url}?v=$version&encoding=$encoding";
      return yield Websocket\connect($url);
    });
  }
}
