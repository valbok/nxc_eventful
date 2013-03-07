<?php
/**
 * @author vd
 * @copyright Copyright (C) 2012 NXC AS.
 * @license GNU GPL v2
 * @package nxc_eventful
 */

/**
 * Base eventful object
 */
class nxcEventful_Object
{
    /**
     * @param (mixed) $list List of fields and values
     */
    public function __construct( $list = array() )
    {
        $this->copyFieldList( $list );
    }

    /**
     * Copies fields to current object from specified list
     *
     * @return (void)
     */
    protected function copyFieldList( $list = array() )
    {
        foreach ( $list as $key => $value )
        {
            $this->$key = self::get( $value );
        }
    }

    /**
     * @return (instance of \a $class)
     */
    protected static function instance( $class, $array = array() )
    {
        return new $class( $array );
    }

    /**
     * Returns proper value
     *
     * @param (mixed)
     *
     * @return (mixed)
     */
    public static function get( $list )
    {
        $result = $list;
        if ( $list instanceof stdClass )
        {
            $result = new self( $list );
        }
        elseif ( is_array( $list ) )
        {
            $result = array();
            foreach ( $list as $key => $value )
            {
                $result[$key] = self::get( $value );
            }
        }

        return $result;
    }

    /**
     * @reimp
     * @note Is needed for using in tpls
     */
    public function hasAttribute( $name )
    {
        return isset( $this->$name );
    }

    /**
     * Returns field or custom values.
     *
     * @return (mixed)
     */
    public function attribute( $name )
    {
        if ( !$this->hasAttribute( $name ) )
        {
            eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
            return false;
        }

        return $this->$name;
    }

    /**
     * Returns all attributes that exist for this object
     *
     * @return (array)
     */
    public function attributes()
    {
        $attrs = get_object_vars( $this );
        $attrs = array_keys( $attrs );

        return $attrs;
    }

    /**
     * Sets attribute value
     *
     * @return (this)
     */
    public function setAttribute( $name, $value )
    {
        $this->$name = $value;

        return $this;
    }

    /**
     * Parses response to __CLASS__
     *
     * @param (stdClass|array)
     *
     * @return (__CLASS__)
     */
    public static function parse( $response )
    {
        return self::get( $response );
    }

    /**
     * Parses response to list.
     *
     * @param (stdClass|array)
     *
     * @return (array)
     */
    public static function parseList( $response )
    {
        if ( !is_array( $response ) )
        {
            $response = array( $response );
        }

        $result = array();
        foreach ( $response as $item )
        {
            $result[] = self::parse( $item );
        }

        return $result;
    }

    /**
     * Creates response object
     *
     * @return (nxcEventful_Response)
     */
    public static function getResponse( $content = false, $header = false )
    {
        return new nxcEventful_Response( $content, $header );
    }
}
?>
