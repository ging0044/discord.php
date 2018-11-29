<?php
namespace p7g\Discord\Rest;

final class Endpoint {
  public const GATEWAY = 'gateway';
  public const GATEWAY_BOT = 'gateway/bot';

  public static function CHANNEL(string $id): string {
    return "channels/$id";
  }

  public static function CHANNEL_MESSAGES(string $id): string {
    return self::CHANNEL($id) . '/messages';
  }

  public static function CHANNEL_MESSAGE(
    string $channel,
    string $message
  ): string {
    return self::CHANNEL_MESSAGES($cId) . "/$mId";
  }

  public static function REACTION(
    string $channel,
    string $message,
    string $emoji,
    string $user = '@me'
  ): string {
    return self::REACTIONS($channel, $message, $emoji) . "/$user";
  }

  public static function REACTIONS(
    string $channel,
    string $message,
    string $emoji = null
  ): string {
    if ($emoji !== null) {
      return self::CHANNEL_MESSAGE($channel, $message) . "/reactions/$emoji";
    }
    return self::CHANNEL_MESSAGE($channel, $message) . '/reactions';
  }

  public static function MESSAGES_BULK_DELETE(string $channel): string {
    return self::CHANNEL_MESSAGES($channel) . '/bulk-delete';
  }

  public static function CHANNEL_PERMISSIONS(
    string $channel,
    string $overwrite
  ): string {
    return self::CHANNEL($channel) . "/permissions/$overwrite";
  }

  public static function CHANNEL_INVITES(string $channel): string {
    return self::CHANNEL($channel) . '/invites';
  }

  public static function CHANNEL_TYPING(string $channel): string {
    return self::CHANNEL($channel) . '/typing';
  }

  public static function CHANNEL_PINS(string $channel): string {
    return self::CHANNEL($channel) . '/pins';
  }

  public static function CHANNEL_PIN(string $channel, string $message): string {
    return self::CHANNEL_PINS($channel) . "/$message";
  }

  public static function CHANNEL_RECIPIENT(
    string $channel,
    string $user
  ): string {
    return self::CHANNEL($channel) . "/recipients/$user";
  }
}
