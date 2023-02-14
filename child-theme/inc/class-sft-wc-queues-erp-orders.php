<?php


class SFT_WC_Queues_Erp_Orders{

    public static array  $data          = [];
    public static string $table         = 'wp_enapps_manage_false_orders';
    public static int    $sleep_timeout = 5;
    public static int    $attempts      = 3;
    public static int    $run_interval  = 20;
    /**
     * @var bool|string
     */
    public static $erp_response = '';


    public static function init(){

        add_action(
            'wp_footer',
            array( self::class, 'script')
        );
        add_action(
            'wp_ajax_wc_queues_erp_orders',
            array( self::class, 'queues_Erp_Orders' )
        );
        add_action(
            'wp_ajax_nopriv_wc_queues_erp_orders',
            array( self::class, 'queues_Erp_Orders' )
        );
    }


    public static function queues_Erp_Orders(){

        if ( isset( $_POST['is_thank_you'] ) ) self::init_thank_you_page(10);
        else self::init_main(300);
    }


    public static function script(){

        $is_thank_you = false;

        if ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) $is_thank_you = '&is_thank_you=1';

        ?>
        <script>
            jQuery.ajax({
                url: '<?php echo admin_url("admin-ajax.php") ?>',
                type: 'POST',
                data: 'action=wc_queues_erp_orders<?= $is_thank_you ?>',
            });
        </script>
        <?php
    }


    public static function init_main( $run_interval = false ){

        if( !empty( $run_interval ) ) self::$run_interval = $run_interval;

        if ( !self::can_run() ) return;

        self::set_next_start_time();
        self::get_queue();

        if ( empty( self::$data )) return;

        self::run_send_order();
    }


    public static function init_thank_you_page( $run_interval = false ){

        if( !empty( $run_interval ) ) self::$run_interval = $run_interval;

        //self::set_next_start_time();
        self::get_queue();

        if ( empty( self::$data )) return;

        self::run_send_order();
    }


    /** Main cron function */
    public static function run_send_order()
    {
        /** Change status run to processed for active cron fields list */
        foreach ( self::$data as $order ) self::update_order( $order->id, str_replace( 'run', 'processed', $order->queue ) );
        /**  End */

        foreach ( self::$data as $order ){

            $start_time = time();

            $result = self::send_order_to_erp( $order->api_request_json );

            if( $result ) self::update_order( $order->id, 'completed' ); //self::remove_from_queue( $order->id );
            else self::change_queue_status( $order->id, $order->queue );

            $duration = time() - $start_time;

            if ( $duration ) self::set_next_start_time( is_int( $duration ) && $duration < 30 ? $duration : 30 );

            sleep( self::$sleep_timeout );
        }
    }


    public static function get_queue(): void
    {
        global $wpdb;

        self::$data = $wpdb->get_results( "SELECT * FROM " . self::$table . " WHERE queue LIKE '%run%'" );
    }


    public static function change_queue_status( $id, $status ){

        preg_match( '/\[(.*?)\]/', $status, $attempt );
        $attempt = ( int ) $attempt[1];

        $status = $attempt + 1 >= self::$attempts ? 'failed' : 'run[' . ( $attempt + 1 ) . ']';

        if ( 'failed' === $status ) self::send_email( $id );

        return self::update_order( $id, $status );
    }


    public static function update_order( $id, $status ){

        global $wpdb;

        $array = array(
            'queue'         => $status,
            'error_message' => isset(self::$erp_response) ? self::$erp_response : '',
        );

        return $wpdb->update( self::$table, $array, array( 'id' => $id ) );
    }


    public static function send_order_to_erp( $erp_json ):bool
    {

        $response = ennaps_send_order_to_erp( $erp_json );

        if ( empty( $response ) ) return false;

        $response_check = json_decode( $response, true );
        self::$erp_response = $response;

        if( $response_check['status'] != 'true' || !$response || $response_check['system_warnings'] != '{}' ) return false;
        else return true;
    }


    public static function remove_from_queue( $id ): bool
    {
        global $wpdb;

        return $wpdb->delete( self::$table, array( 'id' => $id ) );
    }


    public static function set_next_start_time( $time_interval = false ): bool
    {
        $last_time     = get_option( 'queues_erp_order_starts_time' );
        $time_interval = $time_interval ? $time_interval : self::$run_interval;

        if( empty( $last_time ) || $last_time < time() ) $last_time = time();

        $last_time = $last_time + $time_interval;

        return update_option( 'queues_erp_order_starts_time', $last_time );
    }


    public static function can_run(): bool
    {
        $last_time = get_option( 'queues_erp_order_starts_time' );
        $current_time = time();

        if( empty( $last_time ) ) return true;
        else return $current_time > $last_time;
    }


    public static function send_email( $order_id ){

        $response_check = json_decode( self::$erp_response, true );

        if( $response_check['system_warnings'] != '(\'Warning !\', \'WooCommerce Id must be unique.\')' ){

            $error_message = 'The following problem ('.$response_check['system_warnings'].') arose when creating the order (woo id: '.$order_id.') on the https://ornworkwear.com/ website. Please re-send this order again from admin panel.';

            wp_mail('anton.pavliv.mr@gmail.com, ivan@enapps.co.uk', 'Error with Order Woo ID: '.$order_id, $error_message);

        }
    }

}