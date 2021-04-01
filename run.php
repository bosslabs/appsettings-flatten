#!/usr/bin/env php

<?php
    require_once ('vendor/autoload.php');

    use App\Command\ConvertCommand;
    use Symfony\Component\Console\Application;

    $appliction = new Application('AppSettingsFlattenerCLI', '0.1a');
    $appliction->add(new ConvertCommand());
    $appliction->run();