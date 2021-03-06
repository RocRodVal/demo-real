<?php
/**
*Initialize the pagination rules for dashboard
* @return Pagination
*/
class Paginationlib {

    function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->library('session');
    }

    public function init_pagination($base_url="",$total_rows,$per_page=FALSE, $segment = FALSE){
        $config['per_page']          = ($per_page !== FALSE) ? $per_page : 10 ;
        $config['uri_segment']       = ($segment !== FALSE) ? $segment : 3 ;
        $config['base_url']          = base_url().$base_url;
        $config['base_url']          = base_url().$base_url;
        $config['total_rows']        = $total_rows;
        $config['use_page_numbers']  = TRUE;
        $config['num_links']          = 5;
        $config['first_tag_open'] = '<li class="paginate_button first">';
        $config['last_tag_open']= '<li class="paginate_button last">';
        $config['next_tag_open']='<li class="paginate_button next">';
        $config['prev_tag_open'] = '<li class="paginate_button prev">';
        $config['num_tag_open'] = '<li class="paginate_button ">';

        $config['first_tag_close'] = '</li>';
        $config['last_tag_close']= '</li>';
        $config['next_tag_close']= '</li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="paginate_button active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';



        return $config;
    }


    public function get_bounds($total_registros, $page = 1, $per_page=100){
        $bounds = array();
        $bounds["num_resultados"] = $total_registros;
        $bounds["show_paginator"] = ($total_registros > $per_page) ? true : false;
            $n_inicial = ($page - 1) * $per_page + 1;
            $n_inicial = ($n_inicial == 0) ? 1 : $n_inicial;
        $bounds['n_inicial'] = $n_inicial;
            $n_final = ($n_inicial) + $per_page -1 ;
            $n_final = ($total_registros < $n_final) ? $total_registros : $n_final;
        $bounds['n_final'] = $n_final;

        return $bounds;
    }



}