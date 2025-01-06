<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('dt_number_format'))
{
    function dt_number_format($number, $decimals = 2)
    {
        $number = (float) $number;

        return number_format($number, $decimals);
    }
}




if (!function_exists('dt_money_format'))
{
    function dt_money_format($number)
    {
        if ($number) {
            return money_number_format($number);
        }
        return '';
    }
}




// should format it via SQL, rather than front-end, allowing DT's search to work
if (!function_exists('dt_datestring_to_format'))
{
    function dt_datestring_to_format($datetime, $format = 'j M Y, g:i A')
    {
        $output = '-';
        if (!empty(str_replace(array("0", "-", ":", " "), "", $datetime))) {
            $output = datestring_to_format($datetime, $format);
        }

        return $output;
    }
}




// should format it via SQL, rather than front-end, allowing DT's search to work
if (!function_exists('dt_timestamp_to_format'))
{
    function dt_timestamp_to_format($timestamp, $format = 'j M Y, g:i A')
    {
        if ($timestamp == '') {
           return '-';
        }

        $output = timestamp_to_format($timestamp, $format);

        return $output;
    }
}






// should format it via SQL, rather than front-end, allowing DT's search to work
if (!function_exists('dt_note'))
{
    function dt_note($id, $note, $first_name, $last_name, $created_on)
    {
        if ($note == '') {
           return '-';
        }
        $note = substr($note, 0, 100);
		$output = "<b> $first_name $last_name </b> <br> ".human_timestamp($created_on, 'd/m/Y')." <br> $note <br> <a href='#' target='_self' class='view-notes' data-id='$id'>Read more...</a>";

        return $output;
    }
}





if (!function_exists('dt_str_pad'))
{
    function dt_str_pad($number, $padding, $prefix = null, $hex = '#')
    {
        if (is_numeric($prefix))
            $prefix = $hex . $prefix;

        if ($number)
            return $prefix . str_pad($number, $padding, "0", STR_PAD_LEFT);

        return '';
    }
}




if (!function_exists('dt_icon'))
{
    function dt_icon($bool, $icon)
    {
        return ($bool) ? '<span class="' . $icon . '"></span>' : '';
    }
}




if (!function_exists('dt_ucfirst'))
{
    function dt_ucfirst($string)
    {
        return ucfirst($string);
    }
}




if (!function_exists('dt_ucwords'))
{
    function dt_ucwords($string)
    {
        return ucwords($string);
    }
}




if (!function_exists('dt_tools')) {
    function dt_tools($id, $controller, $methods = 'view|form|download|delete|delete_sa') {
        $controller = strtolower($controller);
        $methods    = explode('|', $methods);

        $output[] = '<div class="d-inline-flex">';
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-small-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';
                foreach($methods as $method) {
                    // defaults
                    $attr = [
                        'icon'        => '',
                        'title'       => '',
                        'target'      => '_self',
                        'class'       => 'dropdown-item'
                    ];

                    if ($method == 'delete_sa') {
                        $url = 'javascript:void(0)';
                    } else {
                        $url = $controller . '/'  . $method . '/' . $id;
                    }

                    switch ($method) {
                        case 'view':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye mr-50"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
                            $title           = 'View';
                            break;
						case 'view_notes':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle mr-50"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>';
                            $title           = 'View Notes';
                            break;
						case 'view_history':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye mr-50"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
                            $title           = 'View History';
                            break;
                        case 'form':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>';
                            $title           = 'Edit';
                            break;
                        case 'download':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download mr-50"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>';
                            $title           = 'Download';
                            //$attr['target']  = '_blank';
                            break;
                        case 'delete':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                            $title           = 'Delete';
							$module 		 = str_replace("-", " ", $controller);
                            $attr['onclick'] = "if (!confirm('Do you want to delete the $module ?')) return false;";
                            break;
						case 'edit':
							$icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>';
                            $title           = 'Edit';
                            break;
						case 'edit_modal':
							$icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>';
                            $title           = 'Edit';
                            break;
                        case 'delete_sa':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                            $title           = 'Delete';
                            $attr['data-id'] = $id;
                            $attr['class']   = "dropdown-item delete-sa";
                            $attr['aria-disabled']   = true;
                            break;
						case 'delete_sa_address':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                            $title           = 'Delete';
                            $attr['data-id'] = $id;
                            $attr['class']   = "dropdown-item delete-sa delete-address";
                            $attr['aria-disabled']   = true;
                            break;
                        default:
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download mr-50"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
                            break;
                    }

                    if ($method == 'delete_sa') {
                        $output[] = '<a href="javascript:void(0);" title="Delete" target="_self" class="dropdown-item delete-sa" data-id="' . $id . '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Delete</a>';
                    } elseif ($method == 'edit_modal') {
                        $output[] = '<a href="javascript:void(0);" title="Edit" target="_self" class="dropdown-item edit-modal" data-id="' . $id . '">'.$icon.' Edit</a>';
                    } elseif ($method == 'delete_sa_address') {
						$output[] = '<a href="#" title="Delete Address" target="_self" id="address-'.$id.'" class="dropdown-item delete-address" data-id="' . $id . '">'.$icon.' Delete</a>';
                    } elseif ($method == 'view_notes') {
						$output[] = '<a href="#" title="View Notes" target="_self" class="dropdown-item view-notes" data-id="' . $id . '">'.$icon.' View Notes</a>';
                    } elseif ($method == 'view_history') {
						$output[] = '<a href="#" title="View History" target="_self" class="dropdown-item view-history" data-id="' . $id . '">'.$icon.' View History</a>';
                    } else {
                        $output[] = anchor($url, $icon . ' ' . $title, $attr);
                    }

                }

            $output[] = '</div>';
        $output[] = '</div>';

        return implode('', $output);
    }
}




