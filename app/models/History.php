<?php

class Users extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->belongsTo("to", "Users", "fingerprint");
    }
}
