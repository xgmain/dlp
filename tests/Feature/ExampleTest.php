<?php

it ('site is running', function() {
    return $this->get('/')->assertStatus(200);
});
