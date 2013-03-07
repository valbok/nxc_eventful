<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2012 NXC AS
 * @license GNU GPL v2
 * @package nxc_eventful
 */

class nxcEventful_HTTP_Test extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $this->assertInstanceOf( 'nxcEventful_HTTP', nxcEventful_HTTP::get( 'test' ) );
    }

    public function testCorrectServer()
    {
        $c = nxcEventful_HTTP::get( 'google.com' )->call( '/' );
        $this->assertTrue( is_string( $c ) );

        $c = nxcEventful_HTTP::get( 'www.google.com' )->call( '/' );
        $this->assertTrue( is_string( $c ) );

        $c = nxcEventful_HTTP::get( 'http://www.google.com' )->call( '/' );
        $this->assertTrue( is_string( $c ) );

        $c = nxcEventful_HTTP::get( 'https://www.google.com' )->call( '/' );
        $this->assertTrue( is_string( $c ) );

        $c = nxcEventful_HTTP::get( 'google.com' )->call( '' );
        $this->assertTrue( is_string( $c ) );
    }

    public function testWrongServer()
    {
        try
        {
            $c = nxcEventful_HTTP::get( 'does-not-exist.sure' )->call( '/' );
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
            $c = nxcEventful_HTTP::get( 'ya.ru' )->call( '/wrong.html?323123' );
            $this->fail( 'No exception' );
        }
        catch ( Exception $e )
        {
        }
    }

}
?>