if (!function_exists('dt_tools_poc')) {
    function dt_tools_poc($id, $controller, $methods = 'edit|delete') {
        $controller = strtolower($controller);
        $methods    = explode('|', $methods);

		$output[] = '<div class="d-flex align-items-center justify-content-center">';
                foreach($methods as $method) {
					if ($method == 'delete') {
						$output[] = '<a href="javascript:void(0);" title="Delete" target="_self" class="delete-poc ml-1" data-id="' . $id . '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>';
					} elseif ($method == 'edit') {
						$output[] = '<a href="javascript:void(0);" title="Edit" target="_self" class="edit-poc" data-id="' . $id . '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></a>';
					}
                }
        $output[] = '</div>';

        return implode('', $output);
    }
}




if (!function_exists('dt_tools_template')) {
    function dt_tools_template($id, $url) {
		$methods = ['download', 'delete_sa'];
        $output[] = '<div class="d-flex align-items-center">';
                foreach($methods as $method) {
                    if ($method == 'delete_sa') {
                        $output[] = '<a href="javascript:void(0);" title="Delete" target="_self" class=" delete-template" data-id="' . $id . '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash fa-lg"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>';
                    } elseif ($method == 'download') {
                        $output[] = '<a href="'.site_url('application-form-template/download/'.$id).'" title="Edit" target="_self" class="mr-2" data-id="' . $id . '"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download fa-lg"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></a>';
                    } elseif ($method == 'view') {
						$output[] = '<a href="'.$url.'" target="_blank"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>';
                    }
                }
        $output[] = '</div>';

        return implode('', $output);
    }
}





if (!function_exists('dt_tools_application_form')) {
    function dt_tools_application_form($application_id, $follow_up_id) {
        $controller = 'application_form';
		$methods 	= [
			'View Application Form',
			'Edit Application Form',
			'Delete Application Form'
		];

		$icons = [
			'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye mr-50"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
			'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>',
			'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>',
		];

        $output[] = '<div class="d-inline-flex">';
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-small-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';
                foreach($methods as $key => $method) {
                    if ($method == 'View Application Form') {
                        $output[] = '<a href="'.site_url('application-form/view/'.$application_id).'" class="dropdown-item">'.$icons[$key].$method.'</a>';
                    } elseif ($method == 'Edit Application Form') {
						$output[] = '<a href="'.site_url('application-form/edit/'.$application_id).'" class="dropdown-item">'.$icons[$key].$method.'</a>';
                    } elseif ($method == 'Delete Application Form') {
                        $output[] = '<a href="javascript:void(0);" title="Delete Application Form" target="_self" class="dropdown-item delete-application-form" data-id="'.$application_id.'">'.$icons[$key].$method.'</a>';
                    }
                }
            $output[] = '</div>';
        $output[] = '</div>';
        return implode('', $output);
    }
}

if (!function_exists('dt_tools_application_inline')) {
    function dt_tools_application_inline($application_id, $follow_up_id) {
        $controller = 'application_form';
		$methods 	= [
			'View',
			'Download',
		];

		$icons = [
			'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye mr-50"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
			'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>',
			'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>',
		];

        $output[] = '<div class="d-inline-flex">';
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-small-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';

            $output[] = '</div>';
        $output[] = '</div>';

		$output[] = '<div class="d-flex align-items-end justify-content-end">';
		foreach($methods as $key => $method) {
			if ($method == 'View') {
				$output[] = '<a href="'.site_url('application-form/view/'.$application_id).'" class="dropdown-item">'.$icons[$key].$method.'</a>';
			} elseif ($method == 'Edit Application Form') {
				$output[] = '<a href="'.site_url('application-form/edit/'.$application_id).'" class="dropdown-item">'.$icons[$key].$method.'</a>';
			} elseif ($method == 'Delete Application Form') {
				$output[] = '<a href="javascript:void(0);" title="Delete Application Form" target="_self" class="dropdown-item delete-application-form" data-id="'.$application_id.'">'.$icons[$key].$method.'</a>';
			}
		}
        $output[] = '</div>';

        return implode('', $output);
    }
}









