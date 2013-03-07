<?php
/**
 * @author vd
 * @copyright Copyright (C) 2012 NXC AS.
 * @license GNU GPL v2
 * @package nxc_eventful
 */

/**
 * Event object
 */
class nxcEventful_Event extends nxcEventful_Object
{
    /**
     * @reimp
     */
    public static function get( $array )
    {
        return parent::instance( __CLASS__, $array );
    }

    /**
     * @reimp
     */
    public static function parse( $response )
    {
        return self::get( $response );
    }

    /**
     * @reimp
     *
     * @return (__CLASS__)
     */
    public static function fetch( $id )
    {
        $response = nxcEventful::get()->call( 'events/get', array( 'id' => $id ) );

        return self::parse( $response );
    }

    /**
     * @reimp
     *
     * @return (array( __CLASS__ ))
     */
    public static function parseList( $response )
    {
        $result = array();
        if ( !is_array( $response ) )
        {
            $response = array( $response );
        }

        foreach ( $response as $item )
        {
            $result[] = self::parse( $item );
        }

        return $result;
    }

    /**
     * Fetches list
     *
     * @return (nxcEventful_Response)
     * @url http://api.eventful.com/docs/events/search
     */
    public static function fetchList( array $params = array() )
    {
        $response = nxcEventful::get()->call( 'events/search', $params );
        $list = isset( $response['events']['event'] ) ? $response['events']['event'] : array();
        unset( $response['events'] );

        return self::getResponse( self::parseList( $list ), $response );
    }

    /**
     * Fetches by dates
     *
     * @param (int)
     * @param (int)
     * @return (array)
     */
    public static function fetchListByDates( $startDate, $endDate, array $params = array() )
    {
        if ( !is_numeric( $startDate ) )
        {
            $startDate = strtotime( $startDate );
        }

        if ( !is_numeric( $endDate ) )
        {
            $endDate = strtotime( $endDate );
        }

        if ( $startDate and $endDate )
        {
            $params['date'] = date( 'Ymd00', $startDate ) . '-' . date( 'Ymd00', $endDate );
        }

        return self::fetchList( $params );
    }

}
?>
