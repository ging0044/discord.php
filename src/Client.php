<?php
namespace p7g\Discord;

use Rest\Endpoint;
use Common\Channel;
use Common\Embed;

class Client {
  /** @var Rest\Client $restClient */
  private $restClient;

  /** @var Gateway\Client $gatewayClient */
  private $gatewayClient;

  public function __construct(Token\IToken $token, ?array $options = []) {
    $this->restClient = new Rest\Client($token);
    $this->gatewayClient = new Gateway\Client();
  }

  public function start(array $identity) {
    $this->gatewayClient->setIdentity($identity);
    $gateway = yield $this->restClient->get(Endpoint::GATEWAY_BOT);
    $this->gatewayClient->start();
  }

  public function on(...$args) {
    return $this->gatewayClient->getEventStream()->on(...$args);
  }

  public function once(...$args) {
    return $this->gatewayClient->getEventStream()->once(...$args);
  }

  public function send(
    Channel $channel,
    string $message,
    Embed $embed
  ): Promise {
    return \Amp\call(function () use ($channel, $message, $embed) {
      $data = [
        'content' => $message,
      ];
      if ($embed !== null) {
        $data['embed'] = $embed;
      }
      return yield $this->restClient->post(
        Endpoint::CHANNEL_MESSAGES($channel->id),
        $data
      );
    });
  }
}
