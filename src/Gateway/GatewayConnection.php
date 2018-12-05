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

  /** @var ConnectorInterface $connector */
  private $connector;

  /** @var ?Websocket\Connection $connection */
  private $connection;

  /** @var ?object $gateway */
  private $gateway;

  /** @var bool $running */
  private $running = false;

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

  private function connect() {
    $this->logger->debug('Getting connection');
    try {
      $this->connection = yield $this->connector->connect();
    }
    catch (\Exception $e) { // TODO: keep trying
      $this->logger->emergency('Failed to get connection', ['e' => $e]);
      $this->emit('error', $e);
    }
  }

  public function setConnector(ConnectorInterface $connector): void {
    $this->connector = $connector;
  }

  public function disconnect(int $code, string $message): void {
    $this->connection->close($code, $message);
  }

  public function reconnect(): Promise {
    return Amp\call(\Closure::fromCallable([$this, 'connect']));
  }

  public function start(): void {
    $this->running = true;
    Amp\Loop::defer(function () {
      yield Amp\call(\Closure::fromCallable([$this, 'connect']));

      $this->logger->debug('Started');
      try {
        while ($this->running && $message = yield $this->receiveMessage()) {
          $this->emit($message->op, $message);
        }
      }
      catch (Websocket\ClosedException $e) {
        $this->logger->notice(
          "Websocket connection closed with code {$e->getCode()}: "
          . $e->getMessage()
        );
      }
    });
  }

  public function stop() {
    $this->running = false;
  }

  private function receiveMessage(): Promise {
    return Amp\call(function () {
      $message = yield $this->connection->receive();
      $body = yield $message->buffer();

      $this->logger->debug("Received: $body");

      return \json_decode($body);
    });
  }
}
