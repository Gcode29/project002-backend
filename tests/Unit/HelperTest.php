<?php

it('can trim whitespaces from a string', function () {
    $actual = " a  b c  d
    e f  ";

    $expected = 'a b c d e f';

    expect(trim_whitespaces($actual))->toBe($expected);
});