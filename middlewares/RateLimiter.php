<?php
require 'vendor/autoload.php';
use Predis\Client;

function RateLimiter($user_id) {

    static $redis = null;

   
    if ($redis === null) {
        $redis = new Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
    }

   
    $limit  = 10;
    $window = 60;
    $key    = "rate_limit:$user_id";
    $now    = microtime(true);

    // High-performance atomic Lua script
    $script = <<<LUA
local key = KEYS[1]
local now = tonumber(ARGV[1])
local window = tonumber(ARGV[2])
local limit = tonumber(ARGV[3])

-- Remove old requests
redis.call("ZREMRANGEBYSCORE", key, 0, now - window)

-- Add current request
redis.call("ZADD", key, now, now)

-- Count requests
local count = redis.call("ZCARD", key)

-- Set expiry only once
redis.call("EXPIRE", key, window)

return count
LUA;

    // Execute Lua script atomically
    $count = $redis->eval($script, 1, $key, $now, $window, $limit);

    if ($count > $limit) {
        header("HTTP/1.1 429 Too Many Requests");
        header("Retry-After: $window");
        exit("🚫 Too many requests. Try again later.");
    }
}