if (!function_exists('dt_tools_quotation')) {
    function dt_tools_quotation($id, $status, $methods = 'edit|delete_sa') {
        $methods    = explode('|', $methods);
        $controller = 'quotation';

        $output[] = '<div class="d-inline-flex">';
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';
                foreach($methods as $method) {

					switch ($method) {
						case 'view_notes':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle mr-50"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>';
                            $title           = 'View Notes';
                            break;
						case 'view_history':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-feather"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"></path><line x1="16" y1="8" x2="2" y2="22"></line><line x1="17.5" y1="15" x2="9" y2="15"></line></svg>';
                            $title           = 'View History';
                            break;
						case 'view_memo':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>';
                            $title           = 'View Notes';
                            break;
						case 'view_invoice':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>							';
                            $title           = 'View Notes';
                            break;
						case 'send_email':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>';
                            $title           = 'Edit';
                            break;
						case 'edit':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>';
                            $title           = 'Edit';
                            break;
                        case 'delete_sa':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                            $title           = 'Delete';
                            $attr['data-id'] = $id;
                            $attr['class']   = "dropdown-item delete-sa";
                            $attr['aria-disabled']   = true;
                            break;
                        default:
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download mr-50"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
                            break;
                    }

                    if ($method == 'edit') {
						if (in_array($status, constant('QUOTATION_STATUS_ALLOWED_TO_EDIT'))) {
							$output[] = '<a href="'.site_url('quotation/edit/'.$id).'" title="Edit" target="_self" class="dropdown-item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg> Edit</a>';
						} else {
							$ouptut[] = '';
						}
                    } elseif ($method == 'delete_sa') {
                        $output[] = '<a href="javascript:void(0);" title="Delete" target="_self" class="dropdown-item delete-sa" data-id="' . $id . '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Delete</a>';
                    } elseif ($method == 'view_notes') {
						$output[] = '<a href="#" title="View Notes" target="_self" class="dropdown-item view-notes" data-id="' . $id . '">'.$icon.' View Notes</a>';
                    } elseif ($method == 'view_history') {
						$output[] = '<a href="#" title="View History" target="_self" class="dropdown-item view-history" data-id="' . $id . '">'.$icon.' View History</a>';
                    } elseif ($method == 'view_memo') {
						$output[] = '<a href="#" title="View Memo" target="_self" class="dropdown-item view-memo" data-id="' . $id . '">'.$icon.' View Memo</a>';
                    } elseif ($method == 'view_invoice') {
						$output[] = '<a href="#" title="View Notes" target="_self" class="dropdown-item view-invoice" data-id="' . $id . '">'.$icon.' View Invoice</a>';
                    }

                }

            $output[] = '</div>';
        $output[] = '</div>';

        return implode('', $output);
    }
}





if (!function_exists('dt_tools_address')) {
    function dt_tools_address($id, $client_id, $primary, $methods = 'edit|delete') {
        $methods    = explode('|', $methods);
		$output[] = '<div class="d-flex align-items-end justify-content-end">';
		if ($primary == '1') {
			$output[] = '<span class="badge badge-light text-success mr-1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Primary Address</span>';
		} else {
			$output[] = '<a href="#" class="btn-change-primary-address mr-1" data-client-id="'.$client_id.'" data-address-id="'.$id.'"><span class="badge badge-white border-dark text-dark">Set as Primary Address</span></a>';
		}
		$output[] = '';
        foreach($methods as $method) {
			if ($method == 'delete') {
                $output[] = '<a href="javascript:void(0);" title="Delete" target="_self" class="delete-address" data-id="' . $id . '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> </a>';
            } elseif ($method == 'edit') {
                $output[] = '<a href="javascript:void(0);" title="Edit" target="_self" class="edit-address mr-1" data-id="' . $id . '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg> </a>';
            }
        }
        $output[] = '</div>';

        return implode('', $output);
    }
}






if (!function_exists('dt_check')) {
    function dt_check($bool) {
        if ((bool) $bool) {
            return '<span class="text-success fa fa-fw fa-check-circle"></span>';
        }

        return '<span class="text-danger fa fa-fw fa-times-circle"></span>';
    }
}




if (!function_exists('dt_anchor_tel')) {
    function dt_anchor_tel($tel, $title = null) {
        if ($tel) {
            $title = $title ?: $tel;
            return '<a href="tel:' . $tel . '">' . $title . '</a>';
        } else {
            return '-';
        }
    }
}


if (!function_exists('dt_anchor_mailto')) {
    function dt_anchor_mailto($email, $title = null) {
        if ($email) {
            $title = ($title) ?: $email;
            return '<a href="mailto:' . $email . '">' . $title . '</a>';
        } else {
            return '-';
        }
    }
}


