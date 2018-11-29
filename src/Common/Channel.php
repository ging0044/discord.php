<?php
namespace p7g\Discord\Common;

use Amp\Struct;

class Channel {
  use Struct;

  /** @var string */
  public $id;

  /** @var int */
  public $type;

  /** @var ?string */
  public $guildId;

  /** @var ?int */
  public $position;

  /** @var ?Overwrite[] */
  public $permissionOverwrites;

  /** @var ?string */
  public $name;

  /** @var ?string */
  public $topic;

  /** @var ?bool */
  public $nsfw;

  /** @var ?string */
  public $lastMessageId;

  /** @var ?int */
  public $bitrate;

  /** @var ?int */
  public $userLimit;

  /** @var ?int */
  public $rateLimitPerUser;

  /** @var ?User[] */
  public $recipients;

  /** @var ?string */
  public $icon;

  /** @var ?string */
  public $ownerId;

  /** @var ?string */
  public $applicationId;

  /** @var ?string */
  public $parentId;

  /** @var ?string */
  public $lastPinTimestamp;
}
