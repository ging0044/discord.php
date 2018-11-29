<?php
namespace p7g\Discord\Common;

use Amp\Struct;

class Embed {
  use Struct;

  /** @var ?string */
  public $title;

  /** @var ?string */
  public $type;

  /** @var ?string */
  public $description;

  /** @var ?string */
  public $url;

  /** @var ?string */
  public $timestamp;

  /** @var ?int */
  public $color;

  /** @var ?Embed\Footer */
  public $footer;

  /** @var ?Embed\Image */
  public $image;

  /** @var ?Embed\Thumbnail */
  public $thumbnail;

  /** @var ?Embed\Video */
  public $video;

  /** @var ?Embed\Provider */
  public $provider;

  /** @var ?Embed\Author */
  public $author;

  /** @var ?Embed\Field[] */
  public $fields;
}
