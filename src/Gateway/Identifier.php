<?php
namespace p7g\Discord\Gateway;

use Psr\Log;

final class Identifier implements Log\LoggerAwareInterface {
  use Log\LoggerAwareTrait;

  /** @var GatewayConnection $connection */
  private $connection;

  /** @var EventStream $eventStream */
  private $eventStream;

  /** @var mixed $identity */
  private $identity;

  /** @var ?string $sessionId */
  private $sessionId;

  /** @var bool $resume */
  private $resume;

  public function __construct(
    GatewayConnection $connection,
    EventStream $eventStream
  ) {
    $this->connection = $connection;
    $this->eventStream = $eventStream;
    $this->logger = new Log\NullLogger();

    $this->connection->on(
      OpCode::HELLO,
      \Closure::fromCallable([$this, 'handleHello'])
    );

    $this->eventStream->on(
      Event::READY,
      \Closure::fromCallable([$this, 'handleReady'])
    );

    $this->connection->on(
      OpCode::INVALID_SESSION,
      \Closure::fromCallable([$this, 'handleInvalidSession'])
    );
  }

  public function setIdentity($identity): self {
    $this->identity = $identity;
    return $this;
  }

  private function handleHello(): void {
    if ($this->resume) {
      $this->resume();
    }
    else {
      $this->logger->debug('Sending Identify, next time will send Resume');
      $this->identify();
      $this->resume = true;
    }
  }

  private function handleReady($data): void {
    $this->sessionId = $data->session_id;
    $this->logger->debug(
      'Got new session ID',
      ['sessionId' => $this->sessionId]
    );
  }

  private function handleInvalidSession(): void {
    $this->logger->debug('Got invalid session, will identify next');
    $this->resume = false;
  }

  private function resume(): void {
    $data = $this->identity; // TODO: make this be whatever
    $this->logger->debug(
      'Attempting to resume the session',
      $data
    );
    $this->connection->send([
      'op' => OpCode::RESUME,
      'd' => $data,
    ]);
  }

  private function identify(): void { // TODO: after identify, switch to resume
    $this->logger->debug('Identifying', ['identity' => $this->identity]);
    $this->connection->send([
      'op' => OpCode::IDENTIFY,
      'd' => $this->identity,
    ]);
  }
}
