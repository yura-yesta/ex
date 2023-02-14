<?php

class WC_Dojo_Payment{

    public static string $sk = DOJO_KEY;

    public static function init()
    {

        add_action(
            'wp_ajax_dojo_ajax',
            array(self::class, 'connectE_ajax')
        );
        add_action(
            'wp_ajax_nopriv_dojo_ajax',
            array(self::class, 'connectE_ajax')
        );
        add_action(
            'wp_enqueue_scripts',
            array(self::class, 'add_dojo_script')
        );
//        add_action(
//            'admin_menu',
//            array( self::class, 'admin_menu_dojo' ),
//            80
//        );
    }


    public static function connectE_ajax()
    {

        switch ($_POST['method']) {
            case 'check_order':
                echo self::check_order();
                break;
            case 'dojo_get_token':
                echo self::dojo_get_token();
                break;
            case 'dojo_change_amount':
                echo self::dojo_change_amount();
                break;
        }

        die;
    }


    public static function dojo_get_token($id, $amount)
    {

        if ( empty( $id ) || empty( $amount ) ) return false;

        $url = "https://api.dojo.tech/payment-intents";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "content-type: application/json",
            "Authorization: Basic " . self::$sk,
            "version: 2022-04-07",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $currencyCode = !empty($_POST['currencyCode']) ? $_POST['currencyCode'] : 'GBP';

        $data = array(
            "captureMode" => "Auto",
            "reference" => $id,
            "amount" =>array("value" => $amount, "currencyCode" => $currencyCode),
            "config" => array(
                "payment" => array(
                    "customAmountAllowed" => true,
                    "tipsAllowed" => true)
            )
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode((object)$data));

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        return $resp;
    }


    public static function add_dojo_script()
    {

        if (!is_checkout()) {
            return;
        }

        wp_enqueue_script('dojo', 'https://cdn.dojo.tech/payments/v1/client.js');
    }


    public static function check_order($pre_order = false){

        global $wpdb;

        $user_id = get_current_user_ID();
        $amount = $_POST['amount'];


        if($_POST['status'] === 'payment_success') {

            $md = $_POST['md'];
            //return json_encode([$_POST, ['user_id' => $user_id, 'amount' => $amount], json_encode(WC()->session->get('cart'))]);

            return $wpdb->update('wp_enapps_wc_payments',
                [
                    'user_id' => $user_id,
                    'md' => $md,
                    'pi_sandbox' => $_POST['pi_sandbox'],
                    'order_status' => 'processed',
                    'summ' => $amount,
                    'details' => json_encode(WC()->session->get('cart')),
                ],
                [
                    'id' => $_POST['pre_order_id']
                ]
            );

        }

        if ($_POST['status'] === 'order_status') {



            $orders = $wpdb->get_results("SELECT * FROM wp_enapps_wc_payments WHERE user_id = $user_id AND order_status = 'processed'");

            $refund_orders = [];
            $processed = false;

            foreach ($orders as $order) {

                if ($order->summ === $amount) {
                    $processed = true;
                } else {
                    $refund_orders []=  $order;
                }
            }

            if(!empty($refund_orders)) {

                self::refund_payment($refund_orders);
            }

            return $processed;
        }
    }


    private static function refund_payment( $orders)
    {

        global $wpdb;

        foreach ($orders as $order){

            if (self::dojo_refund($order)){

                $wpdb->update( 'wp_enapps_wc_payments', [ 'order_status' => 'refunded'], [ 'id' => $order->id ]);
            }
        }

    }


    private static function dojo_refund($order){

        $pi_sandbox = $order->pi_sandbox;
        $sk_sandbox = self::$sk;
        $amount = $order->summ * 100;
        $url = "https://api.dojo.tech/payment-intents/$pi_sandbox/refunds";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "content-type: application/json",
            "Authorization: Basic $sk_sandbox",
            "version: 2022-04-07",
            "idempotencyKey: 4b5ada68-545d-41ed-a9a2-b3264a1deb8d",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $data = '{"amount":'.$amount.',"refundReason":"Demo refund","notes":"Duplicate transaction"}';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode($resp);

        return !empty($resp->refundId);

    }


