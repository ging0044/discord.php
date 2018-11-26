<?php
namespace p7g\Discord\Gateway;

class OpCode {
  const EVENT = 0;
  const HEARTBEAT = 1;
  const IDENTIFY = 2;
  const STATUS_UPDATE = 3;
  const VOICE_STATE_UPDATE = 4;
  // no 5?
  const RESUME = 6;
  const RECONNECT = 7;
  const REQUEST_GUILD_MEMBERS = 8;
  const INVALID_SESSION = 9;
  const HELLO = 10;
  const HEARTBEAT_ACK = 11;
}
