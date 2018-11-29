<?php
namespace p7g\Discord\Common\Embed;

use Amp\Struct;

class Footer {
  use Struct;

  /** @var string */
  public $footer;

  /** @var ?string */
  public $iconUrl;

  /** @var ?string */
  public $proxyIconUrl;
}
