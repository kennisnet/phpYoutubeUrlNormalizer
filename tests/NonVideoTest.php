<?php
namespace Test\Kennisnet\YoutubeUrlNormalizer;

use Kennisnet\YoutubeUrlNormalizer\YoutubeUrlNormalizer;
use PHPUnit\Framework\TestCase;

class NonVideoTest extends TestCase {

    public function testPlaylist() {
        $url = "http://nl.youtube.com/playlist?list=PLA5NP5n9YwGXERqwmaHvNh4ab8qS7fOUL&feature=plcp";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://www.youtube.com/playlist?list=PLA5NP5n9YwGXERqwmaHvNh4ab8qS7fOUL", $yt->normalized);
        $this->assertEquals("https://www.youtube.com/playlist?feature=plcp&list=PLA5NP5n9YwGXERqwmaHvNh4ab8qS7fOUL", $yt->normalized_parameters);
    }

    public function testUser() {
        $url = "http://www.youtube.com/user/KenniscentrumHandel1/#p/u/8/rGqEuduJF1Y";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://www.youtube.com/user/KenniscentrumHandel1", $yt->normalized);
        $this->assertEquals("", $yt->normalized_parameters);
    }

    public function testChannel() {
        $url = "http://nl.youtube.com/channel/UCG0IMhE_SJUx68GwdjuzGWw";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://www.youtube.com/channel/UCG0IMhE_SJUx68GwdjuzGWw", $yt->normalized);
        $this->assertEquals("", $yt->normalized_parameters);
    }
}
