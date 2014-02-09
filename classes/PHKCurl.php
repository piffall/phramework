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
    protected $Curl = null;

    protected $URL = '';
    protected $Port = 80;
    protected $UserAgent = '';
    protected $Timeout = 5;
    protected $DataFields = array();    

    /**
     * Constructor
     * Initializes cURL handler
     */
    public function __construct()
    {
        $this->Curl = curl_init();
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

}

?>
