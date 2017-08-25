<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 *	@author : CodesLab
 *  @support: support@codeslab.net
 *	date	: 05 June, 2015
 *	Easy Inventory
 *	http://www.codeslab.net
 *  version: 1.0
 */

class Global_Model extends MY_Model
{
    public $_table_name;
    public $_order_by;
    public $_primary_key;
    public $_order = '';

    
    public function get_last_id($table, $key, $id)
    {
        $this->db->select_max($key);
        $Q = $this->db->get($table);
        $row = $Q->row_array();
        $last_id = $row[$id];

        return $last_id;
    }

    public function get_all_sub_category_by_id($category_id)
    {
        $this->db->select('tbl_sub_category.*', false);
        $this->db->select('tbl_category.category_name', false);
        $this->db->from('tbl_sub_category');
        $this->db->join('tbl_category', 'tbl_category.category_id  =  tbl_sub_category.category_id ', 'left');
        $this->db->where('tbl_category.category_id', $category_id);
        $query_result = $this->db->get();
        $result = $query_result->result();

        return $result;
    }

    public function check_product_code($product_code, $product_id)
    {
        $this->db->select('tbl_product.*', false);
        $this->db->from('tbl_product');
        if (!empty($product_id)) {
            $this->db->where('product_id !=', $product_id);
        }
        $this->db->where('product_code', $product_code);
        $query_result = $this->db->get();
        $result = $query_result->row();

        return $result;
    }
    public function check_user_name($user_name, $user_id)
    {
        $this->db->select('tbl_user.*', false);
        $this->db->from('tbl_user');
        if ($user_id) {
            $this->db->where('user_id !=', $user_id);
        }
        $this->db->where('user_name', $user_name);
        $query_result = $this->db->get();
        $result = $query_result->row();

        return $result;
    }

    public function get_countries()
    {
        $this->db->select('tbl_country.*', false);
        $this->db->from('tbl_country');
        $query_result = $this->db->get();
        $result = $query_result->result();

        return $result;
    }
    public function get_country($id)
    {
        $this->db->select('tbl_country.*', false);
        $this->db->from('tbl_country');
        $this->db->where('country_id', $id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        foreach ($result as $key)
        return $key->name;
    }

    public function get_country_currency_code($id)
    {
        $this->db->select('tbl_country.*', false);
        $this->db->from('tbl_country');
        $this->db->where('country_id', $id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        foreach ($result as $key)
        return $key->currency;
    }

    public function get_product_bellow_qty($product_id, $product_quantity, $i)
    {
       $user_type = $this->session->userdata('user_type');
            $this->db->select('tbl_inventory.*', false);
            $this->db->select('tbl_product.product_name, tbl_product.product_code, tbl_product_image.filename', false);
            $this->db->from('tbl_inventory');
            $this->db->where('tbl_inventory.product_id', $product_id);

            if($user_type == 1){
            $this->db->where('tbl_inventory.notify_quantity >=', ($product_quantity/$i));
            }
            else if($user_type == 0){
            $this->db->where('tbl_inventory.notify_quantity >=', $product_quantity);
            }

            $this->db->join('tbl_product', 'tbl_product.product_id  =  tbl_inventory.product_id ', 'left');
            $this->db->join('tbl_product_image', 'tbl_product_image.product_id  =  tbl_inventory.product_id ', 'left');

            $query_result = $this->db->get();
            $result= $query_result->row();
            return $result;

    }
    public function get_default_currency()
    {
        $this->db->select('tbl_business_profile.*', false);
        $this->db->from('tbl_business_profile');
        $query_result = $this->db->get();
        $result = $query_result->result();
        foreach ($result as $key)
        return $key->currency;
    }

    public function log($activity){

    $data = array(
       'userId' => $this->session->userdata('employee_id'),
       'activityTitle' => $this->session->userdata('name').' '.$activity,
       'activityDate' => date('Y-m-d H:i:s'),
       'ipAddress' => $this->input->ip_address(),
       'country_id' => $this->session->userdata('user_country') 
    );

    $this->db->insert('tbl_activity', $data);
}
}
