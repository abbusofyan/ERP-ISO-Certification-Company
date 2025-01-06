<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller']                        = 'Dashboard';
$route['404_override']                              = '';
$route['translate_uri_dashes']                      = FALSE;

$route['login']                                     = 'Auth/login';
$route['logout']                                    = 'Auth/logout';
$route['forgot']                                    = 'Auth/forgot';
$route['auth/reset/(:any)']                         = 'Auth/reset_password/$1';
$route['profile']                                   = 'Profile/form';
$route['password']                                  = 'Profile/password';

$route['user']                                      = 'User/index';
$route['user/create']	                            = 'User/create';
$route['user/role']	                            	= 'User/role';
$route['user/(:num)']                               = 'User/view/$1';
$route['user/(:num)/view']                          = 'User/view/$1';
$route['user/(:num)/edit']                          = 'User/edit/$1';
$route['user/delete/(:num)']                        = 'User/delete/$1';


$route['permission/store']                          = 'Permission/store';

$route['group/store']                          		= 'Group/store';
$route['group/(:num)/update']                 		= 'Group/update/$1';

$route['client']                                    = 'Client/index';
$route['client/create']	                            = 'Client/create';
$route['client/change_main_contact']                = 'Client/change_main_contact';
$route['client/view/(:num)']                        = 'Client/view/$1';
$route['client/edit/(:num)']                        = 'Client/edit/$1';
$route['client/update/(:num)']                      = 'Client/update/$1';
$route['client/export']                             = 'Client/export';
$route['client/add-note']                           = 'Client/add_note';

$route['address/(:num)/edit']                       = 'Address/edit/$1';
$route['address/store']		                        = 'Address/store';

$route['contact/update/(:num)']                     = 'Contact/update/$1';
$route['contact/store']		                        = 'Contact/store';

$route['certification-scheme']                      = 'Certification_Scheme/index';
$route['certification-scheme/store']                = 'Certification_Scheme/store';
$route['certification-scheme/update/(:num)']        = 'Certification_Scheme/update/$1';
$route['certification-scheme/delete/(:num)']        = 'Certification_Scheme/delete/$1';

$route['accreditation']                             = 'Accreditation/index';
$route['accreditation/update/(:num)']               = 'Accreditation/update/$1';
$route['accreditation/delete/(:num)']               = 'Accreditation/delete/$1';

$route['quotation/']	                            = 'Quotation/index';
$route['quotation/status/(:any)']                   = 'Quotation/index/$1';
$route['quotation/create']                          = 'Quotation/create';
$route['quotation/create_iso_quotation']            = 'Quotation/create_iso_quotation';
$route['quotation/create_bizsafe_quotation']        = 'Quotation/create_bizsafe_quotation';
$route['quotation/create_training_quotation']       = 'Quotation/create_training_quotation';
$route['quotation/add-note']                        = 'Quotation/add_note';
$route['quotation/edit/(:num)']                     = 'Quotation/edit/$1';
$route['quotation/view/(:num)']                     = 'Quotation/view/$1';
$route['quotation/update/(:num)']	                = 'Quotation/update/$1';
$route['quotation/export/(:num)']	                = 'Quotation/export/$1';
$route['quotation/confirm/(:num)']	                = 'Quotation/confirm/$1';


$route['memo/edit/(:num)']	                		= 'Memo/edit/$1';
$route['memo/update/(:num)']	               		= 'Memo/update/$1';

$route['notification/process/(:num)/(:any)']   		= 'Notification/process/$1/$2';
$route['notification/process/(:num)/(:any)']	 	= 'Notification/process/$1/$2';

