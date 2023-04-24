<?php

use AshleyHardy\Language\Language;

test(
    'Translator service translates a basic string and loads a file',
    function() {
        Language::loadFile(__DIR__ . "/Language/test.php");

        $formalGreeting = Language::translate('greeting.formal');
        expect($formalGreeting)->toEqual('Hello world');

        $informalGreeting = Language::translate('greeting.informal');
        expect($informalGreeting)->toEqual('Whaddup dawg');
    }
);

test(
    'Translator returns the input key when a translation cannot be found.',
    function() {
        $unknownKey = Language::translate('unknown.key');
        expect($unknownKey)->toEqual('unknown.key');
    }
);