<?php
namespace p7g\Discord\Common\Message;

use Amp\Struct;

class Activity {
  use Struct;

  /** @var int */
  public $type;

  /** @var ?string */
  public $partyId;
}
