return array( 

    /*
    |--------------------------------------------------------------------------
    | oAuth Config
    |--------------------------------------------------------------------------
    */

    /**
     * Storage
     */
    'storage' => 'Session', 

    /**
     * Consumers
     */
    'consumers' => array(

        /**
         * Facebook
         */
        'Facebook' => array(
            'client_id'     => '1375932729355034',
            'client_secret' => 'ccd34245a7340bf843503931d6598dd9',
            'scope'         => array('email','read_friendlists','user_online_presence'),
        ),      

    )

);