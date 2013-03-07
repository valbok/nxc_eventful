<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2012 NXC AS
 * @license GNU GPL v2
 * @package nxc_eventful
 */

class nxcEventful_Event_Test extends PHPUnit_Framework_TestCase
{
    public function testFetchList()
    {
        $date = date( 'Ymd00', strtotime( '+1 week' ) ) . '-' . date( 'Ymd00', strtotime( '+2 week' ) );
        $p = array( 'date' => $date, 'page_size' => 5 );
        $list = nxcEventful_Event::fetchList( $p );

        $this->assertInstanceOf( 'nxcEventful_Response', $list );
        $list = $list->attribute( 'content' );
        $this->assertTrue( is_array( $list ) );
        $this->assertEquals( 5, count( $list ) );
    }

    public function testFetchListByDates()
    {
        $list = nxcEventful_Event::fetchListByDates( strtotime( '+1 week' ), strtotime( '+2 week' ), array( 'page_size' => 5 ) );

        $this->assertInstanceOf( 'nxcEventful_Response', $list );
        $list = $list->attribute( 'content' );
        $this->assertTrue( is_array( $list ) );
        $this->assertEquals( 5, count( $list ) );
    }

    public function testFetch()
    {
        $list = nxcEventful_Event::fetchListByDates( strtotime( '+1 week' ), strtotime( '+2 week' ), array( 'page_size' => 5 ) );
        $c = $list->attribute( 'content' );
        $o = $c[0];
        $r = nxcEventful_Event::fetch( $o->attribute( 'id' ) );

        $this->assertInstanceOf( 'nxcEventful_Event', $r );
        $this->assertEquals( $o->attribute( 'id' ), $r->attribute( 'id' ) );
    }

}
?>
