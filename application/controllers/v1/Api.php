<?php
use Restserver\Libraries\REST_Controller;

require_once APPPATH . 'controllers/v1/Utility.php';
require_once 'application/libraries/Format.php';
require APPPATH . '/libraries/REST_Controller.php';

//

class Api extends REST_Controller
 {

    public function __construct()
 {
        parent::__construct();
        header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
        header( 'Access-Control-Allow-Headers: Content-Type, x-api-key' );
        header( 'Access-Control-Allow-Credentials: true' );
        header( 'Access-Control-Allow-Origin: *' );
        if ( 'OPTIONS' === $_SERVER[ 'REQUEST_METHOD' ] ) {
            die();
        }
    }

    public function test_get() {
        echo 'Api is working well';
    }

    //Customer Unboarding Start///

    public function  create_customer_post() {
        $first_name = $this->input->post( 'first_name' );
        $last_name = $this->input->post( 'last_name' );
        $email = $this->input->post( 'email' );
        $phoneNumber = $this->input->post( 'phonenumber' );
        if (empty($first_name) || !preg_match('/^[A-Za-z]+$/', $first_name)) {
            $this->response(array('status_code' => '1', 'message' => 'First Name is required and should contain only letters'));
        }
    
        if (empty($last_name) || !preg_match('/^[A-Za-z]+$/', $last_name)) {
            $this->response(array('status_code' => '1', 'message' => 'Last Name is required and should contain only letters'));
        }
        
        if ( $email == '' )
 {
            $this->response( array( 'status_code'=>'1', 'message'=>'email is required' ) );
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('status_code' => '1', 'message' => 'Invalid email format'));
        }
        if ( $phoneNumber == '' )
 {
            $this->response( array( 'status_code'=>'1', 'message'=>'Phone Number is required' ) );
        }

        $utility = new utility();
        try {
            $user_id = substr( strtoupper( $first_name ), 0, 1 ).substr( strtoupper( $last_name ), 0, 1 ).mt_rand( 0000, 9999 );
            $data = array( 'insert_dt'=>date( 'Y-m-d H:i:s' ),
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'email'=>$email,
            'phonenumber'=>$phoneNumber,
            'user_id'=>$user_id );
            $isEmailExist = $utility->isExist( 'customer', 'email', $email );
            if ( $isEmailExist[ 'status_code' ] == '1' ) {
                return $this->response( $isEmailExist );
            }
            $submit_data = $utility->insertQuery( 'customer', $data );
            if ( $submit_data == TRUE ) {
                $this->response( array( 'status_code'=>'0', 'message'=>'Customer Account Created Successful', 'result'=>array( 'user_id'=>$user_id ) ) );
            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Create User API Error: ' . $ex->getMessage() ) );
        }

    }

    public function update_customer_post() {
        $first_name = $this->input->post( 'first_name' );
        $last_name = $this->input->post( 'last_name' );
        $email = $this->input->post( 'email' );
        $phoneNumber = $this->input->post( 'phonenumber' );
        if ( $first_name == '' )
 {
            $this->response( array( 'status_code'=>'1', 'message'=>'First Name is required' ) );
        }
        if ( $last_name == '' )
 {
            $this->response( array( 'status_code'=>'1', 'message'=>'last Name is required' ) );
        }
        if ( $phoneNumber == '' )
 {
            $this->response( array( 'status_code'=>'1', 'message'=>'Phone Number is required' ) );
        }
        if ( $email == '' )
 {
            $this->response( array( 'status_code'=>'1', 'message'=>'email is required' ) );
        }

        $utility = new utility();
        try {
            $data = array(
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'phonenumber'=>$phoneNumber
            );
            $isEmailExist = $utility->isExist( 'customer', 'email', $email );
            if ( $isEmailExist[ 'status_code' ] == '0' ) {
                return $this->response( array( 'status_code'=>'1', 'message'=>$isEmailExist[ 'message' ] ) );
            }

            $submit_data =  $utility->updateQuery( 'customer', $data, 'email', $email );

            if ( $submit_data == TRUE ) {
                $this->response( array( 'status_code'=>'0', 'message'=>'Updated Successfully' ) );

            } else {
                $this->response( array( 'status_code'=>'1', 'message'=>'An error occurred' ) );

            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Create User API Error: ' . $ex->getMessage() ) );
        }

    }

    public function get_customers_get() {
        $utility = new Utility();
        try {
            $getCustomers = $utility->select_table( 'customer' );

            if ( $getCustomers != false ) {
                $this->response( array( 'status_code' => '0', 'message' => 'Successful', 'result' => $getCustomers ) );
            } else {
                $this->response( array( 'status_code' => '1', 'message' => 'An error occurred' ) );
            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
        }
    }

    public function get_single_customer_get() {
        $email =  $this->input->get( 'email' );
        if ( $email == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'email is required' ) );
        }
        $utility = new utility();
        try {
            $get_customer = $utility->select_column( 'customer', 'email', $email );
            if ( $get_customer[ 'status_code' ] == '1' ) {
                $this->response( array( 'status_code'=>'0', 'message'=>'succesful', 'result'=>$get_customer[ 'result' ] ) );
            } else {
                $this->response( array( 'status_code'=>'1', 'message'=>$email.'dosent exist' ) );
            }

        } catch( exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
        }
    }

    public function  delete_customer_get() {
        $email =  $this->input->get( 'email' );
        if ( $email == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'email is required' ) );
        }

        $utility = new utility();
        try {
            $isEmailExist = $utility->isExist( 'customer', 'email', $email );
            if ( $isEmailExist[ 'status_code' ] == '0' ) {
                return $this->response( array( 'status_code'=>'1', 'message'=>$isEmailExist[ 'message' ] ) );
            }
            $delete_customer = $utility->deleteQuery( 'customer', 'email', $email );
            $this->response( $delete_customer );
        } catch( exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
        }
    }
    //Customer Unboarding stop//

    //food Unboarding Start//

    public function create_food_post() {
        $food_name = $this->input->post( 'food_name' );
        $recipe = $this->input->post( 'recipe' );
        $chef_id = $this->input->post( 'chef_id' );
        $duration = $this->input->post( 'duration' );

        if ( $food_name == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Food Name is required' ) );
        }
        if ( $recipe == '' ) {
            $this->response( array( 'status_code' => '1', 'message'=>'Recipe is required' ) );
        }
        if ( $chef_id == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Chef Id is required' ) );
        }
        if ( $duration == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Duration is required' ) );
        }
        $utility = new Utility();
        try {
            $data = array(
                'insert_dt'=> date( 'Y-m-d H:i:s' ),
                'status' => 1,
                'food_name'=>$food_name,
                'recipe'=>$recipe,
                'chef_id'=>$chef_id,
                'duration'=>$duration
            );
            $isFoodExist = $utility->isExist( 'food', 'food_name', $food_name );
            if ( $isFoodExist[ 'status_code' ] == '1' ) {
                return $this->response( $isFoodExist );
            }
            $submit_data = $utility->insertQuery( 'food', $data );
            if ( $submit_data == TRUE ) {
                $this->response( array( 'status_code'=>'0', 'message'=>'Food Name Created Successfully' ) );
            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Create User API Error: ' . $ex->getMessage() ) );
        }

    }

    public function update_food_post() {
        $food_name = $this->input->post( 'food_name' );
        $recipe = $this->input->post( 'recipe' );
        $chef_name = $this->input->post( 'chef_name' );
        $chef_id = $this->input->post( 'chef_id' );
        $duration = $this->input->post( 'duration' );
        $status = $this->input->post( 'status' );

        if ( $food_name == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Food Name is required' ) );
        }
        if ( $recipe == '' ) {
            $this->response( array( 'status_code' => '1', 'message'=>'Recipe is required' ) );
        }

        if ( $chef_id == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Chef Id is required' ) );
        }
        if ( $duration == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Duration is required' ) );
        }
        if ( $status == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Status is required' ) );
        }
        $utility = new Utility();
        try {

            $isFoodExist = $utility->isExist( 'food', 'food_name', $food_name );
            if ( $isFoodExist[ 'status_code' ] == '0' ) {
                return $this->response( array( 'status_code'=>'1', 'message'=>$isFoodExist[ 'message' ] ) );
            }
            $get_chef_name = $utility->select_column( 'chef_details', 'chef_id', $chef_id );

            if ( $get_chef_name[ 'status_code' ] == '0' ) {
                return $this->response( array( 'status_code'=>'1', 'message'=>$get_chef_name[ 'message' ] ) );
            }

            $data = array(
                'status' => $status,
                'food_name'=>$food_name,
                'recipe'=>$recipe,
                'chef_id'=>$chef_id,
                'duration'=>$duration,
                'chef_name' => $get_chef_name[ 'result' ][ 0 ][ 'chef_name' ],
            );
            $submit_data =  $utility->updateQuery( 'food', $data, 'food_name', $food_name );

            if ( $submit_data == TRUE ) {
                $this->response( array( 'status_code'=>'0', 'message'=>'Updated Successfully' ) );

            } else {
                $this->response( array( 'status_code'=>'1', 'message'=>'An error occurred' ) );

            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Create User API Error: ' . $ex->getMessage() ) );
        }

    }

    public function list_all_foods_get() {
        $utility = new Utility();
        try {
            $list_all_foods = $utility->select_table( 'food' );

            if ( $list_all_foods != false ) {
                $this->response( array( 'status_code' => '0', 'message' => 'Successful', 'result' => $list_all_foods ) );
            } else {
                $this->response( array( 'status_code' => '1', 'message' => 'An error occurred' ) );
            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
        }

    }

    public function list_all_available_foods_get() {

        $utility = new Utility();
        try {
            $list_all_available_foods = $utility->select_table( 'food', 'status', 1 );

            if ( $list_all_available_foods != false ) {
                $this->response( array( 'status_code' => '0', 'message' => 'Successful', 'result' => $list_all_available_foods ) );
            } else {
                $this->response( array( 'status_code' => '1', 'message' => 'An error occurred' ) );
            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
        }
    }

    public function  delete_food_get() {
        $food_name =  $this->input->get( 'food_name' );
        if ( $food_name == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Food Name is required' ) );
        }

        $utility = new utility();
        try {
            $isFoodExist = $utility->isExist( 'food', 'food_name', $food_name );
            if ( $isFoodExist[ 'status_code' ] == '0' ) {
                return $this->response( array( 'status_code'=>'1', 'message'=>$isFoodExist[ 'message' ] ) );
            }
            $delete_food = $utility->deleteQuery( 'food', 'food_name', $food_name );
            $this->response( $delete_food );
        } catch( exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
        }
    }

    public function create_chef_post() {
        $chef_name = $this->input->post( 'chef_name' );
        $location = $this->input->post( 'location' );
        $email = $this->input->post( 'email' );
        if ( $chef_name == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Chef Name is required' ) );
        }
        if ( $location == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Location is required' ) );
        }
        if ( $email == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Email is required' ) );
        }
        $utility = new Utility();
        try {
            $data = array(
                'chef_name'=>$chef_name,
                'location'=>$location,
                'email'=>$email,
                'status'=>1,
                'chef_id'=>$chef_id = substr( strtoupper( 'C' ), 0, 1 ).substr( strtoupper( 'H' ), 0, 1 ).mt_rand( 0000, 9999 ),
                'inserted_dt'=>$inserted_dt = date( 'Y-m-d H:i:s' )
            );
            $isChefExist = $utility->isExist( 'chef_details', 'email', $email );
            if ( $isChefExist[ 'status_code' ] == '1' ) {
                return $this->response( $isChefExist );
            }
            $submit_data = $utility->insertQuery( 'chef_details', $data );
            if ( $submit_data == TRUE ) {
                $this->response( array( 'status_code'=>'0', 'message'=>'Chef Details Created Successful' ) );
            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Create User API Error: ' . $ex->getMessage() ) );
        }

    }

    public function update_chef_post() {
        $chef_name = $this->input->post( 'chef_name' );
        $location = $this->input->post( 'location' );
        $email = $this->input->post( 'email' );
        $status = $this->input->post( 'status' );
        if ( $chef_name == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Chef Name is required' ) );
        }
        if ( $location == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Location is required' ) );
        }
        if ( $email == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Email is required' ) );
        }
        if ( $status == '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'Status is required' ) );

        }
        $utility = new Utility();
        try {
            $data = array(
                'chef_name'=>$chef_name,
                'location'=>$location,
                'status'=>$status
            );
            $isEmailExist = $utility->isExist( 'chef_details', 'email', $email );
            if ( $isEmailExist[ 'status_code' ] == '0' ) {
                return $this->response( array( 'status_code'=>'1', 'message'=>$isEmailExist[ 'message' ] ) );
            }

            $submit_data =  $utility->updateQuery( 'chef_details', $data, 'email', $email );

            if ( $submit_data == TRUE ) {
                $this->response( array( 'status_code'=>'0', 'message'=>'Updated Successfully' ) );

            } else {
                $this->response( array( 'status_code'=>'1', 'message'=>'An error occurred' ) );

            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Create User API Error: ' . $ex->getMessage() ) );
        }
    }

    public function list_all_chefs_get() {
        $utility = new Utility();
        try {
            $list_all_chefs = $utility->select_table( 'chef_details' );

            if ( $list_all_chefs != false ) {
                $this->response( array( 'status_code' => '0', 'message' => 'Successful', 'result' => $list_all_chefs ) );
            } else {
                $this->response( array( 'status_code' => '1', 'message' => 'An error occurred' ) );
            }
        } catch( Exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
        }
    }

    public function  delete_chef_get() {
        $email =  $this->input->get( 'email' );
        if ( $email ==  '' ) {
            $this->response( array( 'status_code'=>'1', 'message'=>'email is required' ) );
        }
        $utility = new utility();
        try {
            $isEmailExist = $utility->isExist( 'chef_details', 'email', $email );
            if ( $isEmailExist[ 'status_code' ] == '0' ) {
                return $this->response( array( 'status_code'=>'1', 'message'=>$isEmailExist[ 'message' ] ) );
            }
            $delete_chef = $utility->deleteQuery( 'chef_details', 'email', $email );
            $this->response( $delete_chef );
        } catch( exception $ex ) {
            $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
        }
    }

        public function get_partners_get(){
            $utility = new Utility();
            try {
                $list_all_partners = $utility->select_table( 'pos_partners' );
    
                if ( $list_all_partners != false ) {
                    $this->response( array( 'status_code' => '0', 'message' => 'Successful', 'result' => $list_all_partners ) );
                } else {
                    $this->response( array( 'status_code' => '1', 'message' => 'An error occurred' ) );
                }
            } catch( Exception $ex ) {
                $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
            }
            
        }

        public function get_providers_get(){
            $utility = new Utility();
            try {
                $list_all_providers = $utility->select_table('pos_partners');
    
                if ( $list_all_providers != false ) {
                    $this->response( array( 'status_code' => '0', 'message' => 'Successful', 'result' => $list_all_providers ) );
                } else {
                    $this->response( array( 'status_code' => '1', 'message' => 'An error occurred' ) );
                }
            } catch( Exception $ex ) {
                $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
            }
   
        }

        public function single_banks_post() {
            $code =  $this->input->post('code');
            if ( $code == '' ) {
                $this->response( array( 'status_code'=>'1', 'message'=>'code is required' ) );
            }
            $utility = new utility();
            try {
                $get_customer = $utility->select_column( 'pos_providers', 'code', $code );
                if ( $get_customer[ 'status_code' ] == '1' ) {
                    $this->response( array( 'status_code'=>'0', 'message'=>'succesful', 'result'=>$get_customer[ 'result' ] ) );
                } else {
                    $this->response( array( 'status_code'=>'1', 'message'=>$code.'dosent exist' ) );
                }
    
            } catch( exception $ex ) {
                $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
            }
        }

        public function get_eod_post(){
            $code =  $this->input->post('code');
            if ( $code == '' ) {
                $this->response( array( 'status_code'=>'1', 'message'=>'code is required' ) );
            }
            $utility = new utility();
            try {
                $get_customer = $utility->select_column('eod', 'code', $code );
                if ( $get_customer[ 'status_code' ] == '1' ) {
                    $this->response( array( 'status_code'=>'0', 'message'=>'succesful', 'result'=>$get_customer[ 'result' ] ) );
                } else {
                    $this->response( array( 'status_code'=>'1', 'message'=>$code.'dosent exist' ) );
                }
    
            } catch( exception $ex ) {
                $this->response( array( 'status_code' => '1', 'message' => 'Get Users API Error: ' . $ex->getMessage() ) );
            }

        }

        public function input_eod_post() {
            $transaction_amount = $this->input->post( 'txn_amount' );
            $transaction_count = $this->input->post( 'txn_count' );
            $code = $this->input->post('code');
           
    
            if ( $transaction_amount == '' ) {
                $this->response( array( 'status_code'=>'1', 'message'=>'txn_amount is required' ) );
            }
            if ( $transaction_count == '' ) {
                $this->response( array( 'status_code' => '1', 'message'=>' txn_count is required' ) );
            }
            if ( $code == '' ) {
                $this->response( array( 'status_code' => '1', 'message'=>' code is required' ) );
            }
       
            $utility = new Utility();
            try {
                $data = array(
                    'transaction_amount'=>   $transaction_amount,
                    'transaction_count' =>    $transaction_count,
                    'code' =>$code
                  
                );
                $isCodeExist = $utility->isExist( 'eod', 'code', $code );
                if ( $isCodeExist[ 'status_code' ] == '1' ) {
                    $submit_data =  $utility->updateQuery( 'eod', $data, 'code', $code);
                    if ( $submit_data == TRUE ) {
                        $this->response( array( 'status_code'=>'0', 'message'=>'Updated Successfully' ) );
                    }
                }
                $submit_data = $utility->insertQuery( 'eod', $data );
                if( $submit_data == TRUE ) {
                    $this->response( array( 'status_code'=>'0', 'message'=>'Eod input Successfully' ) );
                }
            } catch( Exception $ex ) {
                $this->response( array( 'status_code' => '1', 'message' => 'Create User API Error: ' . $ex->getMessage() ) );
            }
    
        }

        
    }
