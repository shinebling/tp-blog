<?php

namespace app\listener;


class Message
{
    /**
     * 信息码注册
     */
    public function handle($event)
    {
        $codes = config('message.code');
        foreach ($codes as $key => $value) {
            define($key, $value);
        }
    }
}

