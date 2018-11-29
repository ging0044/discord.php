<?php
namespace p7g\Discord\Common;

use Amp\Struct;

class Reaction {
  use Struct;

  /** @var int */
  public $count;

  /** @var bool */
  public $me;

  /** @var Emoji */
  public $emoji;
}
