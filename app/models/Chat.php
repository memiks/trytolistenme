<?php

class Chat extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->belongsTo("fromuser", "Users", "fingerprint");
        $this->belongsTo("friend", "Users", "fingerprint");
    }
}
