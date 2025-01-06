<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('client_status_badge')) {
	function client_status_badge($status) {
		$badge_style = [
			'Active' => 'bg-light text-primary border-primary',
			'New' => 'bg-light text-success border-success',
			'Past Active' => 'bg-light text-warning border-warning',
			'Non-Active' => 'bg-secondary',
			'Deleted' => 'bg-danger'
		];
		return '<span class="badge badge-pill '.$badge_style[$status].'">'.$status.'</span>';
	}
}


if (!function_exists('quotation_status_badge')) {
	function quotation_status_badge($status) {
		$badge_style = [
			'New' => 'bg-light text-success border-success',
			'Confirmed' => 'bg-light text-info border-info',
			'Chosen Other CB' => 'bg-purple text-white',
			'On-Hold' => 'bg-yellow text-yellow',
			'Dropped by ASA' => 'bg-red',
			'Dropped by Client' => 'bg-orange',
			'Non-Active' => 'bg-grey text-dark',
		];
		return '<span class="badge badge-pill '.$badge_style[$status].'">'.$status.'</span>';
	}
}


if (!function_exists('send_quotation_status_badge')) {
	function send_quotation_status_badge($status) {
		$badge_style = [
			'App Form Sent' => 'bg-light text-success border-success',
			'App Form Received' => 'bg-light text-info border-info',
			'Quotation Not Sent' => 'bg-purple',
			'Quotation Sent' => 'bg-blue',
			'Dropped by Client' => 'bg-orange',
			'Dropped by ASA' => 'bg-red'
		];
		return '<span class="badge badge-pill '.$badge_style[$status].'">'.$status.'</span>';
	}
}

if (!function_exists('invoice_status_badge')) {
	function invoice_status_badge($status) {

		$badge_style = [
			'New' => 'bg-light text-success border-success',
			'Due' => 'bg-light text-dark-blue border-dark-blue',
			'Partially Paid' => 'bg-blue',
			'Late' => 'text-purple bg-white border-purple',
			'Paid' => 'text-orange bg-white border-orange',
			'Cancelled' => 'bg-red',
			'Draft' => 'bg-grey border-dark text-dark'
		];
		if ($status) {
			return '<span class="badge badge-pill '.$badge_style[$status].'">'.$status.'</span>';
		}
		return '';
	}
}

if (!function_exists('receipt_status_badge')) {
	function receipt_status_badge($status) {

		$badge_style = [
			'Success' => 'bg-blue',
			'Cancelled' => 'bg-red',
		];
		if ($status) {
			return '<span class="badge badge-pill '.$badge_style[$status].'">'.$status.'</span>';
		}
		return '';
	}
}
