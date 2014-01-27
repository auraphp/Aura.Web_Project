<?php
// set the mode here only if it is not already set.
// this allows for setting via web server, integration testing, etc.
if (! isset($_ENV['AURA_CONFIG_MODE'])) {
    if (($aura_env = getenv('AURA_CONFIG_MODE')) !== false) {
        $_ENV['AURA_CONFIG_MODE'] = $aura_env;
    } else {
        $_ENV['AURA_CONFIG_MODE'] = 'dev';
    }
}
