<?php
/**
 * @author vd
 * @copyright Copyright (C) 2012 NXC AS.
 * @license GNU GPL v2
 * @package nxc_eventful
 */

/**
 * Base object to handle HTTP requests
 */
class nxcEventful_HTTP
{
    /**
     * API root
     *
     * @var (string)
     */
    protected $Server = false;

    /**
     * HTTP AUTH Username
     *
     * @var (string)
     */
    protected $Username = false;

    /**
     * HTTP AUTH password
     *
     * @var (string)
     */
    protected $Password = false;

    /**
     * @reimp
     */
    public function __construct( $server, $username = false, $password = false )
    {
        $this->Server = $server;
        $this->Username = $username;
        $this->Password = $password;
    }

    /**
     * Creates object
     *
     * @return (__CLASS__)
     */
    public static function get( $server, $username = false, $password = false )
    {
        return new self( $server, $username, $password );
    }

    /**
     * Creates object by class
     *
     * @return ($class)
     */
    protected static function instance( $class, $server, $username = false, $password = false )
    {
        return new $class( $server, $username, $password );
    }

    /**
     * @return (nxcEvetful_HTTP_Response)
     * @exception Exception
     */
    protected function invoke( $url, $method = 'GET', array $data = array(), $headers = array() )
    {
        $url = $this->Server . str_replace( '//', '/', $url );

        if ( $method == 'GET' and $data )
        {
            $url .= '?' . http_build_query( $data );
        }

        // Prepare curl session
        $session = curl_init( $url );
        curl_setopt( $session, CURLOPT_VERBOSE, 0 );

        // Add additonal headers
        curl_setopt( $session, CURLOPT_HTTPHEADER, $headers );

        // Don't return HTTP headers. Do return the contents of the call
        curl_setopt( $session, CURLOPT_HEADER, false );
        curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );

        $user = $this->Username;
        $password = $this->Password;

        if ( $user and !empty( $user ) )
        {
            curl_setopt( $session, CURLOPT_USERPWD, "$user:$password" );
        }

        curl_setopt( $session, CURLOPT_CUSTOMREQUEST, $method );

        if ( in_array( $method, array( 'POST', 'PUT' ) ) )
        {
            curl_setopt( $session, CURLOPT_POSTFIELDS, $data );
        }

        // Make the call
        $result = curl_exec( $session );
        $error = $result === false ? curl_error( $session ) : '';

        // Get return http status code
        $code = curl_getinfo( $session, CURLINFO_HTTP_CODE );

        // Close HTTP session
        curl_close( $session );

        return nxcEventful_HTTP_Response::get( $code )->setData( $result )->setErrorMessage( $error )->handle();
    }

    /**
     * Calls the service
     *
     * @return (string)
     * @exception Exception
     */
    public function call( $url, array $data = array() )
    {
        return $this->invoke( $url, 'GET', $data );
    }
}
?>
