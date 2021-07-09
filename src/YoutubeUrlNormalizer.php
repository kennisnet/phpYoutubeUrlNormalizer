<?php
namespace Kennisnet\YoutubeUrlNormalizer;

use \UnexpectedValueException;

/**
* PHP library for normalizing Youtube Urls. 
*
* @version 0.0.2
* @author Wim Muskee <wimmuskee@gmail.com>
*
* Copyright 2020 Stichting Kennisnet
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

class YoutubeUrlNormalizer {
    # is this a valid youtube url
    public $isYoutube = False;
    # resource type is one of [video, playlist, user, channel]
    public $type = "";
    # the youtube resource identifier
    public $identifier = "";
    # resulting normalized url without input parameters
    public $normalized = "";
    # resulting normalized url with input parameters
    public $normalized_parameters = "";

    private $parsed_url = array();
    private $parameters = array();

    public function __construct( $url ) {
        if ( empty( trim($url) ) ) {
            throw new UnexpectedValueException( "URL should not be empty" );
        }

        $this->parsed_url = parse_url( trim($url) );
        $this->checkHost();

        if ( $this->isYoutube ) {
            $this->checkPath();
            $this->setNormalized();
        }
    }

    /**
     * Use parsed_url host information to parse some variables.
     */
    public function checkHost() {
        if (!isset($this->parsed_url["host"])) {
            $this->isYoutube = false;
            return;
        }

        if ( $this->parsed_url["host"] == "youtu.be") {
            $this->isYoutube = True;
        }
        elseif ( $this->parsed_url["host"] == "youtube.com") {
            $this->isYoutube = True;
        }
        elseif ( substr( strrev( $this->parsed_url["host"] ), 0, 12 ) == strrev(".youtube.com") ) {
            $this->isYoutube = True;
        }
    }

    /**
     * Use parsed_url path information to determine type and parse 
     * the rest accordingly.
     */
    public function checkPath() {
        $exploded_path = explode("/", isset($this->parsed_url["path"]) ? $this->parsed_url["path"] : '');

        if (count($exploded_path) <= 1) {
            $this->isYoutube = false;
            return;
        }

        switch ( $exploded_path[1] ) {
            case "watch":
            $this->type = "video";
            break;

            case "channel":
            $this->type = "channel";
            $this->setIdentifier($exploded_path[2]);
            break;

            case "playlist":
            $this->type = "playlist";
            break;

            case "user":
            $this->type = "user";
            $this->setIdentifier($exploded_path[2]);
            break;

            case "v":
            case "embed":
            $this->type = "video";
            $this->setIdentifier($exploded_path[2]);
            break;

            default:
            if ( $this->parsed_url["host"] == "youtu.be") {
                $this->type = "video";
                $this->setIdentifier($exploded_path[1]);
            }
            else {
                $this->isYoutube = False;
                return;
            }
        }
        $this->checkQuery();
    }

    /**
     * Depending on type, set normalized url variants.
     */
    public function setNormalized() {
        if ( empty($this->type) || !$this->isYoutube ) {
            return;
        }

        if ( $this->type != "playlist" && empty($this->identifier) ) {
            $this->isYoutube = False;
            return;
        }

        switch ( $this->type ) {
            case "video":
            $this->normalized = "https://youtu.be/".$this->identifier;
            $this->normalized_parameters = "https://youtu.be/".$this->identifier."?".$this->getParameterString();
            break;

            case "playlist":
            $this->normalized = "https://www.youtube.com/".$this->type."?list=".$this->parameters["list"];
            $this->normalized_parameters = "https://www.youtube.com/".$this->type."?".$this->getParameterString();
            break;

            default:
            $this->normalized = "https://www.youtube.com/".$this->type."/".$this->identifier;
        }
    }

    /**
     * Parse query parameters.
     */
    private function checkQuery() {
        if ( !array_key_exists("query", $this->parsed_url) || !$this->isYoutube ) {
            return;
        }

        $parameters = explode("&", $this->parsed_url["query"]);
        foreach( $parameters as $parameter ) {
            $parts = explode("=", $parameter );
            $key = $parts[0];
            $value = $parts[1];

            switch ( $key ) {
                case "v":
                $this->setIdentifier($value);
                break;
                case "feature":
                case "index":
                case "list":
                case "time_continue":
                $this->parameters[$key] = $value;
                break;
            }
        }
    }

    /**
     * Remake parsed parameters into a normalized url parameter string.
     */
    private function getParameterString() {
        # make sure parameters are always in same order
        ksort($this->parameters);

        $parameters = array();
        foreach( $this->parameters as $key => $value ) {
            $parameters[] = $key."=".$value;
        }

        return implode("&", $parameters);
    }

    /**
     * Sets identifier after some validation.
     * Only basic validation for now.
     * 
     * @param string $identifier Identifier to set.
     * @see https://webapps.stackexchange.com/questions/54443/format-for-id-of-youtube-video  
     */
    private function setIdentifier( $identifier ) {
        # no type conditional yet, so check minimum length 11 for videoID
        if ( strlen($identifier) < 11 ) {
            $this->isYoutube = False;
        }

        $this->identifier = $identifier;
    }
}
?>
