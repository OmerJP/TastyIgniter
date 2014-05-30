<?php

class Home extends MX_Controller {

	public function __construct() {
		parent::__construct(); 																	// calls the constructor
		$this->load->model('Locations_model'); 													// loads the location model
		$this->load->library('location'); 														// load the location library
		$this->load->library('currency'); 														// load the currency library
					
		$this->load->library('language');
		$this->lang->load('main/home', $this->language->folder());
	}

	public function index() {
		if ($this->session->flashdata('alert')) {
			$data['alert'] = $this->session->flashdata('alert'); 								// retrieve session flashdata variable if available
		} else {
			$data['alert'] = '';
		}

		if ($this->session->flashdata('local_alert')) {
			$data['local_alert'] = $this->session->flashdata('local_alert'); 								// retrieve session flashdata variable if available
		} else {
			$data['local_alert'] = '';
		}

		// START of retrieving lines from language file to pass to view.
		$this->template->setTitle($this->lang->line('text_heading'));
		$this->template->setHeading($this->lang->line('text_heading'));
		$data['text_local'] 			= $this->lang->line('text_local');
		$data['text_postcode'] 			= ($this->config->item('search_by') === 'postcode') ? $this->lang->line('entry_postcode') : $this->lang->line('entry_address');
		$data['text_find'] 				= $this->lang->line('text_find');
		$data['text_delivery_charge'] 	= $this->lang->line('text_delivery_charge');
		$data['text_reviews'] 			= $this->lang->line('text_reviews');
		$data['text_opening_hours'] 	= $this->lang->line('text_opening_hours');
		$data['text_open'] 				= $this->lang->line('text_open');
		$data['text_distance'] 			= $this->lang->line('text_distance');		
		$data['text_covered_area'] 		= $this->lang->line('text_covered_area');		
		$data['button_view_menu'] 		= $this->lang->line('button_view_menu');
		// END of retrieving lines from language file to send to view.

		$data['local_action']			= site_url('main/local_module/distance');
		$data['menus_url']				= site_url('main/menus');

		if ($this->config->item('maps_api_key')) {
			$data['map_key'] = $this->config->item('maps_api_key');
		} else {
			$data['map_key'] = '';
		}

		$local_info = $this->session->userdata('local_info');
		if ($local_info['search_query']) {
			$data['postcode'] = $local_info['search_query'];
		} else {
			$data['postcode'] = '';
		}

		$data['local_location'] = $this->location->local(); 									//retrieve local restaurant data from location library
		
		if ($data['local_location']) { 															//if local restaurant data is available
			$data['location_name'] 			= $data['local_location']['location_name'];
			$data['location_address_1'] 	= $data['local_location']['location_address_1'];
			$data['location_city'] 			= $data['local_location']['location_city'];
			$data['location_postcode'] 		= $data['local_location']['location_postcode'];
			$data['location_telephone'] 	= $data['local_location']['location_telephone'];
			$data['distance'] 				= $this->location->distance(); //format diatance to 2 decimal place
			$data['delivery_charge']		= ($this->location->getDeliveryCharge() > 0) ? $this->currency->format($this->location->getDeliveryCharge()) : $this->lang->line('text_free');
			$data['reviews']				= '2 reviews';
		}
		
		$data['opening_hours'] = $this->location->getOpeningHours(); 								//retrieve local restaurant opening hours from location library
		
		if ($this->location->isOpened()) { 														// check if local restaurant is open
			$data['text_open_or_close'] = $this->lang->line('text_opened');						// display we are open
		} else {
			$data['text_open_or_close'] = $this->lang->line('text_closed');						// display we are closed
		}
			
		if ($this->location->checkDelivery() === 'no') { 														// checks if cart contents is empty  
			$data['text_delivery'] = $this->lang->line('text_delivery_n');						// display we are closed
		} else if ($this->location->checkDelivery() === 'outside') {		
			$data['text_delivery'] = $this->lang->line('text_covered_area');		
		} else {
			$data['text_delivery'] = $this->lang->line('text_delivery_y');						// display we are open
		}

		if ($this->location->checkCollection()) { 														// checks if cart contents is empty  
			$data['text_collection'] = $this->lang->line('text_collection_y');						// display we are open
		} else {
			$data['text_collection'] = $this->lang->line('text_collection_n');						// display we are closed
		}
		
		$this->template->regions(array('header', 'content_top', 'content_left', 'content_right', 'footer'));
		if (file_exists(APPPATH .'views/themes/main/'.$this->config->item('main_theme').'home.php')) {
			$this->template->render('themes/main/'.$this->config->item('main_theme'), 'home', $data);
		} else {
			$this->template->render('themes/main/default/', 'home', $data);
		}
	}
}


/* End of file home.php */
/* Location: ./application/controllers/main/home.php */