if (!function_exists('dt_shorten_string')) {
    function dt_shorten_string($text, $max_length = null) {
        if ($text) {
            if (strlen($text) > $max_length) {
                return substr($text, 0, $max_length) . '...';
            } else {
                return $text;
            }
        } else {
            return '';
        }
    }
}



if (!function_exists('dt_quote_number'))
{
    function dt_quote_number($id, $quote_number, $controller)
    {
        if ($id) {
            return anchor($controller . '/view/' . $id, $quote_number);
        } else {
            return '-';
        }
    }
}


if (!function_exists('dt_invoice_number'))
{
    function dt_invoice_number($id, $invoice_number, $status, $request_status)
    {
		if (!$invoice_number) {
			$invoice_number = '-';
		}
		// if ($request_status != 'Approved') {
		// 	$status = 'Draft';
		// }
        if ($id) {
            return '<p><b class="text-danger">'.anchor('invoice/view/' . $id, $invoice_number).'</b></p>'.invoice_status_badge($status);
        } else {
            return '-';
        }
    }
}



if (!function_exists('dt_name'))
{
    function dt_name($id, $name, $controller)
    {
        if ($id) {
            return anchor($controller . '/view/' . $id, $name);
        } else {
            return '-';
        }
    }
}




if (!function_exists('dt_quote_status'))
{
    function dt_quote_status($status)
    {
        if ($status) {
            return quotation_status_badge($status);
        } else {
            return '-';
        }
    }
}




if (!function_exists('dt_client_status'))
{
    function dt_client_status($status)
    {
        if ($status) {
            return client_status_badge($status);
        } else {
            return '-';
        }
    }
}




if (!function_exists('dt_invoice_status'))
{
    function dt_invoice_status($status)
    {
        if ($status) {
            return invoice_status_badge($status);
        } else {
            return '-';
        }
    }
}




if (!function_exists('dt_name_with_group'))
{
    function dt_name_with_group($id, $user_name, $group_name, $controller)
    {
        return anchor($controller . '/' . $id, $user_name) . '<p class="m-b-none small">'. $group_name .'</p>';
    }
}




// combine and return first tel no & second tel no
if (!function_exists('dt_combine_phone'))
{
    function dt_combine_phone($tel_1, $tel_2)
    {
        $tel_no = $tel_1;

        if ($tel_2 > 0) {
            $tel_no = $tel_no . '<br>' . $tel_2;
        }

        return $tel_no;
    }
}



// combine and return first and last user name
if (!function_exists('dt_combine_user_name'))
{
    function dt_combine_user_name($first_name, $last_name)
    {
		return $first_name . ' ' . $last_name;
    }
}



if (!function_exists('dt_leading_zero'))
{
    function dt_leading_zero($number, $digits = 4)
    {
		return leading_zero($number, $digits);
    }
}



if (!function_exists('dt_application_dates'))
{
    function dt_application_dates($send_date, $receive_date)
    {
		if ($send_date || $receive_date) {
			return '<p>Sent <br> '.$send_date.' <br><br> Receive <br> '.$receive_date.'</p>';
		}
		return '-';
    }
}


if (!function_exists('dt_send_quotation_status'))
{
    function dt_send_quotation_status($status)
    {
        if ($status) {
            return send_quotation_status_badge($status);
        } else {
            return '-';
        }
    }
}


if (!function_exists('dt_application_form_standard'))
{
    function dt_application_form_standard($standard)
    {
		if (substr($standard, 0, 1) == '[') {
			$result = '';
			foreach (json_decode($standard) as $scheme) {
				$result .= "-  $scheme <br>";
			}
			return $result;
		} else {
			return "-  $standard";
		}
    }
}


if (!function_exists('dt_quotation_certification_scheme'))
{
    function dt_quotation_certification_scheme($certification_scheme, $accreditation, $training_type)
    {
		$CI =& get_instance();
		if ($certification_scheme) {
			if ($certification_scheme) {
				$result = '';

				$accreditation_id_arr = explode(',', $accreditation);
				foreach (explode(',', $certification_scheme) as $key => $scheme) {

					if(array_key_exists($key, $accreditation_id_arr)) {
						if($accreditation_id_arr[$key] == 0) {
							$accreditation_name = '';
						} else {
							$accreditation = $CI->db->where('id', $accreditation_id_arr[$key])->get('accreditation')->row();
              if ($accreditation) {
                $accreditation_name = "($accreditation->name)";
              } else {
                $accreditation_name = '';
              }
						}
					} else {
						$accreditation_name = '';
					}

					$scheme = $CI->db->where('id', $scheme)->get('certification_scheme')->row();

					if ($scheme) {
						$result .= "-  $scheme->name $accreditation_name <br>";
					};

				}
				return $result;
			}
		}

		if ($training_type) {
			if ($training_type) {
				$result = '';
				foreach (explode(',', $training_type) as $scheme) {
					$scheme = $CI->db->where('id', $scheme)->get('certification_scheme')->row();
					if ($scheme) {
						$result .= "-  $scheme->name <br>";
					};
				}
				return $result;
			}
		}

        return '';
    }
}


