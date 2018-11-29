<?php
namespace p7g\Discord\Common\Embed;

use Amp\Struct;

class Image {
  use Struct;

  /** @var ?string */
  public $url;

  /** @var ?string */
  public $proxyUrl;

  /** @var ?int */
  public $height;

  /** @var ?int */
  public $width;
}
