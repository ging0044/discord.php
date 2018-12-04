<?php
namespace p7g\Discord\Rest;

use Amp\Artax;
use Amp\Promise;
use Psr\Log;
use p7g\Discord\Token\TokenInterface;

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

  /** @var array $options */
  private $options = self::DEFAULT_OPTIONS;

  /** @var string $discordphpVersion */
  private static $discordphpVersion;

  /** @var string $discordphpHomepage */
  private static $discordphpHomepage;

  public static function getUserAgent(): string {
    if (empty(self::$discordphpHomepage) || empty(self::$discordphpVersion)) {
      $composer = json_decode(
        file_get_contents(__DIR__ . '/../../composer.json')
      );
      self::$discordphpVersion = $composer->version;
      self::$discordphpHomepage = $composer->homepage;
    }
    $url = self::$discordphpHomepage;
    $version = self::$discordphpVersion;
    return "DiscordBot ($url, $version)";
  }

  public static function qualifyEndpoint(
    int $version,
    string $endpoint
  ): string {
    return self::DISCORD_API_URL . "/v$version/$endpoint";
  }

  public function __construct(TokenInterface $token, array $options = []) {
    $this->client = new Artax\DefaultClient();
    $this->options = array_replace_recursive($this->options, $options);

    $this->headers = [
      'Authorization' => (string) $token,
      'User-Agent' => self::getUserAgent(),
    ];

    $this->logger = new Log\NullLogger();
  }

  public function request(
    string $method,
    string $endpoint,
    $data = null
  ): Promise {
    $url = self::qualifyEndpoint($this->options['version'], $endpoint);
    $request = (new Artax\Request($url))
      ->withHeaders($this->headers)
      ->withMethod($method);

    $body = json_encode($data);
    if ($data !== null) {
      $request->withBody($body);
    }

    $rand = \random_int(0, PHP_INT_MAX);
    $this->logger->debug(
      "Making $method request ($rand) to (url) with body: $body"
    );

    return \Amp\call(function () use ($request, $rand) {
      $response = yield $this->client->request($request);
      $body = yield $response->getBody();

      $this->logger->debug("Received result ($rand): $body");

      return \json_decode($body);
    });
  }

  public function connect(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::CONNECT, $endpoint, $data);
    });
  }

  public function delete(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::DELETE, $endpoint, $data);
    });
  }

  public function get(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::GET, $endpoint, $data);
    });
  }

  public function head(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::HEAD, $endpoint, $data);
    });
  }

  public function options(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::OPTIONS, $endpoint, $data);
    });
  }

  public function patch(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::PATCH, $endpoint, $data);
    });
  }

  public function post(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::POST, $endpoint, $data);
    });
  }

  public function put(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::PUT, $endpoint, $data);
    });
  }

  public function trace(string $endpoint, $data = null): Promise {
    return \Amp\call(function () use ($endpoint, $data) {
      return yield $this->request(Method::TRACE, $endpoint, $data);
    });
  }
}
