<?php
namespace p7g\Discord\Common\Message;

final class MessageType {
  public const DEFAULT = 0;
  public const RECIPIENT_ADD = 1;
  public const RECIPIENT_REMOVE = 2;
  public const CALL = 3;
  public const CHANNEL_NAME_CHANGE = 4;
  public const CHANNEL_ICON_CHANGE = 5;
  public const CHANNEL_PINNED_MESSAGE = 6;
  public const GUILD_MEMBER_JOIN = 7;
}
