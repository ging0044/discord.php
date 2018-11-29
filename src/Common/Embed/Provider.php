<?php
namespace p7g\Discord\Common\Embed;

use Amp\Struct;

class Provider {
  use Struct;

  /** @var ?string */
  public $name;

  /** @var ?string */
  public $url;
}
