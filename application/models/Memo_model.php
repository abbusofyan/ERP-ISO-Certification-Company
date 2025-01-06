<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Memo_model extends WebimpModel
{
    protected $_table = 'memo';

	protected $before_create = ['_record_created'];

    protected $before_update = ['_record_updated'];

    public function __construct()
    {
        parent::__construct();
    }


	protected $belongs_to = [
		'quotation' => [
            'primary_key' => 'quotation_id',
            'model'       => 'quotation_model',
        ],
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];


	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}


	public function generate_new_memo_number() {
		$year = date('Y');

		$number = '0004';

		$last_memo = $this->order_by('id', 'DESC')->get_by([
			'FROM_UNIXTIME(created_on, "%Y") = '."$year"
		]);

		if ($last_memo) {
			$last_memo_number = explode('/', explode('-', $last_memo->number)[1])[0];
			$number = str_pad(intval($last_memo_number) + 1, strlen($last_memo_number), '0', STR_PAD_LEFT);
		}

		return 'MEMO-'.$number.'/'.date('Y');
	}


	protected function _record_updated($post)
	{
		$current_user = $this->session->userdata();

		$post['updated_by'] = $current_user['user_id'];
		$post['updated_on'] = time();

		return $post;
	}

}
