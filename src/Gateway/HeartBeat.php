<?php
namespace p7g\Discord\Gateway;

use Psr\Log;

final class Heartbeat implements Log\LoggerAwareInterface {
  use Log\LoggerAwareTrait;

  /** @var GatewayConnection $connection */
  private $connection;

  /** @var float $lastBeat */
  private $lastBeat;

  /** @var float $lastAck */
  private $lastAck;

  /** @var bool $acknowledged */
  private $acknowledged = true;

  /** @var int $interval */
  private $interval;

  /** @var string $beat watcher ID from Amp\Loop::repeat */
  private $beat;

  /** @var ?int $sequence */
  private $sequence;

  /** @var \Closure $doHeartbeat */
  private $doHeartbeat;

  /** @var \Closure $recordHeartbeatAck */
  private $recordHeartbeatAck;

  /** @var \Closure $handleHello */
  private $handleHello;

  public function __construct(GatewayConnection $connection) {
    $this->connection = $connection;
    $this->logger = new Log\NullLogger();

    $this->handleHello = \Closure::fromCallable([$this, 'handleHello']);
    $this->doHeartbeat = \Closure::fromCallable([$this, 'doHeartbeat']);
    $this->recordHeartbeatAck = \Closure::fromCallable(
      [$this, 'recordHeartbeatAck']
    );

    $this->addEventListeners();
  }

  private function addEventListeners(): void {
    $this->connection->once(OpCode::HELLO, $this->handleHello);
    $this->connection->on(OpCode::HEARTBEAT_ACK, $this->recordHeartbeatAck);
    $this->connection->on(OpCode::HEARTBEAT, $this->doHeartbeat);
    $this->connection->on(OpCode::EVENT, (function ($message) {
      if ($message->s > $this->sequence) {
        $this->sequence = $message->s;
      }
    })->bindTo($this, self::class));
    $this->connection->on('disconnect', function ($code) {
      if ($code >= 1000 && $code < 2000) {
        $this->reset();
      }
    });
  }

  public function start(): void {
    $this->logger->debug("Starting heartbeat at interval {$this->interval}");
    $this->beat = \Amp\Loop::repeat($this->interval, $this->doHeartbeat);
    \Amp\Loop::unreference($this->beat);
  }

  public function stop(): void {
    \Amp\Loop::disable($this->beat);
  }

  public function resume(): void {
    \Amp\Loop::enable($this->beat);
  }

  private function handleHello($message): void {
    $this->interval = $message->d->heartbeat_interval;
    $this->logger->debug("Got heartbeat interval of {$this->interval}");
    $this->start();
  }

  private function recordHeartbeatAck(): void {
    $this->lastAck = \microtime(true);
    $this->acknowledged = true;
    $latency = round(($this->lastAck - $this->lastBeat) * 1000, 2);
    $this->logger->debug("Received heartbeat ack, latency {$latency}ms");
  }

  private function reset(): void {
    \Amp\Loop::cancel($this->beat);
    $this->acknowledged = true;
  }

  private function doHeartbeat(): void {
    if (!$this->acknowledged) {
      $this->logger->warning(
        "No ack sent for heartbeat at {$this->lastBeat}, "
        . "last ack at {$this->lastAck}"
      ); // TODO: handle properly

      $this->reset();
      $this->connection->disconnect(
        \Amp\Websocket\Code::ABNORMAL_CLOSE,
        'Heartbeat not acknowledged'
      ); // TODO: What code should be used?
      $this->connection->reconnect();
    }

    $this->logger->debug('Sending heartbeat');

    $this->acknowledged = false;
    $this->lastBeat = microtime(true);
    $this->connection->send([
      'op' => OpCode::HEARTBEAT,
      'd' => $this->sequence,
    ]);
  }
}
