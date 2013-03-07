<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2012 NXC AS
 * @license GNU GPL v2
 * @package nxc_eventful
 */

class nxcEventful_HTTP_JSON_Test extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $this->assertInstanceOf( 'nxcEventful_HTTP_JSON', nxcEventful_HTTP_JSON::get( 'test' ) );
    }

    public function testCorrectURL()
    {
        try
        {
            $c = nxcEventful_HTTP_JSON::get( 'http://api.eventful.com' )->call( '/users/login' );
            $this->fail( 'No exception' );
        }
        catch ( Exception $e )
        {
        }
    }

    public function testWrongURL()
    {
        try
        {
            $c = nxcEventful_HTTP_JSON::get( 'http://google.com' )->call( '/wrong' );
            $this->fail( 'No exception' );
        }
        catch ( Exception $e )
        {
            if ( $e->getCode() != 500 )
            {
                $this->fail( 'Wrong code :' . $e->getCode() );
            }
        }
    }


}
?>
