<?php
/**
 * @author vd
 * @copyright Copyright (C) 2012 NXC AS.
 * @license GNU GPL v2
 * @package nxc_eventful
 */

/**
 * Response object
 */
class nxcEventful_HTTP_Response
{
    /**
     * HTTP Code
     *
     * @var (string)
     */
    protected $HTTPCode = false;

    /**
     * Returned data
     *
     * @var (string)
     */
    protected $Data = false;

    /**
     * @var (string)
     */
    protected $ErrorMessage = false;

    /**
     * @param (string) HTTP Code
     * @reimp
     */
    public function __construct( $code )
    {
        $this->HTTPCode = $code;
    }

    /**
     * Creates object
     *
     * @return (__CLASS__)
     */
    public static function get( $code )
    {
        return new self( $code );
    }

    /**
     * @return (string)
     * @exception Exception
     */
    public function handle()
    {
        switch ( $this->getHTTPCode() )
        {
            case '404':
            {
                $this->setErrorMessage( 'Page not found' );
            } break;

            case '500':
            {
                $this->setErrorMessage( 'Internal Server Error' );
            } break;

            case '403':
            case '401':
            {
                $this->setErrorMessage( 'Access denied' );
            } break;
        }

        $error = $this->getErrorMessage();
        if ( $error )
        {
            throw new Exception( $error, $this->getHTTPCode() );
        }

        return $this->getData();
    }

    /**
     * @return (this)
     */
    public function setData( $data )
    {
        $this->Data = $data;
        return $this;
    }

    /**
     * @return (string) Response body
     */
    public function getData()
    {
        return $this->Data;
    }

    /**
     * @return (this)
     */
    public function setErrorMessage( $error )
    {
        $this->ErrorMessage = $error;
        return $this;
    }

    /**
     * @return (string)
     */
    public function getErrorMessage()
    {
        return $this->ErrorMessage;
    }

    /**
     * @return (string)
     */
    public function getHTTPCode()
    {
        return $this->HTTPCode;
    }

}
?>