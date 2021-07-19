<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function test_文字列が正しくリンクに変換される()
    {
        $origin = '[[test]]です';
        $converted = "<a href=\"/questions?answer=test&question=1&like=1\">test</a>です";;
        $this->assertEquals(convertLink($origin), $converted);
    }
}
