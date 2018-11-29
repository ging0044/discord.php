<?php
namespace p7g\Discord\Common\Embed;

use Amp\Struct;

class Author {
  use Struct;

  /** @var ?string */
  public $name;

  /** @var ?string */
  public $url;

  /** @var ?string */
  public $iconUrl;

  /** @var ?string */
  public $proxyIconUrl;
}
