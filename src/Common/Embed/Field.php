<?php
namespace p7g\Discord\Common\Embed;

use Amp\Struct;

class Field {
  use Struct;

  /** @var string */
  public $name;

  /** @var string */
  public $value;

  /** @var ?bool */
  public $inline;
}
