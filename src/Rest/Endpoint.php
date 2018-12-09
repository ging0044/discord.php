<?php
namespace p7g\Discord\Rest;

final class Endpoint {
  public const GATEWAY = 'gateway';
  public const GATEWAY_BOT = 'gateway/bot';

  public const CHANNELS = 'channels';

  public static function CHANNEL(string $id): string {
    return self::CHANNELS . "/$id";
  }

  public static function CHANNEL_MESSAGES(string $id): string {
    return self::CHANNEL($id) . '/messages';
  }

  public static function CHANNEL_MESSAGE(
    string $channel,
    string $message
  ): string {
    return self::CHANNEL_MESSAGES($channel) . "/$message";
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

  public const GUILDS = 'guilds';

  public static function GUILD(string $id): string {
    return self::GUILDS . "/$id";
  }

  public static function GUILD_CHANNELS(string $guild): string {
    return self::GUILD($guild) . '/channels';
  }

  public static function GUILD_EMOJIS(string $guild): string {
    return self::GUILD($guild) . '/emojis';
  }

  public static function GUILD_EMOJI(string $guild, string $emoji): string {
    return self::GUILD_EMOJIS($guild) . "/$emoji";
  }

  public static function GUILD_MEMBERS(string $guild): string {
    return self::GUILD($guild) . '/members';
  }

  public static function GUILD_MEMBER(string $guild, string $user): string {
    return self::GUILD_MEMBERS($guild) . "/$user";
  }

  public static function GUILD_MEMBER_NICK(
    string $guild,
    string $user
  ): string {
    return self::GUILD_MEMBER($guild, $user) . '/nick';
  }

  public static function GUILD_MEMBER_ROLES(
    string $guild,
    string $user
  ): string {
    return self::GUILD_MEMBER($guild, $user) . '/roles';
  }

  public static function GUILD_MEMBER_ROLE(
    string $guild,
    string $user,
    string $role
  ): string {
    return self::GUILD_MEMBER_ROLES($guild, $user) . "/$role";
  }

  public static function GUILD_BANS(string $guild): string {
    return self::GUILD($guild) . '/bans';
  }

  public static function GUILD_BAN(string $guild, string $user): string {
    return self::GUILD_BANS($guild) . "/$user";
  }

  public static function GUILD_ROLES(string $guild): string {
    return self::GUILD($guild) . '/roles';
  }

  public static function GUILD_ROLE(string $guild, string $role): string {
    return self::GUILD_ROLES($guild) . "/$role";
  }

  public static function GUILD_PRUNE(string $guild): string {
    return self::GUILD($guild) . '/prune';
  }

  public static function GUILD_REGIONS(string $guild): string {
    return self::GUILD($guild) . '/regions';
  }

  public static function GUILD_INVITES(string $guild): string {
    return self::GUILD($guild) . '/invites';
  }

  public static function GUILD_INTEGRATIONS(string $guild): string {
    return self::GUILD($guild) . '/integrations';
  }

  public static function GUILD_INTEGRATION(
    string $guild,
    string $integration
  ): string {
    return self::GUILD_INTEGRATIONS($guild) . "/$integration";
  }

  public static function GUILD_INTEGRATION_SYNC(
    string $guild,
    string $integration
  ): string {
    return self::GUILD_INTEGRATION($guild, $integration) . '/sync';
  }

  public static function GUILD_EMBED(string $guild): string {
    return self::GUILD($guild) . '/embed';
  }

  public static function GUILD_VANITY_URL(string $guild): string {
    return self::GUILD($guild) . '/vanity-url';
  }

  public static function GUILD_WIDGET_PNG(string $guild): string {
    return self::GUILD($guild) . '/widget.png';
  }

  public const INVITES = '/invites';

  public static function INVITE(string $id): string {
    return self::INVITES . "/$id";
  }

  public const USERS = '/users';

  public static function USER(string $id): string {
    return self::USERS . "/$id";
  }

  public static function USER_GUILDS(string $user): string {
    return self::USER($user) . '/guilds';
  }

  public static function USER_GUILD(string $user, string $guild): string {
    return self::USER_GUILDS($user) . "/$guild";
  }

  public static function USER_CHANNELS(string $user): string {
    return self::USER($user) . '/channels';
  }

  public static function USER_CONNECTIONS(string $user): string {
    return self::USER($user) . '/connections';
  }

  public const VOICE_REGIONS = '/voice/regions';

  public static function CHANNEL_WEBHOOKS(string $channel): string {
    return self::CHANNEL($channel) . '/webhooks';
  }

  public static function GUILD_WEBHOOKS(string $guild): string {
    return self::GUILD($guild) . '/webhooks';
  }

  public const WEBHOOKS = '/webhooks';

  public static function WEBHOOK(string $id): string {
    return self::WEBHOOKS . "/$id";
  }

  public static function WEBHOOK_TOKEN(string $webhook, string $token): string {
    return self::WEBHOOK($webhook) . "/$token";
  }

  public static function WEBHOOK_TOKEN_SLACK(
    string $webhook,
    string $token
  ): string {
    return self::WEBHOOK_TOKEN($webhook, $token) . '/slack';
  }

  public static function WEBHOOK_TOKEN_GITHUB(
    string $webhook,
    string $token
  ): string {
    return self::WEBHOOK_TOKEN($webhook, $token) . '/github';
  }
}