    private static function dojo_change_amount(){

        $pi_sandbox = $_POST['pi_sandbox'];
        $amount = $_POST['amount'];
        $url = "https://api.dojo.tech/payment-intents/$pi_sandbox/amount";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "content-type: application/json",
            "Authorization: Basic " . self::$sk,
            "version: 2022-04-07",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = array('amount' => array('value' => $amount, 'currencyCode' => 'GBP'));

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        echo $resp;
    }


    public static function get_payment_intent( $pi_id )
    {
        if( empty( $pi_id ) ) return '';

        $url = "https://api.dojo.tech/payment-intents/$pi_id";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "content-type: application/json",
            "Authorization: Basic " . self::$sk,
            "version: 2022-04-07",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);

        return json_decode($resp);
    }


    public static function get_payment_intent_status( $id ): string
    {
        $pi = self::get_payment_intent( $id );

        return !empty( $pi->status ) ? $pi->status : '';
    }


    public static function admin_menu_dojo(){

        add_options_page('Dojo Menu', 'Dojo Menu', 'manage_options', 'true_dojo', array( static::class, 'true_dojo_page_callback' ) );
    }


    public static function true_dojo_page_callback(){

        if ( !empty( $_POST['live_sk'] ) && !empty( $_POST['dev_sk'] ) ){

            update_option( 'admin_dojo_live_pk', $_POST['live_pk'] );
            update_option( 'admin_dojo_live_sk', $_POST['live_sk'] );
            update_option( 'admin_dojo_dev_pk', $_POST['dev_pk'] );
            update_option( 'admin_dojo_dev_sk', $_POST['dev_sk'] );

            if ( !empty( $_POST['dojo_mode'] ) ) {
                update_option('admin_dojo_mode', $_POST['dojo_mode']);

                if ( $_POST['dojo_mode'] === 'test' ){

                    update_option( 'admin_dojo_pk', $_POST['dev_pk'] );
                    update_option( 'admin_dojo_sk', $_POST['dev_sk'] );
                }else{

                    update_option( 'admin_dojo_pk', $_POST['live_pk'] );
                    update_option( 'admin_dojo_sk', $_POST['live_sk'] );
                }
            }

            echo '<h4 style="color: green">Updated</h4>';
        } else {

            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) echo '<h4 style="color: red">Not UPDATED. Empty live or test keys fields.</h4>';
        }

        $dojo_live_sk   = get_option( 'admin_dojo_live_sk' ) ?? '';
        $dojo_dev_sk    = get_option( 'admin_dojo_dev_sk' ) ?? '';
        $dojo_sk        = get_option( 'admin_dojo_sk' ) ?? '';
        $dojo_mode      = get_option( 'admin_dojo_mode' ) ?: '<span style="color: red">MODE IS NOT SET</span>';

        ?>

        <p><h1>Dojo Api <span onclick="display_dojo_keys()">Keys</span></h1></p>
        <hr>
        <form action="" method="post">
            <u><h4>Test keys</h4></u>
            <label for="dev_sk">Secret key:</label><br>
            <input type="password" size="50" id="dev_sk" name="dev_sk" value="<?= $dojo_dev_sk ?>"><br><br>
            <u><h4>Live keys</h4></u>
            <label for="live_sk">Secret key:</label><br>
            <input type="password" size="50" id="live_sk" name="live_sk" value="<?= $dojo_live_sk ?>"><br><br>
            <h4><u>Currently using: <?= $dojo_mode ?> </u></h4>
            <input type="radio" id="test_mode" name="dojo_mode" value="test" <?= 'test' === $dojo_mode ? 'checked' : ''; ?>>
            <label for="test_mode">Use Test Mode</label><br>
            <input type="radio" id="live_mode" name="dojo_mode" value="live" <?= 'live' === $dojo_mode ? 'checked' : ''; ?>>
            <label for="live_mode">Use Live Mode</label><br>
            <hr>
            <p>
                <input type="submit" value="UPDATE">
            </p>
        </form>

        <?php

        echo '<div id="dojo_keys_list" style="display: none">';
        echo('<br>admin_dojo_live_sk - > ' . $dojo_live_sk);
        echo('<br>admin_dojo_dev_sk  - > ' . $dojo_dev_sk);
        echo '<br>keys are installed:';
        echo('<br>admin_dojo_sk - > ' . $dojo_sk);

        echo '</div>';
        ?>
        <script>
            function display_dojo_keys() {
                var x = document.getElementById("dojo_keys_list");
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }
        </script>
        <?php
    }
}
