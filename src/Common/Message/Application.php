<?php
namespace p7g\Discord\Common\Message;

use Amp\Struct;

class Application {
  use Struct;

  /** @var string */
  public $id;

  /** @var string */
  public $coverImage;

  /** @var string */
  public $description;

  /** @var string */
  public $icon;

  /** @var string */
  public $name;
}