if (!function_exists('dt_application_follow_up'))
{
    function dt_application_follow_up($follow_up_id, $first_name, $last_name, $notes, $created_on)
    {
		if ($follow_up_id) {
			$CI =& get_instance();
			$CI->load->model('application_follow_up_attachment_model');
			$attachments = $CI->application_follow_up_attachment_model->get_many_by('follow_up_id', $follow_up_id);

			$text = '<p> <b> '.$first_name.' '.$last_name.' </b> | <small>'.human_timestamp($created_on, 'j M, g:i A').'</small> </p>';
			if($notes) {
				$text .= $notes.'<br><br>';
			}

			if($attachments) {
				$text .= '<p><a class="text-danger mr-50 view-attachment" data-id="'.$follow_up_id.'"><b>View Attachment</b></a> | <a href="'.site_url('application_follow_up/'.$follow_up_id.'/download_attachment').'" class="text-danger ml-50 download-attachment" data-id="'.$follow_up_id.'"><b>Download</b></a></p>';
			}
			return $text;
		}
		return '-';
    }
}


if (!function_exists('dt_tools_invoice')) {
    function dt_tools_invoice($id, $status) {
		$CI =& get_instance();
		$CI->load->model('invoice_model');
		$CI->invoice_model->set_soft_delete_false();
		$invoice = $CI->invoice_model->get($id);

        $controller = 'invoice';
        $methods    = explode('|', 'edit|view_notes|view_history|delete_sa');

        $output[] = '<div class="d-inline-flex">';
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';
                foreach($methods as $method) {

                    if ($method == 'delete_sa') {
                        $url = 'javascript:void(0)';
                    } else {
                        $url = $controller . '/'  . $method . '/' . $id;
                    }

					switch ($method) {
						case 'view_notes':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle mr-50"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>';
                            $title           = 'View Notes';
                            break;
						case 'view_history':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-feather mr-50"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"></path><line x1="16" y1="8" x2="2" y2="22"></line><line x1="17.5" y1="15" x2="9" y2="15"></line></svg>';
                            $title           = 'View History';
                            break;
						case 'edit':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>';
                            $title           = 'Edit';
                            break;
                        case 'delete_sa':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                            $title           = 'Delete';
                            $attr['data-id'] = $id;
                            $attr['class']   = "dropdown-item delete-sa";
                            $attr['aria-disabled']   = true;
                            break;
                        default:
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download mr-50"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
                            break;
                    }

                    if ($method == 'edit') {
						if ($status == 'Approved') {
							if($invoice->status == 'New' || $invoice->status == 'Due' || $invoice->status == 'Late' || $invoice->status == 'Partially Paid') {
								$output[] = '<a href="'.site_url('invoice/edit/'.$id).'" title="Edit" target="_self" class="dropdown-item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg> Edit</a>';
							}
						} else {
							$output[] = '';
						}
                    } elseif ($method == 'delete_sa') {
                        $output[] = '<a href="javascript:void(0);" title="Delete" target="_self" class="dropdown-item delete-sa" data-id="' . $id . '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Delete</a>';
                    } elseif ($method == 'view_notes') {
						$output[] = '<a href="#" title="View Notes" target="_self" class="dropdown-item view-notes" data-id="' . $id . '">'.$icon.' View Notes</a>';
                    } elseif ($method == 'view_history') {
						$output[] = '<a href="#" title="View History" target="_self" class="dropdown-item view-history" data-id="' . $id . '">'.$icon.' View History</a>';
                    }

                }

            $output[] = '</div>';
        $output[] = '</div>';

        return implode('', $output);
    }
}

if (!function_exists('dt_tools_detail_invoice')) {
    function dt_tools_detail_invoice($id, $status) {
		$methods 	= [
			'View Invoice',
			'Edit',
			'Download',
			// 'Cancel'
		];

        $output[] = '<div class="d-inline-flex">';
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';
                foreach($methods as $method) {
					switch ($method) {
						case 'View Invoice':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign mr-50"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
                            break;
						case 'Edit':
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 mr-50"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>';
                            break;
						case 'Download':
							$icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download mr-50"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>';
							break;
                        default:
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle mr-50"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
                            break;
                    }

                    if ($method == 'View Invoice') {
						$output[] = '<a href="'.site_url('invoice/view/'.$id).'" class="dropdown-item">' . $icon . ' View Invoice</a>';
                    } elseif ($method == 'Edit') {
						if ($status != 'Paid' && $status != 'Cancelled') {
							$output[] = '<a href="'.site_url('invoice/edit/'.$id).'" class="dropdown-item">' . $icon . ' Edit</a>';
						}
                    } elseif ($method == 'Download') {
						$output[] = '<a href="'.site_url('finance-summary/download-invoice/'.$id).'" target="_blank" class="dropdown-item" data-id="' . $id . '">'.$icon.' Download</a>';
                    } else {
						$output[] = '<a class="dropdown-item btn-cancel-invoice" data-id="' . $id . '">'.$icon.' Cancel</a>';
					}

                }

            $output[] = '</div>';
        $output[] = '</div>';

        return implode('', $output);
    }
}

