<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_attachment_model extends WebimpModel
{
    protected $_table = 'invoice_attachment';

	protected $belongs_to = [
        'invoice' => [
            'primary_key' => 'invoice_id',
            'model'       => 'invoice_model',
        ],
		'file' => [
            'primary_key' => 'file_id',
            'model'       => 'file_model',
        ],
    ];


    public function __construct()
    {
        parent::__construct();
    }

}
