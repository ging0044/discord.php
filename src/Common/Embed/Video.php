<?php
namespace p7g\Discord\Common\Embed;

use Amp\Struct;

class Video {
  use Struct;

  /** @var ?string */
  public $url;

  /** @var ?int */
  public $height;

  /** @var ?int */
  public $width;
}