if (!function_exists('dt_client_name_finance_summary'))
{
    function dt_client_name_finance_summary($client_name, $certification_scheme, $accreditation)
    {
		$CI =& get_instance();
		if ($certification_scheme) {
			$certification_scheme = explode(',', $certification_scheme);
			$accreditation = explode(',', $accreditation);

			$certification_scheme_name = '';
			$accreditation_name = '';
			foreach ($certification_scheme as $key => $scheme_id) {
				$scheme_name = $CI->db->where('id', $scheme_id)->get('certification_scheme')->row('name');
				$acc_name = $CI->db->where('id', $accreditation[$key])->get('accreditation')->row('name');
				if ($acc_name) {
					$certification_scheme_name .= $scheme_name . '(' . $acc_name . '); ';
				} else {
					$certification_scheme_name .= $scheme_name;
				}
			}
			$html = '<h4 class="mt-1">' . $client_name . '</h4> <hr>';
			$html .= '<span class="text-dark-blue"> Certification Scheme </span> <br>';
			$html .= '<span> ' . $certification_scheme_name . ' </span> <br>';
		} else {
			$html = '<h4 class="mt-1">' . $client_name . '</h4>';
		}

		return $html;
    }
}

if (!function_exists('dt_invoices_by_quotation'))
{
    function dt_invoices_by_quotation($quotation_id, $column)
    {
		$CI =& get_instance();
		$CI->load->model('invoice_model');
		$CI->load->model('client_history_model');
		$invoices = $CI->invoice_model->get_many_by('quotation_id', $quotation_id);

		if ($column == 'invoice_number') {
			$invoice_numbers = '';
			if ($invoices) {
				foreach ($invoices as $invoice) {
					$invoice_numbers .= '<p><b class="text-danger">'.anchor('invoice/view/' . $invoice->id, $invoice->number).'</b></p>';
				}
			}
			return $invoice_numbers;
		}

		if ($column == 'invoice_type') {
			$invoice_types = '';
			if ($invoices) {
				foreach ($invoices as $invoice) {
					$invoice_types .= '<p>' . $invoice->invoice_type . '</p>';
				}
			}
			return $invoice_types;
		}

		if ($column == 'date_created') {
			$dates = '';
			if ($invoices) {
				foreach ($invoices as $invoice) {
					$dates .= '<p>' . human_timestamp($invoice->created_on, 'd/m/Y') . '</p>';
				}
			}
			return $dates;
		}

		if ($column == 'status') {
			$statuses = '';
			if ($invoices) {
				foreach ($invoices as $invoice) {
					if ($invoice->request_status != 'Approved') {
						$status = 'Draft';
					} else {
						$status = $invoice->status;
					}
					$statuses .= '<p>' . invoice_status_badge($status) . '</p>';
				}
			}
			return $statuses;
		}
    }
}

if (!function_exists('dt_tools_finance_summary')) {
    function dt_tools_finance_summary($quotation_id) {
		$methods = ['View Details', 'Generate Receipt', 'View History'];

        $output[] = '<div class="d-inline-flex">';
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg style="width:24px; height: 24px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';
                foreach($methods as $method) {

					switch ($method) {
						case 'View Details':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 feather feather-arrow-right-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg>';
                            break;
						case 'Generate Receipt':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>';
                            break;
						case 'View History':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 feather feather-feather"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"></path><line x1="16" y1="8" x2="2" y2="22"></line><line x1="17.5" y1="15" x2="9" y2="15"></line></svg>';
                            break;
                        default:
                            $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-1 feather feather-download mr-50"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
                            break;
                    }

                    if ($method == 'View Details') {
						$output[] = '<a href="'.site_url('finance-summary/view/'.$quotation_id).'" class="dropdown-item"> ' . $icon . ' View Details</a>';
                    } elseif ($method == 'Generate Receipt') {
						$output[] = '<a href="#" class="dropdown-item generate-receipt" data-url="'.site_url('invoice/generate-receipt/'.$quotation_id).'" data-id="' . $quotation_id . '"> ' . $icon . ' Generate Receipt</a>';
                    } elseif ($method == 'View History') {
						$output[] = '<a href="#" class="dropdown-item view-history" data-id="' . $quotation_id . '">'.$icon.' View History</a>';
                    }
                }

            $output[] = '</div>';
			$output[] = '<a href="'.site_url('finance-summary/view/'.$quotation_id).'"><svg style="width:24px; height: 24px;" viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 feather feather-arrow-right-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg></a>';
        $output[] = '</div>';

        return implode('', $output);
    }
}

