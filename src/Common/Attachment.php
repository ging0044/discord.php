<?php
namespace p7g\Discord\Common;

use Amp\Struct;

class Attachment {
  use Struct;

  /** @var string */
  public $id;

  /** @var string */
  public $filename;

  /** @var int */
  public $size;

  /** @var string */
  public $url;

  /** @var string */
  public $proxyUrl;

  /** @var ?int */
  public $height;

  /** @var ?int */
  public $width;
}
