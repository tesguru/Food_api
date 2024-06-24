<?php

class Utility extends CI_Controller
 {
    public function __construct()
 {
        parent::__construct();
        header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
        header( 'Access-Control-Allow-Headers: Content-Type, x-api-key,client-id' );
        header( 'Access-Control-Allow-Credentials: true' );
        header( 'Access-Control-Allow-Origin: *' );
        if ( 'OPTIONS' === $_SERVER[ 'REQUEST_METHOD' ] ) {
            die();
        }
    }

    // CALL API

    public function call_api( $method, $url, $header, $data = false )
 {

        $curl = curl_init();
        // return $response = array( 'status' => FALSE, 'response' => $urlll, 'message' => $data );
        // return $data;
        switch ( $method ) {
            case 'POST':
            //   return $response = array( 'status' => FALSE, 'response' => $url, 'message' => $data );
            curl_setopt( $curl, CURLOPT_POST, true );
            if ( $data ) {

                curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
            } 
            break;
            case 'Q_POST':
            curl_setopt( $curl, CURLOPT_POST, true );
            if ( $data ) {
                $data = http_build_query( $data );

                curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
            }
            break;
            case 'PUT':
            curl_setopt( $curl, CURLOPT_PUT, 1 );
            break;
            default:
            $url = sprintf( '%s?%s', $url, http_build_query( $data ) );

            if ( $data ) {
                $url = $url . "/$data";
            }

        }
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

        $result = curl_exec( $curl );
        $err = curl_error( $curl );

        curl_close( $curl );

        if ( $err ) {
            $response = 'cURL Error #:' . $err;
        } else {
            $response = $result;
        }

        return $response;
    }

    public function insertQuery( $tableName, $tableData ) {
        $sqlQuery = $this->db->insert( $tableName, $tableData );
        return $sqlQuery;
    }

    public function isExist( $tableName, $columnName, $data ) {
        $sqlQuery = $this->db->query( "SELECT * FROM $tableName WHERE $columnName='$data'" )->result_array();
        if ( count( $sqlQuery ) > 0 ) {
            $response = array( 'status_code'=>'1', 'message'=>$data.' already exist' );
        } else {
            $response = array( 'status_code'=>'0', 'message'=>$data. " doesn't exist" );
        }
        return $response;
    }

    public function select_table( $tableName ) {
        $response = $this->db->query( "SELECT * FROM $tableName" )->result_array();

        return $response;
    }

    public function updateQuery( $tableName, $data, $id, $real_id ) {
        $sqlQuery =  $this->db->update( $tableName, $data, array( $id => $real_id ) );
        return $sqlQuery;

    }

    public function deleteQuery( $tableName,  $columnName, $columndata ) {
        $sqlQuery =  $this->db->delete( $tableName, array( $columnName => $columndata ) );
        if ( $sqlQuery == TRUE ) {
            $response = array( 'status_code'=>'0', 'message'=>'Deleted Successful' );

        } else {
            $response = array( 'status_code'=>'1', 'message'=>'Error Occured' );

        }
        return $response;
    }

    public function select_column( $tableName, $columnName, $data ) {
        $sqlQuery = $this->db->query( "SELECT * FROM $tableName WHERE $columnName='$data'" )->result_array();
        if ( count( $sqlQuery ) > 0 ) {
            $response = array( 'status_code'=>'1', 'message'=>'Successful', 'result'=>$sqlQuery );
        } else {
            $response = array( 'status_code'=>'0', 'message'=>$data. " doesn't exist" );
        }
        return $response;
    }





}