if (!function_exists('dt_tools_receipt')) {
    function dt_tools_receipt($receipt_id, $receipt_status) {
        $output[] = '<div class="d-inline-flex">';
        $output[] = '<a href="#" class="view-detail-receipt" data-id="'.$receipt_id.'"><svg style="width:24px; height: 24px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye mr-50"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        if ($receipt_status != 'Cancelled') {
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg style="width:24px; height: 24px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';
            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 feather feather-arrow-right-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg>';
            $output[] = '<a href="#" class="dropdown-item btn-cancel-receipt" data-id="' . $receipt_id . '">'.$icon.' Cancel</a>';
            $output[] = '</div>';
        }
        $output[] = '</div>';
        return implode('', $output);
    }
}


if (!function_exists('dt_invoice_by_receipt'))
{
    function dt_invoice_by_receipt($receipt_id, $column)
    {
		$CI =& get_instance();
		if ($column == 'invoice_number') {
			$invoice_numbers = '';
			$invoices = $CI->db->select('i.number')
					->from('invoice i')
					->join('detail_receipt dr', 'dr.invoice_id = i.id')
					->where('dr.receipt_id', $receipt_id)->get()->result();
			if ($invoices) {
				foreach ($invoices as $invoice) {
					$invoice_numbers .= '<p>'.$invoice->number.'</p>';
				}
			}
			return $invoice_numbers;
		}

		// if ($column == 'invoice_type') {
		// 	$invoice_types = '';
		// 	$invoices = $CI->db->where('quotation_id', $quotation_id)->get('invoice')->result();
		// 	if ($invoices) {
		// 		foreach ($invoices as $invoice) {
		// 			$invoice_types .= '<p>' . $invoice->invoice_type . '</p>';
		// 		}
		// 	}
		// 	return $invoice_types;
		// }

		// if ($column == 'date_created') {
		// 	$dates = '';
		// 	$invoices = $CI->db->where('quotation_id', $quotation_id)->get('invoice')->result();
		// 	if ($invoices) {
		// 		foreach ($invoices as $invoice) {
		// 			$dates .= '<p>' . human_timestamp($invoice->created_on, 'd/m/Y') . '</p>';
		// 		}
		// 	}
		// 	return $dates;
		// }

		if ($column == 'invoice_status') {
			$statuses = '';
			$detail_receipt = $CI->db->select('dr.invoice_status')->from('detail_receipt dr')->where('dr.receipt_id', $receipt_id)->get()->result();
			if ($detail_receipt) {
				foreach ($detail_receipt as $receipt) {
					$statuses .= '<p>' . invoice_status_badge($receipt->invoice_status) . '</p>';
				}
			}
			return $statuses;
		}
    }
}

if (!function_exists('dt_format_receipt_invoice_number')) {
	function dt_format_receipt_invoice_number($combined_invoice_number) {
		$invoice_numbers = '';
		if ($combined_invoice_number) {
			$combined_invoice_number_arr = explode(',', $combined_invoice_number);
			foreach ($combined_invoice_number_arr as $invoice_number) {
				$invoice_numbers .= '<p>'.$invoice_number.'</p>';
			}
		}
		return $invoice_numbers;
	}
}

if (!function_exists('dt_format_receipt_invoice_status')) {
	function dt_format_receipt_invoice_status($combined_invoice_status) {
		$statuses = '';
		if ($combined_invoice_status) {
			$combined_invoice_status_arr = explode(',', $combined_invoice_status);
			foreach ($combined_invoice_status_arr as $status) {
				$statuses .= '<p>' . invoice_status_badge($status) . '</p>';
			}
		}
		return $statuses;
	}
}




if (!function_exists('dt_client_application_form')) {
	function dt_client_application_form($form_key) {
		if($form_key) {
			$CI =& get_instance();
			$CI->load->model('application_form_model');
			$application_form = $CI->db->where('number', $form_key)->or_where('client_name', $form_key)->get('application_form')->row();
			if ($application_form) {
				return '<a href="'.site_url('application-form/view/'.$application_form->id).'">'.$application_form->number.'</a>';
			}
			return '';
		}
		return '';
	}

}

if(!function_exists('dt_tools_client_application_form')) {
	function dt_tools_client_application_form($form_id) {
		$output[] = '<div class="d-inline-flex">';
		$output[] = '<a href="#" class="view-detail-receipt" data-id="'.$form_id.'"><svg style="width:24px; height: 24px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file mr-1"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>';
        $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg style="width:24px; height: 24px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></a>';
        $output[] = '</div>';

        return implode('', $output);
	}
}

