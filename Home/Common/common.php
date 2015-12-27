<?php
/**
 * Created by PhpStorm.
 * User: bo
 * Date: 2015/10/26
 * Time: 17:41
 */

function getUID(){
   $uid = session('loginId');
   return $uid;
}

function is_login() {
    $user = session( 'user_auth' );
    if ( empty( $user ) ) {
        return 0;
    } else {
        return session( 'user_auth_sign' ) == data_auth_sign( $user ) ? $user['uid'] : 0;
    }
}

function data_auth_sign( $data ) {
    if ( !is_array( $data ) ) {
        $data = (array)$data;
    }
    ksort( $data );
    $code = http_build_query( $data );
    $sign = sha1( $code );
    return $sign;
}
