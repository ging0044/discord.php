<?php
namespace p7g\Discord\Common;

use Amp\Struct;

class Message {
  use Struct;

  /** @var string */
  public $id;

  /** @var string */
  public $channelId;

  /** @var ?string */
  public $guildId;

  /** @var User|Webhook */
  public $author;

  /** @var ?GuildMember */
  public $member;

  /** @var string */
  public $content;

  /** @var string */
  public $timestamp;

  /** @var ?string */
  public $editedTimestamp;

  /** @var bool */
  public $tts;

  /** @var bool */
  public $mentionEveryone;

  /** @var User[] */
  public $mentions;

  /** @var int[] */
  public $mentionRoles;

  /** @var Attachment[] */
  public $attachments;

  /** @var Embed[] */
  public $embeds;

  /** @var ?Reaction[] */
  public $reactions;

  /** @var ?string */
  public $nonce;

  /** @var bool */
  public $pinned;

  /** @var ?string */
  public $webhookId;

  /** @var int */
  public $type;

  /** @var ?MessageActivity */
  public $activity;

  /** @var ?MessageApplication */
  public $application;
}
