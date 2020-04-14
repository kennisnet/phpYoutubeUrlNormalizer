[![Build Status](https://travis-ci.org/kennisnet/phpYoutubeUrlNormalizer.svg?branch=master)](https://travis-ci.org/kennisnet/phpYoutubeUrlNormalizer)

# phpYoutubeUrlNormalizer
PHP library for normalizing Youtube urls.

## Usage
The library can handle all types of urls, and either verifies it is not a Youtube url, or provides normalized variants.
Two types of normalized urls are provided, one with provided valid arguments, and one without.

Just call the library with a URL you'd like to check or normalize.

```php
<?php
$yt = new YoutubeUrlNormalizer("http://www.youtube.com/watch?v=7cZFk68S3js&time_continue=12&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&index=4");

var_dump($yt->isYoutube);
# bool(true)

echo $yt->normalized;
# https://youtu.be/7cZFk68S3js

echo $yt->normalized_parameters;
# https://youtu.be/7cZFk68S3js?index=4&list=PLi_srCikhtghrNa6Ti1d4aSyPQzQ3JI63&time_continue=12
```
