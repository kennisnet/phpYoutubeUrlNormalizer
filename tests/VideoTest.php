<?php
namespace Test\Kennisnet\YoutubeUrlNormalizer;

use Kennisnet\YoutubeUrlNormalizer\YoutubeUrlNormalizer;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase {

    public function testNormal() {
        $url = "http://youtube.com/watch?v=7cZFk68S3js&time_continue=12&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&index=4";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://youtu.be/7cZFk68S3js", $yt->normalized);
        $this->assertEquals("https://youtu.be/7cZFk68S3js?index=4&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&time_continue=12", $yt->normalized_parameters);
    }

    public function testSubDomain() {
        $url = "http://nl.youtube.com/watch?v=7cZFk68S3js&time_continue=12&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&index=4";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://youtu.be/7cZFk68S3js", $yt->normalized);
        $this->assertEquals("https://youtu.be/7cZFk68S3js?index=4&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&time_continue=12", $yt->normalized_parameters);
    }

    public function testEmbed() {
        $url = "http://youtube.com/embed/7cZFk68S3js?time_continue=12&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&index=4";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://youtu.be/7cZFk68S3js", $yt->normalized);
        $this->assertEquals("https://youtu.be/7cZFk68S3js?index=4&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&time_continue=12", $yt->normalized_parameters);
    }

    public function testNormalized() {
        $url = "https://youtu.be/7cZFk68S3js?time_continue=12&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&index=4";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://youtu.be/7cZFk68S3js", $yt->normalized);
        $this->assertEquals("https://youtu.be/7cZFk68S3js?index=4&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&time_continue=12", $yt->normalized_parameters);
    }

    public function testEndSpace() {
        $url = "http://youtu.be/7cZFk68S3js ";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://youtu.be/7cZFk68S3js", $yt->normalized);
    }

    public function test() {
        $url = "http://youtu.be/7cZFk68S3js ";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://youtu.be/7cZFk68S3js", $yt->normalized);
    }

    public function testNoEndQuestionMark() {
        $url = "https://www.youtube.com/watch?v=NVhmq-pB_cs";
        $yt = new YoutubeUrlNormalizer($url);
        $this->assertTrue($yt->isYoutube);
        $this->assertEquals("https://youtu.be/NVhmq-pB_cs", $yt->normalized);
        $this->assertEquals("https://youtu.be/NVhmq-pB_cs", $yt->normalized_parameters);
    }
}
