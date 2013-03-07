NXC Eventful eZ Publish extension v1.0
---------------------------------------

It is adapter to http://api.eventful.com/

Allows to fetch eventful events and show it on your site.

EXAMPLE
-------

    $list = nxcEventful_Event::fetchListByDates( strtotime( '+1 week' ), strtotime( '+2 week' ), array( 'page_size' => 5 ) );
    $c = $list->attribute( 'content' );
    $o = $c[0];
    $r = nxcEventful_Event::fetch( $o->attribute( 'id' ) );
    var_dump( $r )