$route['invoice/create']				            = 'Invoice/create';
$route['invoice/store']				           		= 'Invoice/store';
$route['invoice']					                = 'Invoice/index';
$route['invoice/view/(:num)']				        = 'Invoice/view/$1';
$route['invoice/update/(:num)']				        = 'Invoice/update/$1';
$route['invoice/approve/(:num)']				    = 'Invoice/approve/$1';
$route['invoice/decline/(:num)']				    = 'Invoice/decline/$1';
$route['invoice/add-note']					        = 'Invoice/add_note';
$route['invoice/add-attachment']					= 'Invoice/add_attachment';
$route['invoice/delete-attachment/(:num)']			= 'Invoice/delete_attachment/$1';
$route['invoice/generate-receipt/(:num)']	        = 'Invoice/generate_receipt/$1';
$route['invoice/delete/(:num)']				        = 'Invoice/delete/$1';
$route['invoice/view-pdf/(:num)']				    = 'Invoice/view_pdf/$1';
$route['invoice/download-attachment/(:num)']		= 'Invoice/download_attachment/$1';

$route['finance-summary']					        = 'Finance_Summary/index';
$route['finance-summary/view/(:num)']	        	= 'Finance_Summary/view/$1';
$route['finance-summary/download-invoice/(:num)']	= 'Finance_Summary/download_invoice/$1';

$route['application-form']                   		= 'Application_Form/index';
$route['application-form/create']                   = 'Application_Form/create';
$route['application-form/store']                   	= 'Application_Form/store';
$route['application-form/view/(:num)']				= 'Application_Form/view/$1';
$route['application-form/edit/(:num)'] 				= 'Application_Form/edit/$1';
$route['application-form/update/(:num)'] 			= 'Application_Form/update/$1';

$route['application-form-template']                 = 'Application_Form_Template/index';
$route['application-form-template/upload']          = 'Application_Form_Template/upload';
$route['application-form-template/download/(:num)'] = 'Application_Form_Template/download/$1';


$route['application_follow_up/(:num)/download_attachment'] = 'Application_Follow_Up/download_attachment/$1';
$route['application_follow_up/download_attachment_file/(:num)'] = 'Application_Follow_Up/download_attachment_file/$1';
$route['application_follow_up/store'] 				= 'Application_Follow_Up/store';

$route['api/client/get-notes']                      = 'api/client/get_notes';
$route['api/client/get-main-contact']               = 'api/client/get_main_contact';
$route['api/client/flag'] 				            = 'api/client/flag';
$route['api/client/fitler'] 				        = 'api/client/filter';
$route['api/client/get_by_status']			        = 'api/client/get_by_status';
$route['api/client/get_detail']				        = 'api/client/get_detail';
$route['api/client/get_history']				    = 'api/client/get_history';
$route['api/client/create']					        = 'api/client/create';

$route['api/contact/delete'] 				        = 'api/contact/delete';
$route['api/contact/get_by_client']  		        = 'api/contact/get_by_client';
$route['api/contact/edit']	 				        = 'api/contact/edit';
$route['api/contact/switch_main_contact']           = 'api/contact/switch_main_contact';

$route['api/address/switch_main_address']           = 'api/address/switch_main_address';
$route['api/address/create']			            = 'api/address/create';

$route['api/referrer/get_like']			            = 'api/referrer/get_like';

$route['api/application_form_template/get_by_id']   = 'api/application_form_template/get_by_id';
$route['api/application_form_template/delete']      = 'api/application_form_template/delete';

$route['api/application_follow_up/get_latest_attachment_by_id'] = 'api/application_follow_up/get_latest_attachment_by_id';
$route['api/application_follow_up/get_attachments'] = 'api/application_follow_up/get_attachments';
$route['api/application_follow_up/delete'] 			= 'api/application_follow_up/delete';

$route['api/application_form/get_like']	 			= 'api/application_form/get_like';
$route['api/application_form/delete'] 				= 'api/application_form/delete';

$route['api/group/get_permission']	 				= 'api/group/get_permission';
$route['api/group/delete']	 						= 'api/group/delete';

$route['api/certification_scheme/get']	 			= 'api/certification_scheme/get';

$route['api/accreditation/get']	 					= 'api/accreditation/get';

$route['api/memo/generate']		 					= 'api/memo/generate';
$route['api/memo/get']			 					= 'api/memo/get';

$route['api/invoice/add_note']	 					= 'api/invoice/add_note';

$route['receipt/add-note']					        = 'Receipt/add_note';
