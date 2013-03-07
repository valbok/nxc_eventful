<?php
/**
 * @author vd
 * @copyright Copyright (C) 2012 NXC AS.
 * @license GNU GPL v2
 * @package nxc_eventful
 */

/**
 * JSON handler
 */
class nxcEventful_HTTP_JSON extends nxcEventful_HTTP
{
    /**
     * @reimp
     */
    public static function get( $server, $username = false, $password = false )
    {
        return self::instance( __CLASS__, $server, $username, $password );
    }

    /**
     * @reimp
     */
    public function call( $url, array $data = array() )
    {
        $result = json_decode( parent::call( '/json/' . $url, $data ), true );
        $error = false;

        if ( function_exists( 'json_last_error' ) )
        {
            switch ( json_last_error() )
            {
                case JSON_ERROR_NONE:
                {
                    $error = false;
                } break;

                case JSON_ERROR_DEPTH:
                {
                    $error = 'Maximum stack depth exceeded';
                } break;

                case JSON_ERROR_STATE_MISMATCH:
                {
                    $error = 'Underflow or the modes mismatch';
                } break;

                case JSON_ERROR_CTRL_CHAR:
                {
                    $error = 'Unexpected control character found';
                } break;

                case JSON_ERROR_SYNTAX:
                {
                    $error = 'Syntax error, malformed JSON';
                } break;

                case JSON_ERROR_UTF8:
                {
                    $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                }  break;

                default:
                {
                    $error = 'Unknown error';
                } break;
            }
        }
        elseif ( !$result )
        {
            $error = 'JSON string can not be decoded';
        }

        // __WARNING__ Hack to show the error.
        // If description and string params usually define errors, but seems sometimes it shows some info without errors
        if ( isset( $result['string'] ) and isset( $result['description'] ) and !isset( $result['nonce'] ) )
        {
            $error = $result['description'];
        }

        if ( $error )
        {
            throw new Exception( $error, 500 );
        }

        return $result;
    }
}
?>