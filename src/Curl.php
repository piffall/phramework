<?php
/**
 * This file is part of phramework.

 * Foobar is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Foobar is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with phramework.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package PHK
 * @filesource src/Curl.php
 * @author Cristòfol Torrens <piffall@gmail.com>
 * @version 1
 */

namespace PHK;
class Curl
{

    const HTTP_PROTOCOL = 'http';
    const HTTPS_PROTOCOL = 'https';

    const HTTP_PORT = 80;
    const HTTPS_PORT = 443;

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';

    const AUTO_REFERER = 'AUTO_REFERER';

    protected $Curl = null;

    protected $Protocol = self::HTTP_PROTOCOL;
    protected $Url = '';
    protected $Port = self::HTTP_PORT;
    protected $Method = self::METHOD_GET;
    protected $UserAgent = '';
    protected $Timeout = 5;
    protected $ConnectTimeout = 5;
    protected $FollowLocation = true;
    protected $DataFields = array();    
    protected $Referer = self::AUTO_REFERER;

    protected $Username = null;
    protected $Password = null;

    protected $Info = array();
    protected $LastReturn = null;
    protected $LastHeaders = null;

    protected $LastCookies = array();

    protected $ErrorNumber = 0;
    protected $ErrorString = 0;

    protected $Verbose = false;

    /** Setters */

    public function setProtocol($Prot)
    {
        $this->Protocol = $Prot;
    }

    public function setUrl($Url)
    {
        $this->Url = $Url;
    }

    public function setPort($Port)
    {
        $this->Port = (int)$Port;
    }

    public function setMethod($Meth)
    {
        $this->Method = $Meth;
    }

    public function setUserAgent($UsAg)
    {
        $this->UserAgent = $UsAg;
    }

    public function setTimeout($Time)
    {
        $this->Timeout = (int)$Time;
    }

    public function setConnectTimeout($CoTi)
    {
        $this->ConnectTimeout = (int)$CoTi;
    }

    public function setFollowLocation($FoLo)
    {
        $this->FollowLocation = (bool)$FoLo;
    }

    public function setDataFields($Data,$Raw=false)
    {
        if(!$Raw) {
            $this->DataFields = self::buildQuery($Data);
        } else {
            $this->DataFields = $Data;
        }
    }

    public function setReferer($Refe)
    {
        $this->Referer = $Refe;
    }

    public function setUsername($User)
    {
        $this->Username = $User;
    }

    public function setPassword($Pass)
    {
        $this->Password = $Pass;
    }

    public function setInfo($Info)
    {
        $this->Info = $Info;
    }

    public function setLastReturn($LaRe)
    {
        $this->LastReturn = $LaRe;
    }

    public function setLastHeaders($LaHe)
    {
        $this->LastHeaders = $LaHe;
    }

    public function setLastCookies($LaCo)
    {
        $this->LastCookies = $LaCo;
    }

    public function setErrorNumber($ErNo)
    {
        $this->ErrorNumber = $ErNo;
    }

    public function setErrorString($ErSt)
    {
        $this->ErrorString = $ErSt;
    }

    public function setVerbose($Verb)
    {
        $this->Verbose = (bool)$Verb;
    }

    /** End of setters */

    /** Getters */

    public function getProtocol()
    {
        return $this->Protocol;
    }

    public function getUrl()
    {
        return $this->Url;
    }

    public function getPort()
    {
        return $this->Port;
    }

    public function getMethod()
    {
        return $this->Method;
    }

    public function getUserAgent()
    {
        return $this->UserAgent;
    }

    public function getTimeout()
    {
        return $this->Timeout;
    }

    public function getConnectTimeout()
    {
        return $this->ConnectTimeout;
    }

    public function getFollowLocation()
    {
        return $this->FollowLocation;
    }

    public function getDataFields()
    {
        return $this->DataFields;
    }

    public function getReferer()
    {
        return $this->Referer;
    }

    public function getUsername()
    {
        return $this->Username;
    }

    public function getPassword()
    {
        return $this->Password;
    }

    public function getInfo()
    {
        return $this->Info;
    }

    public function getLastReturn()
    {
        return $this->LastReturn;
    }

    public function getLastHeaders()
    {
        return $this->LastHeaders;
    }

    public function getLastCookies($Stri=0)
    {
        return $Stri ? implode('; ',$this->LastCookies) : $this->LastCookies;
    }

    public function getErrorNumber()
    {
        return $this->ErrorNumber;
    }

    public function getErrorString()
    {
        return $this->ErrorString;
    }

    public function getVerbose()
    {
        return $this->Verbose;
    }

    /** End of getters */

    /**
     * Constructor
     * Initializes cURL handler
     */
    public function __construct()
    {
        $this->Curl = curl_init();
    }

    /**
     * HTTP(S) GET Method
     * Performs a GET against URL
     */
    public function get()
    {
        $this->setMethod(self::METHOD_GET);
        $this->curlSetOptions();
        $this->exec();
    }

