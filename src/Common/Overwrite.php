<?php
namespace p7g\Discord\Common;

use Amp\Struct;

class Overwrite {
  use Struct;

  /** @var string */
  public $id;

  /** @var string */
  public $type;

  /** @var int */
  public $allow;

  /** @var int */
  public $deny;
}
