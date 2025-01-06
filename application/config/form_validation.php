<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['error_prefix'] = '<li><span class="fa fa-li fa-fw fa-times"></span> ';
$config['error_suffix'] = '</li>';

$config = array(
	'Quotation/add_note' => array(
        array(
            'field' => 'quotation_id',
            'label' => 'Quotation Id',
            'rules' => 'required'
        ),
        array(
            'field' => 'note',
            'label' => 'Note',
            'rules' => 'required'
        ),
    ),


	'Application_Form/create' => array(
		[
			'field' => 'client_name',
			'label' => 'Client Name',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
            ),
		],
		[
			'field' => 'standard[]',
			'label' => 'Certification Scheme',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please select certification scheme",
            ),
		],
		[
			'field' => 'send_quotation_status',
			'label' => 'Status',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please select a status",
            ),
		],
		[
			'field' => 'send_date',
			'label' => 'Send Date',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please select send date",
            ),
		],
	),

	'Application_Form/edit' => array(
		[
			'field' => 'client_name',
			'label' => 'Client Name',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
            ),
		],
		[
			'field' => 'standard[]',
			'label' => 'Certification Scheme',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please select certification scheme",
            ),
		],
		[
			'field' => 'send_quotation_status',
			'label' => 'Status',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please select a status",
            ),
		],
		[
			'field' => 'send_date',
			'label' => 'Send Date',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please select send date",
            ),
		],
	),

	'Client/add_note' => array(
		[
			'field' => 'client_id',
			'label' => 'Client id',
			'rules' => 'trim|required',
		],
		[
			'field' => 'note',
			'label' => 'Note',
			'rules' => 'trim|required',
		],
	),

	'Address/store' => array(
		array(
			'field' => 'address_name',
			'label' => 'Name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'phone',
			'label' => 'Phone',
			'rules' => 'trim|required|min_length[8]',
		),
		array(
			'field' => 'fax',
			'label' => 'Fax',
			'rules' => 'trim|required|min_length[8]',
		),
		array(
			'field' => 'country',
			'label' => 'Country',
			'rules' => 'trim|required',
		),
		array(
			'field' => 'address',
			'label' => 'Address',
			'rules' => 'trim|required',
		),
		array(
			'field' => 'postal_code',
			'label' => 'Postal Code',
			'rules' => 'trim|required|min_length[5]',
		),
		array(
			'field' => 'total_employee',
			'label' => 'No of Employee',
			'rules' => 'trim|required',
		)
	),

	'Client/add_contact' => array(
		[
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required',
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required',
		],
		[
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'trim|required',
		],
		[
			'field' => 'position',
			'label' => 'Position',
			'rules' => 'trim|required',
		],
		[
			'field' => 'department',
			'label' => 'Department',
			'rules' => 'trim|required',
		],
		[
			'field' => 'phone',
			'label' => 'Phone',
			'rules' => 'trim|required',
		],
		[
			'field' => 'mobile',
			'label' => 'Mobile',
			'rules' => 'trim|required',
		],
		[
			'field' => 'fax',
			'label' => 'Fax',
			'rules' => 'trim|required',
		],
	),


	'User/edit' => array(
		[
			'field' => 'first_name',
			'label' => 'First Name',
			'rules' => 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
				'regex_match' => "Special character not allowed for field First Name",
            ),
		],
		[
			'field' => 'last_name',
			'label' => 'Last Name',
			'rules' => 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
				'regex_match' => "Special character not allowed for field Last Name",
            ),
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
            ),
		],
		[
			'field' => 'contact',
			'label' => 'Mobile',
			'rules' => 'trim|required|min_length[8]|max_length[8]',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
            ),
		],
		[
			'field' => 'group_id',
			'label' => 'User Role',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please select a %s",
            ),
		],
		[
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'trim|required|max_length[50]',
			'errors' => array(
                'required' => "Please select a %s",
            ),
		]
	),


	'User/create' => array(
		[
			'field' => 'first_name',
			'label' => 'First Name',
			'rules' => 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
				'regex_match' => "Special character not allowed for field First Name",
            ),
		],
		[
			'field' => 'last_name',
			'label' => 'Last Name',
			'rules' => 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
				'regex_match' => "Special character not allowed for field Last Name",
            ),
		],
		[
			'field' => 'contact',
			'label' => 'Mobile',
			'rules' => 'trim|required|min_length[8]|max_length[8]',
			'errors' => array(
                'required' => "Please enter the valid value for field %s",
            ),
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|is_unique[user.email]|valid_email',
			'errors' => array(
                'is_unique' => '%s already being used',
				'required' => "Please enter the valid value for field %s",
            ),
		],
		[
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required|min_length[8]|max_length[30]',
			'errors' => array(
                'min_length' => 'Please enter password with 8 digits',
				'required' => "Please enter the valid value for field %s",
            ),
		],
		[
			'field' => 'group_id',
			'label' => 'User Role',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please select a %s",
            ),
		],
	),


	'api/address/create' => array(
		array(
			'field' => 'name',
			'label' => 'Client Name',
			'rules' => 'trim'
		)
	),


	'Client/create' => array(
		array(
			'field' => 'client_id',
			'label' => 'Client Id',
			'rules' => 'trim|required'
		),
	),

	'Client/update' => array(
		[
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required|max_length[200]',
		],
		[
			'field' => 'uen',
			'label' => 'UEN',
			'rules' => 'trim|required|max_length[20]',
		],
		[
			'field' => 'website',
			'label' => 'Website',
			'rules' => 'trim|required|max_length[100]',
		],
		[
			'field' => 'phone',
			'label' => 'Phone',
			'rules' => 'trim|required|min_length[8]|max_length[8]',
		],
		[
			'field' => 'fax',
			'label' => 'Fax',
			'rules' => 'trim|required|min_length[8]|max_length[8]',
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|max_length[200]',
		],
	),

	'Contact/store' => array(
		[
			'field' => 'salutation',
			'label' => 'Salutation',
			'rules' => 'trim|required|max_length[20]',
		],
		[
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]',
			'errors' => array(
				'regex_match' => "Special characters are not allowed for Contact Name",
			),
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|max_length[200]|valid_email',
		],
		[
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'trim|required',
		],
		[
			'field' => 'mobile',
			'label' => 'Mobile',
			'rules' => 'trim|min_length[8]|max_length[8]',
		],
		[
			'field' => 'phone',
			'label' => 'Phone',
			'rules' => 'trim|min_length[8]|max_length[8]',
		],
		[
			'field' => 'fax',
			'label' => 'Fax',
			'rules' => 'trim|min_length[8]|max_length[8]',
		],
	),

	'Contact/update' => array(
		[
			'field' => 'salutation',
			'label' => 'Salutation',
			'rules' => 'trim|required|max_length[20]',
		],
		[
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]',
			'errors' => array(
				'regex_match' => "Special character not allowed for field Name",
			),
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|max_length[200]|valid_email',
		],
		[
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'trim|required',
		],
		[
			'field' => 'mobile',
			'label' => 'Mobile',
			'rules' => 'trim|min_length[8]',
		],
		[
			'field' => 'phone',
			'label' => 'Phone',
			'rules' => 'trim|min_length[8]',
		],
		[
			'field' => 'fax',
			'label' => 'Fax',
			'rules' => 'trim|min_length[8]',
		],
	),

	'api/invoice/add_note' => array(
		[
			'field' => 'note',
			'label' => 'Note',
			'rules' => 'required',
		],
	),

	'api/invoice/update_audit_fixed_date' => array(
		[
			'field' => 'invoice_id',
			'label' => 'Invoice',
			'rules' => 'required',
		],
		[
			'field' => 'audit_fixed_date',
			'label' => 'New Audit Fixed Date',
			'rules' => 'required',
		],
	),

	'api/invoice/update_follow_up_date' => array(
		[
			'field' => 'invoice_id',
			'label' => 'Invoice',
			'rules' => 'required',
		],
		[
			'field' => 'follow_up_date',
			'label' => 'New Follow Up Date',
			'rules' => 'required',
		],
	),

	'Invoice/update' => array(
		[
			'field' => 'invoice_date',
			'label' => 'Invoice Date',
			'rules' => 'required',
		],
		[
			'field' => 'invoice_type',
			'label' => 'Invoice Type',
			'rules' => 'required',
		],
		[
			'field' => 'amount',
			'label' => 'Amount',
			'rules' => 'required|greater_than[0]',
		],
	),

	'Invoice/store' => array(
		[
			'field' => 'invoice_date',
			'label' => 'Invoice Date',
			'rules' => 'required',
		],
		[
			'field' => 'invoice_type',
			'label' => 'Invoice Type',
			'rules' => 'required',
		],
		[
			'field' => 'amount',
			'label' => 'Amount',
			'rules' => 'required|greater_than[0]',
		],
		[
			'field' => 'contact_history_id',
			'label' => 'Contact',
			'rules' => 'required',
			'errors' => array(
                'required' => "Please create or select a contact",
            ),
		],
	),

	'Invoice/generate_receipt' => array(
		[
			'field' => 'payment_method',
			'label' => 'Payment Method',
			'rules' => 'required',
		],
		[
			'field' => 'paid_amount',
			'label' => 'Paid Amount',
			'rules' => 'required',
		],
	),

	'api/quotation/update_follow_up_date' => array(
		[
			'field' => 'quotation_id',
			'label' => 'Quotation',
			'rules' => 'required',
		],
		[
			'field' => 'follow_up_date',
			'label' => 'New Follow Up Date',
			'rules' => 'required',
		],
	),

	'api/contact/update' => array(
		[
			'field' => 'salutation',
			'label' => 'Salutation',
			'rules' => 'trim|required|max_length[20]',
		],
		[
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]',
			'errors' => array(
				'regex_match' => "Special character not allowed for field Name",
			),
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|max_length[200]|valid_email',
		],
		[
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'trim|required',
		],
		[
			'field' => 'mobile',
			'label' => 'Mobile',
			'rules' => 'trim|min_length[8]',
		],
		[
			'field' => 'phone',
			'label' => 'Phone',
			'rules' => 'trim|min_length[8]',
		],
		[
			'field' => 'fax',
			'label' => 'Fax',
			'rules' => 'trim|min_length[8]',
		],
	),

	'api/contact/create' => array(
		[
			'field' => 'salutation',
			'label' => 'Salutation',
			'rules' => 'trim|required|max_length[20]',
		],
		[
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]',
			'errors' => array(
				'regex_match' => "Special characters are not allowed for Contact Name",
			),
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|max_length[200]|valid_email',
		],
		[
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'trim|required',
		],
		[
			'field' => 'mobile',
			'label' => 'Mobile',
			'rules' => 'trim|min_length[8]|max_length[8]',
		],
		[
			'field' => 'phone',
			'label' => 'Phone',
			'rules' => 'trim|min_length[8]|max_length[8]',
		],
		[
			'field' => 'fax',
			'label' => 'Fax',
			'rules' => 'trim|min_length[8]|max_length[8]',
		],
	),

	'api/accreditation/validate_create' => array(
		[
			'field' => 'name',
			'label' => 'Accreditation',
			'rules' => 'trim|required|max_length[200]|callback_is_unique_create|callback_accreditation_name',
			'errors' => array(
					'is_unique_create' => "Accreditation already exists",
					'accreditation_name' => "Can't accept ONLY special characters",
					'required' => "Accreditation can't be empty",
			),
		],
	),

	'api/accreditation/validate_update' => array(
		[
			'field' => 'name',
			'label' => 'Accreditation',
			'rules' => 'trim|required|max_length[200]|callback_is_unique_update|callback_accreditation_name',
			'errors' => array(
					'is_unique_update' => "Accreditation already exists",
					'accreditation_name' => "Can't accept ONLY special characters",
					'required' => "Accreditation can't be empty",
			),
		],
	),

	'api/certification_scheme/validate_create' => array(
		[
			'field' => 'name',
			'label' => 'Certification Scheme',
			'rules' => 'trim|required|max_length[200]|callback_is_unique_create|callback_certification_scheme',
			'errors' => array(
					'is_unique_create' => "Certification scheme already exists",
					'certification_scheme' => "Can't accept ONLY special characters",
					'required' => "Certification scheme can't be empty",
			),
		],
	),

	'api/certification_scheme/validate_update' => array(
		[
			'field' => 'name',
			'label' => 'Certification scheme',
			'rules' => 'trim|required|max_length[200]|callback_is_unique_update|callback_certification_scheme',
			'errors' => array(
					'is_unique_update' => "Certification scheme already exists",
					'certification_scheme' => "Can't accept ONLY special characters",
					'required' => "Certification scheme can't be empty",
			),
		],
	),

	'Invoice/add_note' => array(
        array(
            'field' => 'note',
            'label' => 'Note',
            'rules' => 'required',
			'errors' => array(
				'required' => "Note can't be empty",
			),
        ),
    ),

	'auth/confirm_otp' => array(
        array(
            'field' => 'otp',
            'label' => 'otp',
            'rules' => 'required',
			'errors' => array(
				'required' => "OTP can't be empty",
			),
        ),
    ),

	'receipt/add_note' => array(
        array(
            'field' => 'note',
            'label' => 'Note',
            'rules' => 'required',
			'errors' => array(
				'required' => "Note can't be empty",
			),
        ),
    ),

);
