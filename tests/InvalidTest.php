<?php
namespace Test\Kennisnet\YoutubeUrlNormalizer;

use Kennisnet\YoutubeUrlNormalizer\YoutubeUrlNormalizer;
use PHPUnit\Framework\TestCase;

class InvalidTest extends TestCase {

    public function testNonYoutubeHost() {
        $url = "https://www.example.org/watch?v=7cZFk68S3js";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertFalse($yt->isYoutube);
        $this->assertEmpty($yt->normalized);
    }

    public function testPrefixedYoutubeHost() {
        $url = "http://fakeyoutube.com/watch?v=7cZFk68S3js";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertFalse($yt->isYoutube);
        $this->assertEmpty($yt->normalized);
    }

    public function testNonYoutubePath() {
        $url = "https://www.youtube.com/testinvalid";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertFalse($yt->isYoutube);
        $this->assertEmpty($yt->normalized);
    }

    public function testEmptyVideoId() {
        $url = "http://youtube.com/watch?v=";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertFalse($yt->isYoutube);
        $this->assertEmpty($yt->normalized);
    }

    public function testShortVideoId() {
        $url = "http://youtu.be/1234";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertFalse($yt->isYoutube);
        $this->assertEmpty($yt->normalized);
    }

    public function testMissingRequiredParameter() {
        $url = "http://nl.youtube.com/watch?w=7cZFk68S3js";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertFalse($yt->isYoutube);
        $this->assertEmpty($yt->normalized);
    }
}
