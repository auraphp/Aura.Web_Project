<?php
if (! isset($_ENV['AURA_PROJECT_START_SERVER'])) {
    $_ENV['AURA_PROJECT_START_SERVER'] = 1;
}

if (! isset($_ENV['AURA_PROJECT_SERVER_HOST'])) {
    $_ENV['AURA_PROJECT_SERVER_HOST'] = 'localhost:8080';
}
