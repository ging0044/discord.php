<?php
namespace p7g\Discord\Rest;

use Amp\Artax;
use Amp\Promise;
use Psr\Log;
use p7g\Discord\Token\IToken;

class Client implements Log\LoggerAwareInterface {
  use Log\LoggerAwareTrait;

  public const DEFAULT_OPTIONS = [
    'version' => 6,
  ];

  /** @const string DISCORD_API_URL */
  public const DISCORD_API_URL = 'https://discordapp.com/api';

  /** @var Artax\Client $client */
  private $client;

  /** @var array $headers */
  private $headers;

  public function __construct(IToken $token, array $options = []) {
    $this->client = new Artax\DefaultClient();
    $this->options = array_replace_recursive(self::DEFAULT_OPTIONS, $options);

    $this->headers = [
      'Authorization' => (string) $token,
    ];

    $this->logger = new Log\NullLogger();
  }

  public function get(string $url, $data = null): Promise {
    $version = $this->options['version'];
    $url = self::DISCORD_API_URL . "/v$version$url";
    return \Amp\call((function () use ($url, $data) {
      $request = (new Artax\Request($url))
        ->withHeaders($this->headers);

      if ($data !== null) {
        $request->withBody(json_encode($data));
      }

      $rand = random_int(0, PHP_INT_MAX);
      $this->logger->debug(
        "Making GET request ($rand) to $url with body: " . json_encode($data)
      );

      $response = yield $this->client->request($request);
      $body = yield $response->getBody();

      $this->logger->debug("Received result ($rand): $body");

      return json_decode($body);
    })->bindTo($this, self::class));
  }
}
