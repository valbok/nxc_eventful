<?php
/**
 * @author vd
 * @copyright Copyright (C) 2012 NXC AS.
 * @license GNU GPL v2
 * @package nxc_eventful
 */

/**
 * Main object to access eventful
 */
class nxcEventful
{
    /**
     * Application key, provided by vendor
     *
     * @var (string)
     */
    protected $AppKey = false;

    /**
     * Root API server
     *
     * @var (string)
     */
    protected $Server = false;

    /**
     * Cached username
     *
     * @var (string)
     */
    protected $User = false;

    /**
     * Cached user key
     *
     * @var (string)
     */
    protected $UserKey = false;

    /**
     * @reimp
     */
    public function __construct( $server, $appKey )
    {
        if ( !$server )
        {
            throw new Exception( 'Server is required' );
        }

        if ( !$appKey )
        {
            throw new Exception( 'AppKey is required' );
        }

        $this->Server = $server;
        $this->AppKey = $appKey;
    }

    /**
     * @return (__CLASS__)
     */
    public static function get()
    {
        $ini = eZINI::instance( 'eventful.ini' );
        $server = $ini->variable( 'GeneralSettings', 'RootAPI' );
        $key = $ini->variable( 'GeneralSettings', 'AppKey' );

        return new self( $server, $key );
    }

    /**
     * Logs in and caches needed info
     *
     * @return (string)
     */
    public function login( $user = false, $password = false )
    {
        if ( !$user )
        {
            $ini = eZINI::instance( 'eventful.ini' );
            $user = $ini->variable( 'GeneralSettings', 'User' );
            $password = $ini->variable( 'GeneralSettings', 'Password' );
        }

        $this->User = $user;

        /**
         * Call login to receive a nonce.
         * (The nonce is stored in an error structure.)
         */
        $data = $this->call( 'users/login' );
        $nonce = $data['nonce'];

        // Generate the digested password response.
        // Seems server will try to do the same sequence of md5 to check the password
        $response = md5( $nonce . ":" . md5( $password ) );

        // Send back the nonce and response.
        $args = array(
            'nonce'    => $nonce,
            'response' => $response,
        );

        $r = $this->call( 'users/login', $args );
        if ( !isset( $r['user_key'] ) )
        {
            // Usually means username is wrong
            throw new Exception( 'No user_key in response' );
        }

        // Store the provided user_key.
        $this->UserKey = (string) $r['user_key'];

        return $this->UserKey;
    }

    /**
     * @return (mixed)
     */
    public function call( $method, array $data = array(), $type = 'json' )
    {
        if ( !is_array( $data ) )
        {
            $data = array( $data );
        }

        $result = false;
        $data['app_key'] = $this->AppKey;
        if ( $this->User )
        {
            $data['user'] = $this->User;
        }

        if ( $this->UserKey )
        {
            $data['user_key'] = $this->UserKey;
        }

        switch ( $type )
        {
            case 'json':
            default:
            {
                $result = nxcEventful_HTTP_JSON::get( $this->Server )->call( $method, $data );
            } break;
        }

        return $result;
    }

}
?>