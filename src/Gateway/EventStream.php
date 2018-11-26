<?php
namespace p7g\Discord\Gateway;

use Nekoo\EventEmitter;
use Nekoo\EventEmitterInterface;
use Psr\Log;

class EventStream implements Log\LoggerAwareInterface, EventEmitterInterface {
  use Log\LoggerAwareTrait;
  use EventEmitter;

  /** @var GatewayConnection $connection */
  private $connection;

  public function __construct(GatewayConnection $connection) {
    $this->connection = $connection;

    $this->connection->on(
      OpCode::EVENT,
      \Closure::fromCallable([$this, 'readEvent'])
    );

    $this->logger = new Log\NullLogger();
  }

  private function readEvent($message): void {
    $this->logger->debug('Received event: ' . json_encode($message));
    $event = $message->t;
    $data = $message->d;
    $this->emit($event, $data);
  }
}
