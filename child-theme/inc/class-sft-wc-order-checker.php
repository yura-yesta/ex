<?php


class SFT_WC_Order_Checker{


    public static function init(){

        add_action(
            'wp_footer',
            array( self::class, 'cron_order_checker' ),
            50
        );
    }


    public static function get_orders()
    {
        $query = new WC_Order_Query( array(
            'limit'        => 10,
            'orderby'      => 'date',
            'order'        => 'DESC',
            'return'       => 'ids',
            'status'       => array('wc-pending'),
            'date_created' => '<' . ( time() - 600 ),
        ) );

        try {

            return $query->get_orders();
        } catch (Exception $e) {

            return false;
        }
    }


    public static function cron_order_checker(): void
    {
        $orders = self::get_orders();

        if( empty( $orders ) ) return;

        foreach ($orders as $id){

            $order = new WC_Order($id);

            $paymentIntentID = get_post_meta( $order->get_id(), 'paymentIntentID', true );

            if( empty( $paymentIntentID ) ){

                $order->update_status('wc-failed', "[order_checker] Order was cancelled. Payment Intent ID not exist!!!");
                $order->save();

                continue;
            }

            $pi = WC_Enapps_Dojo_Payment::get_payment_intent( $paymentIntentID );

            if ( !empty( $pi->status ) && 'Captured' === $pi->status ){

                $args = array(
                    'id'        => $pi->id,
                    'amount'    => $pi->totalAmount->value,
                    'currency'  => $pi->totalAmount->currencyCode,
                    'status'    => $pi->status,
                    'desc'      => "[order_checker] Order processing. Pi_id: $pi->id; amount: {$pi->totalAmount->value}; {$pi->totalAmount->currencyCode}; status: $pi->status;",
                    'wc-status' => "wc-processing"
                );
            }else{

                $args = array(
                    'id'        => !empty( $pi->id )  ? $pi->id : 'empty id',
                    'amount'    => !empty( $pi->totalAmount->value ) ? $pi->totalAmount->value : 'empty amount',
                    'currency'  => !empty( $pi->totalAmount->currencyCode ) ? $pi->totalAmount->currencyCode : 'empty currencyCode',
                    'status'    => !empty( $pi->status ) ? $pi->totalAmount->status : 'empty status',
                    'desc'      => "[order_checker] Something wrong. Order was cancelled. Pi_id: $pi->id ; amount: {$pi->totalAmount->value}; {$pi->totalAmount->currencyCode}; status: $pi->status; ",
                    'wc-status' => "wc-failed"
                );
            }

            if ( ( $amount = (int)$args['amount'] ) && ( $amount / 100 ) ) $order->update_meta_data('pi_amount', $amount / 100 );
            $order->update_status( $args['wc-status'], $args['desc'] );
            $order->save();

            if ( 'processing' === $order->get_status() ) enapps_woo_save_order( $order->get_id(), 'failed' );
        }
    }
}