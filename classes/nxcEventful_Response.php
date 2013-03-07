<?php
/**
 * @author vd
 * @date 31 oct 2012
 * @copyright Copyright (C) 2012 NXC AS.
 * @license GNU GPL v2
 * @package nxc_eventful
 */

/**
 * Response object, wrapper to get a result
 */
class nxcEventful_Response
{
    /**
     * Parsed response
     *
     * @var (mixed) Can contain array or single nxcEventful_Object
     */
    protected $Content = false;

    /**
     * Original response header from API server.
     * Means contains only tech data:
     *
      ["last_item"]=>
      NULL
      ["total_items"]=>
      string(6) "107928"
      ["first_item"]=>
      NULL
      ["page_number"]=>
      string(1) "1"
      ["page_size"]=>
      string(1) "5"
      ["page_items"]=>
      NULL
      ["search_time"]=>
      string(5) "0.124"
      ["page_count"]=>
      string(5) "21586"
     *
     * @var (array)
     */
    protected $Header = false;

    /**
     * @param (mixed)
     * @param (array)
     */
    public function __construct( $content = false, $header = false )
    {
        $this->Content = $content;
        $this->Header = $header;
    }

    /**
     * @reimp
     * @note Is needed for using in tpls
     */
    public function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    /**
     * Returns field or custom values to be used in tpl
     *
     * @return (mixed)
     */
    public function attribute( $name )
    {
        switch ( $name )
        {
            case 'content':
            {
                $result = $this->Content;
            } break;

            case 'header':
            {
                $result = $this->Header;
            } break;

            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
            } break;
        }

        return $result;
    }

    /**
     * Returns all attributes that exist for this object
     *
     * @return (array)
     */
    public function attributes()
    {
        return array( 'content', 'header' );
    }
}
?>
