<?php
##########################################################################
#                                                                        #
# Cristòfol Torrens Morell <piffall@gmail.com>                           #
#                                                                        #
# Released under the GNU General Public License WITHOUT ANY WARRANTY.    #
# See LICENSE file for more information                                  #
#                                                                        #
##########################################################################

class PHKCurl
{

    const HTTP_PROTOCOL = 'http';
    const HTTPS_PROTOCOL = 'https';

    const HTTP_PORT = 80;
    const HTTPS_PORT = 443;

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    const AUTO_REFERER = 'AUTO_REFERER';

    protected $Curl = null;

    protected $Protocol = self::HTTP_PORT;
    protected $Url = '';
    protected $Port = self::HTTP_PROTOCOL;
    protected $UserAgent = '';
    protected $Timeout = 5;
    protected $ConnectTimeout = 5;
    protected $FollowLocation = true;
    protected $DataFields = array();    
    protected $Referer = self::AUTO_REFERER;

    protected $Info = null;
    protected $LastReturn = null;
    protected $LastRawHeaders = null;

    protected $ErrorNumber = 0;
    protected $ErrorString = 0;

    protected $Verbose = false;

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

    public function setUserAgent($UsAg)
    {
        $this->UserAgent = $UsAg;
    }

    public function setTimeout($Time)
    {
        $this->Timeout = $Time;
    }

    public function setFollowLocation($FoLo)
    {
        $this->FollowLocation = $FoLo;
    }

    public function setDataFields($Data,$Raw=false)
    {
        if(!$Raw) {
            $this->Data = self::buildQuery($Data);
        } else {
            $this->Data = $Data;
        }
    }

    public function setReferer($Refe)
    {
        $this->Referer = $Refe;
    }

    public function setVerbose($Verb)
    {
        $this->Verbose = (bool)$Verb;
    }

    /**
     * Constructor
     * Initializes cURL handler
     */
    public function __construct()
    {
        $this->Curl = curl_init();
    }

    /**
     * GET Method
     */
    public function get()
    {
        $this->setMethod(self::METHOD_GET);
        $this->curlSetOptions();
        $this->exec();
    }

    /**
     * POST Method
     */
    public function post()
    {
        $this->setMethod(self::METHOD_POST);
        $this->curlSetOptions();
        $this->exec();
    }

    public function curlSetOptions()
    {
        curl_setopt(CURLOPT_RETURNTRANSFER,true);
        curl_setopt(CURLOPT_URL,$this->Url);    
        curl_setopt(CURLOPT_USERAGENT,$this->UserAgent);    
        if($this->Referer===seflf::AUTO_REFERER) {
            curl_setopt(CURLOPT_AUTOREFERER,true);  
        } else {
            curl_setopt(CURLOPT_REFERER,$this->Referer);    
        }
        curl_setopt(CURLOPT_FOLLOWLOCATION,(bool)$this->FollowLocation);
        switch($this->Method) {
            case self::METHOD_GET:
                curl_setopt(CURLOPT_PUT,false);
                curl_setopt(CURLOPT_POST,false);
                curl_setopt(CURLOPT_HTTPGET,true);
                if($this->Data){
                    curl_setopt(CURLOPT_URL,$this->Url.'?'.$this->Data);    
                }
                break;
            case self::METHOD_POST:
                curl_setopt(CURLOPT_PUT,false);
                curl_setopt(CURLOPT_HTTPGET,false);
                curl_setopt(CURLOPT_POST,true);
                curl_setopt(CURLOPT_POSTFIELDS,$this->Data);
                break;
            case self::METHOD_PUT:
                curl_setopt(CURLOPT_POST,false);
                curl_setopt(CURLOPT_HTTPGET,false);
                curl_setopt(CURLOPT_PUT,true);
                break;
        }
        curl_setopt(CURLOPT_VERBOSE,(bool)$this->Verbose);
        curl_setopt(CURLOPT_TIMEOUT,$this->Timeout);
        curl_setopt(CURLOPT_CONNECTTIMEOUT,$this->ConnectTimeout);

    }

    /**
     * Perform session
     */
    public function exec()
    {
        $this->LastReturn = curl_exec($this->Curl);
        $this->ErrorNumber = curl_errno($this->Curl);
        $this->ErrorString = curl_error($this->Curl);

        $this->Info = curl_getinfo($this->Curl);
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