    /**
     * POST Method
     * Performs a POST against URL
     */
    public function post()
    {
        $this->setMethod(self::METHOD_POST);
        $this->curlSetOptions();
        $this->exec();
    }

    /**
     * Set cURL Options
     * Setting up cURL session handler using current settings
     */
    public function curlSetOptions()
    {
        curl_setopt($this->Curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->Curl,CURLOPT_HEADER,true);
        curl_setopt($this->Curl,CURLOPT_URL,$this->getUrl());    
        curl_setopt($this->Curl,CURLOPT_USERAGENT,$this->getUserAgent());
        if(!is_null($this->getUsername())||!is_null($this->getPassword())){
            curl_setopt($this->Curl,CURLOPT_USERPWD,"{$this->getUsername()}:{$this->getPassword()}");    
        }
        if($this->Referer===self::AUTO_REFERER) {
            curl_setopt($this->Curl,CURLOPT_AUTOREFERER,true);  
        } else {
            curl_setopt($this->Curl,CURLOPT_REFERER,$this->getReferer());    
        }
        curl_setopt($this->Curl,CURLOPT_FOLLOWLOCATION,$this->getFollowLocation());
        switch($this->Method) {
            case self::METHOD_GET:
                curl_setopt($this->Curl,CURLOPT_PUT,false);
                curl_setopt($this->Curl,CURLOPT_POST,false);
                curl_setopt($this->Curl,CURLOPT_HTTPGET,true);
                if(is_string($this->getDataFields())){
                    curl_setopt($this->Curl,CURLOPT_URL,$this->getUrl().'?'.$this->getDataFields());    
                }
                break;
            case self::METHOD_POST:
                curl_setopt($this->Curl,CURLOPT_PUT,false);
                curl_setopt($this->Curl,CURLOPT_HTTPGET,false);
                curl_setopt($this->Curl,CURLOPT_POST,true);
                curl_setopt($this->Curl,CURLOPT_POSTFIELDS,$this->getDataFields());
                break;
            case self::METHOD_PUT:
                curl_setopt($this->Curl,CURLOPT_POST,false);
                curl_setopt($this->Curl,CURLOPT_HTTPGET,false);
                curl_setopt($this->Curl,CURLOPT_PUT,true);
                break;
        }
        curl_setopt($this->Curl,CURLOPT_VERBOSE,(bool)$this->getVerbose());
        curl_setopt($this->Curl,CURLOPT_TIMEOUT,$this->getTimeout());
        curl_setopt($this->Curl,CURLOPT_CONNECTTIMEOUT,$this->getConnectTimeout());

    }

    /**
     * Perform session
     */
    public function exec()
    {
        $response = curl_exec($this->Curl);
        
        $this->Info = curl_getinfo($this->Curl);

        $this->LastReturn = substr($response,$this->Info['header_size']);
        $this->LastHeaders = substr($response,0,$this->Info['header_size']);
        $this->ErrorNumber = curl_errno($this->Curl);
        $this->ErrorString = curl_error($this->Curl);

        $this->parseCookies();
    }

    /**
     * Clear data fields.
     */
    public function clearDataFields()
    {
        $this->setDataFields(array());
    }

    /**
     * Parse "Set-Cookies:" from response Header
     */
    public function parseCookies()
    {
        $cookies = array();
        $matches = array();
        if(preg_match_all('/Set\-Cookie\:\s*([^\;]*);/',$this->getLastHeaders(),$matches)) {
            foreach($matches[1] as $match) {
              $cookies = array_merge($cookies,explode('; ',$match));
            }
            $this->setLastCookies($cookies);
        }
    }

    /**
     * Set all Cookies found in last return result
     */
    public function setFromLastCookies()
    {
        curl_setopt($this->Curl,CURLOPT_COOKIE,$this->getLastCookies(1)); 
    }

    /**
     * Set custom cookies
     * @param String $Cook
     */
    public function setCustomCookies($Cook)
    {
        curl_setopt($this->Curl,CURLOPT_COOKIE,$Cook); 
    }

    /**
     * Set custom Headers
     * @param Array $Head
     */
    public function setCustomHeaders($Head)
    {
        curl_setopt($this->Curl,CURLOPT_HTTPHEADER,$Head); 
    }

    /**
     * Find cookie by key and return value
     * @param String key
     * @return String value
     */
    public function getCookieValue($Key)
    {
        foreach($this->getLastCookies() as $cookie){
            $kv = explode('=',$cookie,2);
            if(count($kv)==2) {
                return $kv[1];
            }
            return '';
        }
    }

    /**
     * Reset all options
     */
    public function reset()
    {
        curl_reset($this->Curl);
    }

    /**
     * Encode string according to RFC3986
     * @param $String String
     * @return String
     */
    public function escape($String)
    {
        return curl_escape($this->Curl,$String);
    }

    /**
     * Close
     * Closes cURL handler
     */
    public function close()
    {
        curl_close($this->Curl);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Build query string
     * @param Mixed $data
     * @return Mixed
     */
    public static function buildQuery($data=array())
    {
        if(is_array($data)){
            return http_build_query($data);
        }
        return $data;
    }

}

?>
