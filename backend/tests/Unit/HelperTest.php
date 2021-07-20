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

    public function test_複数回カッコが出現しても正しくリンクに変換される()
    {
        $origin = '[[hoge]]は[[fuga]]です';
        $converted = "<a href=\"/questions?answer=hoge&question=1&like=1\">hoge</a>は<a href=\"/questions?answer=fuga&question=1&like=1\">fuga</a>です";;
        $this->assertEquals(convertLink($origin), $converted);
    }
}