if(!function_exists('dt_quotation_client_name')) {
	function dt_quotation_client_name($quotation_id, $client_name) {
		$CI =& get_instance();
		$CI->load->model('quotation_model');
		$CI->load->model('invoice_model');
		$quotation = $CI->quotation_model->get($quotation_id);
		$invoice = $CI->invoice_model->get_by(['quotation_id' => $quotation_id, '(request_status = "Approved" OR request_status = "Pending Update")']);
		if($quotation->status == 'Confirmed' && !$invoice) {
			return '<h6 class="text-orange">'.$client_name.'</h6>';
		}
		return '<h6>'.$client_name.'</h6>';
	}
}

if(!function_exists('dt_format_invoice_number_by_quotation')) {
	function dt_format_invoice_number_by_quotation($combined_invoice_id, $combined_invoice_number) {
		$arr_invoice_id = explode(', ', $combined_invoice_id);
		$arr_invoice_number = explode(', ', $combined_invoice_number);
		$invoice_numbers = '';
		foreach ($arr_invoice_id as $key => $invoice_id) {
			$invoice_numbers .= '<p><b class="text-danger">'.anchor('invoice/view/' . $invoice_id, $arr_invoice_number[$key]).'</b></p>';
		}
		return $invoice_numbers;
	}
}

if(!function_exists('dt_format_invoice_type_by_quotation')) {
	function dt_format_invoice_type_by_quotation($combined_invoice_type) {
		$invoice_types = '';
		$arr_invoice_type = explode(', ', $combined_invoice_type);
		foreach ($arr_invoice_type as $key => $invoice_type) {
			$invoice_types .= '<p>' . $invoice_type . '</p>';
		}
		return $invoice_types;
	}
}

if(!function_exists('dt_format_invoice_status_by_quotation')) {
	function dt_format_invoice_status_by_quotation($combined_invoice_status) {
		$statuses = '';
		$arr_invoice_status = explode(', ', $combined_invoice_status);
		foreach ($arr_invoice_status as $invoice_status) {
			if($invoice_status) {
				$statuses .= '<p>' . invoice_status_badge($invoice_status) . '</p>';
			}
		}
		return $statuses;
	}
}

if(!function_exists('dt_format_invoice_date_created_by_quotation')) {
	function dt_format_invoice_date_created_by_quotation($combined_invoice_date_created) {
		$dates = '';
		$arr_invoice_date_created = explode(', ', $combined_invoice_date_created);
		foreach ($arr_invoice_date_created as $created_on) {
			$dates .= '<p>' . human_timestamp($created_on, 'd/m/Y') . '</p>';
		}
		return $dates;
	}
}

if (!function_exists('dt_tools_user')) {
    function dt_tools_user($id, $controller, $methods = 'view|form|download|delete|delete_sa') {
        $controller = strtolower($controller);
        $methods    = explode('|', $methods);

        $output[] = '<div class="d-inline-flex">';
            $output[] = '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-small-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></a>';
            $output[] = '<div class="dropdown-menu dropdown-menu-right">';
                foreach($methods as $method) {
                    // defaults
                    $attr = [
                        'icon'        => '',
                        'title'       => '',
                        'target'      => '_self',
                        'class'       => 'dropdown-item'
                    ];

                    if ($method == 'delete_sa') {
                        $url = 'javascript:void(0)';
                    } else {
                        $url = $controller . '/'  . $method . '/' . $id;
                    }

                    switch ($method) {
                        case 'delete':
                            $CI =& get_instance();
                            $logged_in_user_id = $CI->session->get_userdata()['user_id'];
                            if ($id != $logged_in_user_id) {
                              $icon            = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                              $title           = 'Delete';
                              $module 		 = str_replace("-", " ", $controller);
                              $attr['onclick'] = "if (!confirm('Do you want to delete the $module ?')) return false;";
                              $output[] = anchor($url, $icon . ' ' . $title, $attr);
                            }
                            break;
                        case 'edit':
                            $icon            = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>';
                            $title           = 'Edit';
                            $output[] = anchor($url, $icon . ' ' . $title, $attr);
                            break;
                        default:
                            break;
                    }
                }

            $output[] = '</div>';
        $output[] = '</div>';

        return implode('', $output);
    }
}

if (!function_exists('dt_due_date'))
{
    function dt_due_date($audit_fixed_date)
    {
		// Create a DateTime object from the original date
		$date = new DateTime($audit_fixed_date);

		// Add 30 days to the date
		$date->add(new DateInterval('P30D'));

		// Get the result in the same format
		$due_date = $date->format('d/m/Y');
		return $due_date;
    }
}

if (!function_exists('dt_format_receipt_status')) {
	function dt_format_receipt_status($receipt_status) {
        return '<p>' . receipt_status_badge($receipt_status) . '</p>';
	}
}
