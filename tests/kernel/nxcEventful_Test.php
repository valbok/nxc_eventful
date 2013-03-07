<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2012 NXC AS
 * @license GNU GPL v2
 * @package nxc_eventful
 */

class nxcEventful_Test extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $this->assertInstanceOf( 'nxcEventful', nxcEventful::get() );
    }

    public function testLogin()
    {
        $c = nxcEventful::get();
        $userKey = $c->login();
        $this->assertTrue( is_string( $userKey ) );
    }

    public function testWrongLogin()
    {
        $c = nxcEventful::get();
        try
        {
            $userKey = $c->login( '121-wrong', 'wrong' );
            $this->fail( 'No exception' );
        }
        catch ( Exception $e )
        {
        }
    }

    public function testSearchEvents()
    {
        $c = nxcEventful::get();
        $r = $c->call( 'events/search', array( 'date' => '2016120100-2016120200' ) );
        $this->assertTrue( isset( $r['events']['event'] ) );
        $this->assertTrue( is_array( $r['events']['event'] ) );

        foreach ( $r['events']['event'] as $e )
        {
            $d = strtotime( $e['start_time'] );
            $this->assertTrue( $d > 0 );
        }
    }

}
?>
