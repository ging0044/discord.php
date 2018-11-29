<?php
namespace p7g\Discord\Gateway;

final class Status {
  public const UNKNOWN_ERROR = 4000;
  public const UNKNOWN_OPCODE = 4001;
  public const DECODE_ERROR = 4002;
  public const NOT_AUTHENTICATED = 4003;
  public const AUTHENTICATION_FAILED = 4004;
  public const ALREADY_AUTHENTICATED = 4005;
  public const INVALID_SEQ = 4007;
  public const RATE_LIMITED = 4008;
  public const SESSION_TIMEOUT = 4009;
  public const INVALID_SHARD = 4010;
  public const SHARDING_REQUIRED = 4011;

  public static function getMessage(int $status): string {
    $message = null;
    switch ($status) {
      case self::UNKNOWN_ERROR:
        $message = 'Unknown error';
        break;
      case self::UNKNOWN_OPCODE:
        $message = 'Sent invalid opcode';
        break;
      case self::DECODE_ERROR:
        $message = 'Sent invalid payload';
        break;
      case self::NOT_AUTHENTICATED:
        $message = 'Sent payload before identify';
        break;
      case self::AUTHENTICATION_FAILED:
        $message = 'Invalid token';
        break;
      case self::ALREADY_AUTHENTICATED:
        $message = 'Sent more than one identify';
        break;
      case self::INVALID_SEQ:
        $message = 'Invalid sequence sent while resuming';
        break;
      case self::RATE_LIMITED:
        $message = 'Rate limited';
        break;
      case self::SESSION_TIMEOUT:
        $message = 'Session timed out';
        break;
      case self::INVALID_SHARD:
        $message = 'Invalid shard sent while identifying';
        break;
      case self::SHARDING_REQUIRED:
        $message = 'Session would have too many guilds, must shard';
        break;
      default:
        throw new \Exception("Unrecognized status $status");
    }
    return "$message ($status)";
  }
}
