<?php
namespace p7g\Discord\Gateway;

use p7g\Discord\Token\IToken;
use p7g\Discord\Gateway\WebsocketConnector;
use p7g\Discord\Gateway\EventStream;
use p7g\Discord\Gateway\Heartbeat;
use p7g\Discord\Gateway\Identifier;

class Client {
  /** @var GatewayConnection $connection */
  private $connection;

  /** @var EventStream $eventStream */
  private $eventStream;

  /** @var Heartbeat $heartbeat */
  private $heartbeat;

  /** @var Identifier $identifier */
  private $identifier;

  // FIXME: should have class for gateway
  public function __construct(array $options = []) {
    $this->connection = new GatewayConnection();

    $this->eventStream = new EventStream($this->connection);
    $this->heartbeat = new Heartbeat($this->connection);
    $this->identifier = new Identifier($this->connection, $this->eventStream);
  }

  public function setIdentity(array $identity): void {
    $this->identifier->setIdentity($identity);
  }

  public function start(object $gateway): void {
    $this->connection->setConnector(
      new WebsocketConnector($gateway->url)
    );
    $this->connection->start();
  }

  public function getEventStream(): EventStream {
    return $this->eventStream;
  }
}
