<?php
/**
 * Handler for operators
 *
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2012 NXC AS
 * @license GNU GPL v2
 * @package nxc_eventful
 */

class nxcEventfulAutoload
{
    /**
     * @reimp
     */
    function __construct()
    {
    }

    /**
     * @reimp
     */
    public function operatorList()
    {
        return array( 'eventful' );
    }

    /**
     * @reimp
     */
    function namedParameterPerOperator()
    {
        return true;
    }

    /**
     * @reimp
     */
    function namedParameterList()
    {
        return array( 'eventful' => array(
                        'method' =>
                            array( 'type' => 'string',
                                   'required' => true,
                                   'default' => '' ),
                        'params' =>
                            array( 'type' => 'array',
                                   'required' => false,
                                   'default' => array() ),
                        'type' =>
                            array( 'type' => 'string',
                                   'required' => false,
                                   'default' => false ),

                        )
                    );
    }

    /**
     * @reimp
     */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'eventful':
            {
                try
                {
                    $operatorValue = false;
                    $method = $namedParameters['method'];
                    $params = $namedParameters['params'];
                    $type = $namedParameters['type'];

                    $operatorValue = nxcEventful::get()->call( $method, $params, $type );
                }
                catch( Exception $e )
                {
                    eZDebug::writeError( $e->getMessage(), __METHOD__ );
                    eZDebug::writeError( $e->getTraceAsString(), __METHOD__ );
                }
            } break;
        }
    }

}

?>
