<?php
namespace p7g\Discord;

use Amp\Promise;
use p7g\Discord\Rest\Endpoint;
use Common\Channel;
use Common\Embed;

class Client {
  /** @var Rest\Client $restClient */
  private $restClient;

  /** @var Gateway\Client $gatewayClient */
  private $gatewayClient;

  /** @var Token\TokenInterface $token */
  private $token;

  public function __construct(
    Token\TokenInterface $token,
    ?array $options = []
  ) {
    $this->token = $token;
    $this->restClient = new Rest\Client($token);
    $this->gatewayClient = new Gateway\Client();
  }

  public function start(array $identity) {
    $this->gatewayClient->setIdentity($identity);
    \Amp\Loop::run(function () {
      $gateway = yield $this->restClient->get(Endpoint::GATEWAY_BOT);
      $this->gatewayClient->start($gateway);
    });
  }

  public function on(...$args) {
    return $this->gatewayClient->getEventStream()->on(...$args);
  }

  public function once(...$args) {
    return $this->gatewayClient->getEventStream()->once(...$args);
  }

  public function send(
    string $channel,
    string $message,
    ?Embed $embed = null
  ): Promise {
    $message = \str_replace((string) $this->token, '<redacted>', $message);
    return \Amp\call(function () use ($channel, $message, $embed) {
      $data = [
        'content' => $message,
      ];
      if ($embed !== null) {
        $data['embed'] = $embed;
      }
      return yield $this->restClient->post(
        Endpoint::CHANNEL_MESSAGES($channel),
        $data
      );
    });
  }

  public function setLogger(\Psr\Log\LoggerInterface $logger): void {
    $this->gatewayClient->setLogger($logger);
    $this->restClient->setLogger($logger);
  }
}
