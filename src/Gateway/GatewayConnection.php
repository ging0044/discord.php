<?php
namespace p7g\Discord\Gateway;

use Amp;
use Amp\Promise;
use Amp\Websocket;
use Psr\Log;
use Nekoo\EventEmitter;
use Nekoo\EventEmitterInterface;

class GatewayConnection
implements
  Log\LoggerAwareInterface,
  EventEmitterInterface
{
  use Log\LoggerAwareTrait;
  use EventEmitter;

  public const ENCODING_ETF = 'etf'; // TODO
  public const ENCODING_JSON = 'json';

  public const COMPRESSION_ZLIB_STREAM = 'zlib-stream'; // TODO

  public const DEFAULT_OPTIONS = [
    'encoding' => self::ENCODING_JSON,
    'compression' => null,
    'version' => 6, // FIXME: this should be somewhere central
  ];

  /** @var ?Websocket\Connection $connection */
  private $connection;

  /** @var ?object $gateway */
  private $gateway;

  /** @var array $options */
  private $options = self::DEFAULT_OPTIONS;

  public function __construct(array $options = []) {
    $this->options = array_replace_recursive($this->options, $options);

    $this->logger = new Log\NullLogger();

    $this->on(
      OpCode::INVALID_SESSION,
      \Closure::fromCallable([$this, 'handleInvalidSession'])
    );
  }

  private function handleInvalidSession($message) {
    if ($message->d) {
      yield new Amp\Delayed(\random_int(1000, 5000));
      $this->reconnect();
    }
    else {
      $this->logger->emergency('Got invalid session, unable to resume');
      $this->emit('error', new \Exception('Unable to resume session'));
    }
  }

  public function send($data) {
    $this->logger->debug('Sent: ' . json_encode($data));

    $encoded = json_encode($data);
    return $this->connection->send($encoded);
  }

  public function connect($gateway): Promise {
    $this->gateway = $gateway;
    return Amp\call(\Closure::fromCallable([$this, 'doConnect']));
  }

  private function doConnect() {
    $url = $this->gateway->url;
    $version = $this->options['version'];
    $encoding = $this->options['encoding'];
    $url = "$url?v=$version&encoding=$encoding";

    $this->logger->debug("Connecting to gateway at url: $url");

    try {
      $this->connection = yield Websocket\connect($url);
    }
    catch (\Exception $e) {
      $this->logger->emergency('Could not connect to websocket', ['e' => $e]);
      $this->emit('error', $e);
    }
  }

  public function disconnect(int $code, string $message): void {
    $this->connection->close($code, $message);
  }

  public function reconnect(): Promise {
    return Amp\call(\Closure::fromCallable([$this, 'doConnect']));
  }

  public function start(): void {
    Amp\Loop::defer((function () {
      $this->logger->debug('Started');

      try {
        while ($message = yield $this->receiveMessage()) {
          $this->emit($message->op, $message);
        }
      }
      catch (Websocket\ClosedException $e) {
        $this->logger->notice(
          "Websocket connection closed with code {$e->getCode()}: "
          . $e->getMessage()
        );
      }
    })->bindTo($this, self::class));
  }

  private function receiveMessage(): Promise {
    return Amp\call((function () {
      $message = yield $this->connection->receive();
      $body = yield $message->buffer();

      $this->logger->debug("Received: $body");

      return \json_decode($body);
    })->bindTo($this, self::class));
  }
}
