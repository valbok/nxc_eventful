<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2012 NXC AS
 * @license GNU GPL v2
 * @package nxc_eventful
 */

class nxcEventful_HTTP_Response_Test extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $this->assertInstanceOf( 'nxcEventful_HTTP_Response', nxcEventful_HTTP_Response::get( '100' ) );
    }

    public function testSet()
    {
        $c = nxcEventful_HTTP_Response::get( 200 )->setData( 'result' )->setErrorMessage( 'error' );
        $this->assertEquals( 200, $c->getHTTPCode() );
        $this->assertEquals( 'result', $c->getData() );
        $this->assertEquals( 'error', $c->getErrorMessage() );
    }


    public function testHandle200()
    {
        $c = nxcEventful_HTTP_Response::get( 200 )->setData( 'result' );
        $this->assertEquals( 'result', $c->handle() );
    }

    public function testHandleError()
    {
        try
        {
            $c = nxcEventful_HTTP_Response::get( 200 )->setData( 'result' )->setErrorMessage( 'error' );
            $c->handle();
            $this->fail( 'No exception' );
        }
        catch ( Exception $e )
        {
        }
    }

    public function testHandle404()
    {
        try
        {
            $c = nxcEventful_HTTP_Response::get( 404 )->setData( 'result' );
            $c->handle();
            $this->fail( 'No exception' );
        }
        catch ( Exception $e )
        {
        }
    }

    public function testHandle500()
    {
        try
        {
            $c = nxcEventful_HTTP_Response::get( 500 )->setData( 'result' );
            $c->handle();
            $this->fail( 'No exception' );
        }
        catch ( Exception $e )
        {
        }
    }

}
?>
