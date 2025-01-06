<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dt extends WebimpController
{
    protected $view = false;
    protected $helpers = ['datatables'];
    protected $models  = [
        'user',
    ];

    protected $current_user;

    public function __construct()
    {
        parent::__construct();

        if (!$this->user_model->logged_in())
            show_404();

        $this->load->library(['session', 'datatables']);
        $this->current_user = $this->session->userdata();
    }




    public function index()
    {
        show_404();
    }




    /**
     * All Users
     */
    public function user()
    {
        $this->datatables->select('user.id, user.first_name, user.last_name, user.email, user.contact, user.status, user.reference_id, group.description AS group_name')
                         ->from('user')
                         ->join('group', 'user.group_id = group.id')
                         ->where('user.deleted', '0')
                         // ->where('user.id !=' , $this->session->userdata()['user_id'])
                         ->edit_column('name', '$1', 'dt_name(id, name, user)')
                         ->add_column('tools', '$1', 'dt_tools_user(id, user, edit|delete)');

        $this->output->set_content_type('application/json')
                     ->set_status_header(200)
                     ->set_output($this->datatables->generate('json'));
    }



	public function auth_log()
    {
		$this->datatables->select('l.id, u.first_name, u.last_name, l.login_ip as ip, l.login_time as date, l.login_time, l.logout_time')
				->from('auth_log l')
				->join('user u', 'l.user_id = u.id')
				->edit_column('date', '$1', 'dt_timestamp_to_format(date, "M d, Y")')
				->edit_column('login_time', '$1', 'dt_timestamp_to_format(login_time, "M d, Y H:i")')
				->edit_column('logout_time', '$1', 'dt_timestamp_to_format(logout_time, "M d, Y H:i")')
				->where('u.deleted', '0');
		$this->output->set_content_type('application/json')
				->set_status_header(200)
				->set_output($this->datatables->generate('json'));
    }




    /**
     * All Clients
     */
    public function client()
    {
		$filter = $this->input->post('filter');

		$where = [
			$this->datatables->where('client.deleted', '0'),
			// $this->datatables->where('contact.primary', '1'),
			// $this->datatables->where('address.primary', '1')
		];

		if ($filter) {
			parse_str($filter, $filter_arr);
			array_shift($filter_arr);

			// filter status
			if (isset($filter_arr['status'])) {
				$filter_status = $filter_arr['status'];
				$where_status = '( ';
				foreach ($filter_status as $key => $status) {
					$where_status .= 'client.status' .' = '. "'$status'";
					if ($key < count($filter_status)-1) {
						$where_status .= ' OR ';
					}
				}
				$where_status .= ') ';
				array_push($where, $this->datatables->where($where_status));
			}

			if (isset($filter_arr['area'])) {
				$filter_area = $filter_arr['area'];
				$where_area = '( ';
				foreach ($filter_area as $key => $area) {
					$where_area .= 'LEFT(address.postal_code, 2) IN ' . '('.$area.')';
					if ($key < count($filter_area)-1) {
						$where_area .= ' OR ';
					}
				}
				$where_area .= ') ';
				array_push($where, $this->datatables->where('address.country', 'Singapore'));
				array_push($where, $this->datatables->where($where_area));
			}

			// filter flag
			if (isset($filter_arr['flagged'])) {
				$filter_flagged = $filter_arr['flagged'];
				$where_flagged = '( ';
				foreach ($filter_flagged as $key => $status) {
					$where_flagged .= 'client.flagged' .' = '. "'$status'";
					if ($key < count($filter_flagged)-1) {
						$where_flagged .= ' OR ';
					}
				}
				$where_flagged .= ') ';
				array_push($where, $this->datatables->where($where_flagged));
			}

			// filter year cerated
			if ($filter_arr['year_created']) {
				$year_created = $filter_arr['year_created'];
				array_push($where, $this->datatables->where('YEAR(FROM_UNIXTIME(client.created_on))', $year_created));
			}

			// filter date cerated
			if ($filter_arr['date_created_start'] && $filter_arr['date_created_end']) {
				$date_created_start = $filter_arr['date_created_start'];
				$date_created_end = $filter_arr['date_created_end'];

				array_push($where, $this->datatables->where('client.created_on >= unix_timestamp("'.$date_created_start.'")
  				AND client.created_on <= unix_timestamp("'.$date_created_end.'")'));
			}

		}


		$this->datatables->select('client.id,
									client.uen,
									client.status,
									address.address_2,
									address.postal_code,
									address.country,
									address.address,
									client.website,
									client.name,
									client.uen,
									client.flagged,
									contact.id as contact_id,
									contact.salutation,
									contact.name as contact_name,
									contact.email as contact_email,
									contact.mobile as contact_mobile,
									contact.phone as contact_phone,
									t.client_name_history,
                                    cc.combined_contact_name')
						 ->from('client')
						 ->join('contact', 'client.id = contact.client_id AND contact.primary = 1', 'LEFT')
						 ->join('address', 'client.id = address.client_id AND address.primary = 1', 'LEFT')
						 ->join("(SELECT
		                            c.id AS client_id,
		                            GROUP_CONCAT(DISTINCT ch.name ORDER BY ch.id ASC SEPARATOR ', ') AS client_name_history
		                          FROM
		                            client c
		                          JOIN
		                            client_history ch ON c.id = ch.client_id
		                          GROUP BY c.id) as t", 't.client_id = client.id')
                         ->join("(SELECT
 		                            client_id,
 		                            GROUP_CONCAT(DISTINCT contact.name ORDER BY contact.id ASC SEPARATOR ', ') AS combined_contact_name
 		                          FROM
 		                            contact
 		                          GROUP BY client_id) as cc", 'cc.client_id = client.id');
		$where;
		$this->datatables->edit_column('name', '$1', 'dt_name(id, name, client)')
						 ->edit_column('status', '$1', 'dt_client_status(status)')
                         ->edit_column('website', '$1', 'clean_url(website)')
						 ->add_column('total_branch', '$1', 'dt_total_branch(id)')
						 ->add_column('tools', '$1', 'dt_tools(id, client, view_notes|view_history|edit|delete_sa)');

		$this->output->set_content_type('application/json')
					 ->set_status_header(200)
					 ->set_output($this->datatables->generate('json'));
    }






	/**
	 * All Quotation
	 */
	public function quotation()
	{
		if (can(['read-quotation', 'read-client'])):
			$filter = $this->input->post('filter');
      		$status = $this->input->post('topBarStatus');
			$status = $this->input->post('status');
			$search_by_key = $this->input->post('search')['value'];
			$month = $this->input->post('month');
			$year = $this->input->post('year');

			$where = [
				$this->datatables->where('q.deleted', '0'),
			];

			if(!$filter && !$search_by_key && !$status) {
		        if ($month) {
		          array_push($where, $this->datatables->where('MONTH(FROM_UNIXTIME(q.created_on))', $month));
		        }
		        if ($year) {
		          array_push($where, $this->datatables->where('YEAR(FROM_UNIXTIME(q.created_on))', $year));
		        }
			}

			if ($status && $status != 'All') {
				array_push($where, $this->datatables->where('q.status', $status));
			}


			if ($filter) {
				parse_str($filter, $filter_arr);
				array_shift($filter_arr);
				// filter quote type

				if (isset($filter_arr['quote_type'])) {
					$filter_quote_type = $filter_arr['quote_type'];
					if (!in_array("All", $filter_quote_type)) {
						$where_quote_type = '( ';
						foreach ($filter_quote_type as $key => $quote_type) {
							$where_quote_type .= 'q.type' .' = '. "'$quote_type'";
							if ($key < count($filter_quote_type)-1) {
								$where_quote_type .= ' OR ';
							}
						}
						$where_quote_type .= ') ';
						array_push($where, $this->datatables->where($where_quote_type));
					}
				}


				// filter certification cycle
				if (isset($filter_arr['certification_cycle'])) {
					$filter_certification_cycle = $filter_arr['certification_cycle'];
					if (!in_array("All", $filter_certification_cycle)) {
						$where_certification_cycle = '( ';
						foreach ($filter_certification_cycle as $key => $cycle) {
							$where_certification_cycle .= 'q.certification_cycle' .' = '. "'$cycle'";
							if ($key < count($filter_certification_cycle)-1) {
								$where_certification_cycle .= ' OR ';
							}
						}
						$where_certification_cycle .= ') ';
						array_push($where, $this->datatables->where($where_certification_cycle));
					}
				}

				// filter accreditation
				if (isset($filter_arr['accreditation'])) {
					$filter_accreditation = $filter_arr['accreditation'];
					if (!in_array("All", $filter_accreditation)) {
						$where_accreditation = '( ';
						foreach ($filter_accreditation as $key => $acc) {
							$where_accreditation .= 'cacc.combined_accreditation' .' LIKE '. "'".'%'.$acc.'%'."'";
							if ($key < count($filter_accreditation)-1) {
								$where_accreditation .= ' OR ';
							}
						}
						$where_accreditation .= ') ';
						array_push($where, $this->datatables->where($where_accreditation));
					}
				}

				// filter quote status
				if (isset($filter_arr['quote_status'])) {
					$filter_quote_status = $filter_arr['quote_status'];
					if (!in_array("All", $filter_quote_status)) {
						$where_quote_status = '( ';
						foreach ($filter_quote_status as $key => $quote_status) {
							$where_quote_status .= 'q.status' .' = '. "'$quote_status'";
							if ($key < count($filter_quote_status)-1) {
								$where_quote_status .= ' OR ';
							}
						}
						$where_quote_status .= ') ';
						array_push($where, $this->datatables->where($where_quote_status));
					}
				}

				// filter client status
				if (isset($filter_arr['client_status'])) {
					$filter_client_status = $filter_arr['client_status'];
					if (!in_array("All", $filter_client_status)) {
						$where_client_status = '( ';
						foreach ($filter_client_status as $key => $client_status) {
							$where_client_status .= 'client.status' .' = '. "'$client_status'";
							if ($key < count($filter_client_status)-1) {
								$where_client_status .= ' OR ';
							}
						}
						$where_client_status .= ') ';
						array_push($where, $this->datatables->where($where_client_status));
					}
				}

				// filter flag
				if (isset($filter_arr['flagged'])) {
					$filter_flagged = $filter_arr['flagged'];
					if (!in_array("All", $filter_flagged)) {
						$where_flagged = '( ';
						foreach ($filter_flagged as $key => $flagged) {
							$where_flagged .= 'q.flagged' .' = '. "'$flagged'";
							if ($key < count($filter_flagged)-1) {
								$where_flagged .= ' OR ';
							}
						}
						$where_flagged .= ') ';
						array_push($where, $this->datatables->where($where_flagged));
					}
				}

				// filter memo
				if (isset($filter_arr['memo_generated'])) {
					$filter_memo = $filter_arr['memo_generated'];
					if (!in_array("All", $filter_memo)) {
						$where_memo = '( ';
						foreach ($filter_memo as $key => $memo) {
							if ($memo) {
								$where_memo .= 'm.quotation_id IS NOT NULL';
							} else {
								$where_memo .= 'm.quotation_id IS NULL';
							}
							if ($key < count($filter_memo)-1) {
								$where_memo .= ' OR ';
							}
						}
						$where_memo .= ') ';
						array_push($where, $this->datatables->where($where_memo));
					}
				}

				// filter date cerated
				if ($filter_arr['date_created_start'] && $filter_arr['date_created_end']) {
					$date_created_start = $filter_arr['date_created_start'];
					$date_created_end = $filter_arr['date_created_end'];

					array_push($where, $this->datatables->where('q.created_on >= unix_timestamp("'.$date_created_start.'")
					AND q.created_on <= unix_timestamp("'.$date_created_end.' 23:59:59")'));
				} elseif ($filter_arr['date_created_start'] && !$filter_arr['date_created_end']) {
          			$date_created_start = $filter_arr['date_created_start'];
					array_push($where, $this->datatables->where('q.created_on >= unix_timestamp("'.$date_created_start.'")'));
    			}

			}

			$this->datatables->select('q.id as id, q.number as quote_number, q.type as quote_type, q.quote_date, q.flagged, q.training_type, c.name as client_name, ch.name as contact_name, ch.mobile as contact_mobile, ch.phone as contact_phone, ch.email as contact_email, client.status as client_status, ah.address, c.uen, c.phone as client_phone, c.email as client_email, qn.note, qn.first_name, qn.last_name, qn.created_on as note_created_on, q.status as quote_status, q.certification_scheme, q.accreditation, q.created_on, q.confirmed_on, cc.name as certificate_cycle, t.client_name_history, ccs.combined_certification_scheme, cacc.combined_accreditation, ctrn.combined_training_type')
							 ->from('quotation q')
							 ->join('client_history c', 'q.client_history_id = c.id')
							 ->join('client', 'c.client_id = client.id')
							 ->join('address_history ah', 'q.address_history_id = ah.id')
							 ->join('contact_history ch', 'q.contact_history_id = ch.id')
							 ->join('certification_cycle cc', 'q.certification_cycle = cc.id', 'LEFT')
							 ->join('accreditation a', 'q.accreditation = a.id', 'LEFT')
							 ->join('certification_scheme cs', 'q.certification_scheme = cs.id', 'LEFT')
							//  ->join('quotation_notification notification', 'notification.quotation_id = q.id', 'LEFT')
							 ->join('(
								 	select qn.*, u.first_name, u.last_name from quotation_note qn
									join user u on qn.created_by = u.id
									where qn.id in (
									select MAX(qn.id) as id from quotation_note qn
											 join user u on qn.created_by = u.id
											 group by qn.quotation_id
									) order by qn.quotation_id
								 ) as qn', 'qn.quotation_id = q.id', 'left')
							 ->join('(
								 select memo.id, memo.quotation_id from memo
									where memo.id in (
									select MAX(memo.id) as id from memo
									group by memo.quotation_id
									) order by memo.quotation_id
								 ) as m', 'm.quotation_id = q.id', 'left')
               ->join("(SELECT
                          c.id AS client_id,
                          GROUP_CONCAT(DISTINCT ch.name ORDER BY ch.id ASC SEPARATOR ', ') AS client_name_history
                        FROM
                          client c
                        JOIN
                          client_history ch ON c.id = ch.client_id
                        GROUP BY c.id) as t", 't.client_id = client.id')
               ->join('(SELECT q.id as ccs_q_id, GROUP_CONCAT(cs.name) AS combined_certification_scheme
                         FROM quotation q
                         JOIN certification_scheme cs ON FIND_IN_SET(cs.id, q.certification_scheme)
                         GROUP BY q.id) as ccs', 'ccs.ccs_q_id = q.id', 'left')
               ->join('(SELECT q.id as cacc_q_id, GROUP_CONCAT(acc.name) AS combined_accreditation
                         FROM quotation q
                         JOIN accreditation acc ON FIND_IN_SET(acc.id, q.accreditation)
                         GROUP BY q.id) as cacc', 'cacc.cacc_q_id = q.id', 'left')
               ->join('(SELECT q.id as trn_q_id, GROUP_CONCAT(tt.name) AS combined_training_type
                         FROM quotation q
                         JOIN training_type tt ON FIND_IN_SET(tt.id, q.training_type)
                         GROUP BY q.id) as ctrn', 'ctrn.trn_q_id = q.id', 'left');
               $where;
			$this->datatables->add_column('quote_number_formatted', '$1', 'dt_quote_number(id, quote_number, quotation)')
							 ->edit_column('quote_status', '$1', 'dt_quote_status(quote_status)')
							 ->edit_column('client_status', '$1', 'dt_client_status(client_status)')
							 ->add_column('total_branch', '$1', 'dt_total_branch(id)')
							 ->edit_column('quote_date', '$1', 'dt_datestring_to_format(quote_date, "d/m/Y")')
               				 ->edit_column('created_on', '$1', 'dt_timestamp_to_format(created_on, "d/m/Y")')
							 ->edit_column('confirmed_on', '$1', 'dt_timestamp_to_format(confirmed_on, "d/m/Y")')
							 ->edit_column('note', '$1', 'dt_note(id, note, first_name, last_name, note_created_on)')
							 ->edit_column('client_name', '$1', 'dt_quotation_client_name(id, client_name)')
							 ->edit_column('certification_scheme', '$1', 'dt_quotation_certification_scheme(certification_scheme, accreditation, training_type)')
							 ->add_column('tools', '$1', 'dt_tools_quotation(id, quote_status, view_notes|view_history|view_memo|edit|delete_sa)');

			$this->output->set_content_type('application/json')
						 ->set_status_header(200)
						 ->set_output($this->datatables->generate('json'));
		else:
		 show_404();
		endif;
	}









	/**
	 * All Invoice
	 */
	public function invoice()
	{
		if (can(['read-quotation', 'read-invoice'])):
			$filter = $this->input->post('filter');
			$where = [
				$this->datatables->where('q.deleted', '0'),
				$this->datatables->where('i.deleted', '0'),
				$this->datatables->where('(i.request_status != "Declined")'),
			];

			if ($filter) {
				parse_str($filter, $filter_arr);
				array_shift($filter_arr);

				if (isset($filter_arr['client_id'])) {
					$filter_client_id = $filter_arr['client_id'];
					if (!in_array("All", $filter_client_id)) {
						$where_client_id = '( ';
						foreach ($filter_client_id as $key => $status) {
							$where_client_id .= 'client.id' .' = '. "'$status'";
							if ($key < count($filter_client_id)-1) {
								$where_client_id .= ' OR ';
							}
						}
						$where_client_id .= ') ';
						array_push($where, $this->datatables->where($where_client_id));
					}
				}

				if (isset($filter_arr['invoice_status'])) {
					$filter_invoice_status = $filter_arr['invoice_status'];
					if (!in_array("All", $filter_invoice_status)) {
						$where_invoice_status = '( ';
						foreach ($filter_invoice_status as $key => $status) {
							$where_invoice_status .= 'i.status' .' = '. "'$status'";
							if ($key < count($filter_invoice_status)-1) {
								$where_invoice_status .= ' OR ';
							}
						}
						$where_invoice_status .= ') ';
						array_push($where, $this->datatables->where($where_invoice_status));
					}
				}


				if (isset($filter_arr['certification_cycle'])) {
					$filter_certification_cycle = $filter_arr['certification_cycle'];
					if (!in_array("All", $filter_certification_cycle)) {
						$where_certification_cycle = '( ';
						foreach ($filter_certification_cycle as $key => $status) {
							$where_certification_cycle .= 'q.certification_cycle' .' = '. "'$status'";
							if ($key < count($filter_certification_cycle)-1) {
								$where_certification_cycle .= ' OR ';
							}
						}
						$where_certification_cycle .= ') ';
						array_push($where, $this->datatables->where($where_certification_cycle));
					}
				}

				if (isset($filter_arr['month_created'])) {
					$filter_month_created = $filter_arr['month_created'];
					if (!in_array("All", $filter_month_created)) {
						$where_month_created = '( ';
						foreach ($filter_month_created as $key => $month) {
							$where_month_created .= 'MONTH(FROM_UNIXTIME(i.created_on))' .' = '. "'$month'";
							if ($key < count($filter_month_created)-1) {
								$where_month_created .= ' OR ';
							}
						}
						$where_month_created .= ') ';
						array_push($where, $this->datatables->where($where_month_created));
					}
				}

				if (isset($filter_arr['year_created'])) {
					$filter_year_created = $filter_arr['year_created'];
					if (!in_array("All", $filter_year_created)) {
						$where_year_created = '( ';
						foreach ($filter_year_created as $key => $month) {
							$where_year_created .= 'YEAR(FROM_UNIXTIME(i.created_on))' .' = '. "'$month'";
							if ($key < count($filter_year_created)-1) {
								$where_year_created .= ' OR ';
							}
						}
						$where_year_created .= ') ';
						array_push($where, $this->datatables->where($where_year_created));
					}
				}

				// filter date cerated
				if ($filter_arr['date_created_start'] && $filter_arr['date_created_end']) {
					$date_created_start = $filter_arr['date_created_start'];
					$date_created_end = $filter_arr['date_created_end'];

					if ($date_created_start == $date_created_end) {
						array_push($where, $this->datatables->where('DATE(FROM_UNIXTIME(i.created_on)) = "'.$date_created_start.'"'));
					} else {
						array_push($where, $this->datatables->where('DATE(FROM_UNIXTIME(i.created_on)) >= "'.$date_created_start.'" AND DATE(FROM_UNIXTIME(i.created_on)) <= "'.$date_created_end.'"'));
					}

					// array_push($where, $this->datatables->where('i.created_on >= unix_timestamp("'.$date_created_start.'")
					// AND i.created_on <= unix_timestamp("'.$date_created_end.'")'));
				}

			}

			$this->datatables->select('i.id as id,
									i.number as invoice_number,
									i.invoice_date, i.request_status,
									c.name as client_name,
									c.uen,
									c.email as client_email,
									c.phone as client_phone,
									c.fax as client_fax,
									ad.address,
									ad.address_2,
									ad.country,
									ad.postal_code,
									q.certification_scheme,
									q.accreditation,
									q.training_type,
									i.invoice_type,
									i.amount,
									note.note,
									note.first_name,
									note.last_name,
									note.created_on as note_created_on,
									i.status as invoice_status,
									ccs.combined_certification_scheme,
									ct.name as contact_name,
									ct.email as contact_email,
									ct.phone as contact_phone,
									ct.mobile as contact_mobile,
									ct.fax as contact_fax,')
							 ->from('invoice i')
							 ->join('quotation q', 'i.quotation_id = q.id')
							 ->join('client_history c', 'i.client_history_id = c.id', 'LEFT')
							 ->join('contact_history ct', 'i.contact_history_id = ct.id', 'LEFT')
							 ->join('address_history ad', 'i.address_history_id = ad.id', 'LEFT')
							 ->join('client', 'c.client_id = client.id', 'LEFT')
               				 ->join('accreditation a', 'q.accreditation = a.id', 'LEFT')
							 ->join('certification_scheme cs', 'q.certification_scheme = cs.id', 'LEFT')
							 ->join('(SELECT q.id as ccs_q_id, GROUP_CONCAT(cs.name) AS combined_certification_scheme
			                           FROM quotation q
			                           JOIN certification_scheme cs ON FIND_IN_SET(cs.id, q.certification_scheme)
			                           GROUP BY q.id) as ccs', 'ccs.ccs_q_id = q.id', 'left')
							 ->join('(
								 select note.*, u.first_name, u.last_name from invoice_note note
									join user u on note.created_by = u.id
									where note.id in (
									select MAX(note.id) as id from invoice_note note
									  join user u on note.created_by = u.id
									  group by note.invoice_id
									) ORDER by note.invoice_id
								 ) as note', 'note.invoice_id = i.id', 'left');
							 $where;
			$this->datatables->add_column('invoice_number_formated', '$1', 'dt_invoice_number(id, invoice_number, invoice_status, request_status)')
							 // ->edit_column('note_created_on', '$1', 'dt_timestamp_to_format(note_created_on, "d/m/Y")')
							 ->edit_column('note', '$1', 'dt_note(id, note, first_name, last_name, note_created_on)')
							 ->add_column('amount_formatted', '$1', 'dt_money_format(amount)')
							 ->edit_column('certification_scheme', '$1', 'dt_quotation_certification_scheme(certification_scheme, accreditation, training_type)')
							 ->add_column('tools', '$1', 'dt_tools_invoice(id, request_status)');

			$this->output->set_content_type('application/json')
						 ->set_status_header(200)
						 ->set_output($this->datatables->generate('json'));
		else:
		 show_404();
		endif;
	}







	public function detail_invoice()
	{
		if (can(['read-quotation', 'read-invoice'])):
			$quotation_id = $this->input->post('quotation_id');
			$where = [
				$this->datatables->where('q.deleted', '0'),
				// $this->datatables->where('i.deleted', '0'),
        		$this->datatables->where('i.request_status', 'Approved'),
				$this->datatables->where('q.id', $quotation_id),
			];

			$this->datatables->select('i.id as id, i.number as invoice_number, i.audit_fixed_date, i.paid_date, i.invoice_date, i.request_status, i.invoice_type, i.amount, i.status as invoice_status')
							 ->from('invoice i')
							 ->join('quotation q', 'i.quotation_id = q.id');
			$this->datatables->add_column('tools', '$1', 'dt_tools_detail_invoice(id, invoice_status)');
			$this->datatables->add_column('invoice_status', '$1', 'dt_invoice_status(invoice_status)')
                             ->edit_column('paid_date', '$1', 'human_date(paid_date, "d/m/Y")')
							 ->edit_column('invoice_date', '$1', 'human_date(invoice_date, "d/m/Y")')
							 ->add_column('due_date', '$1', 'dt_due_date(audit_fixed_date)')
							 ->edit_column('amount', '$1', 'dt_money_format(amount)');


			$this->output->set_content_type('application/json')
						 ->set_status_header(200)
						 ->set_output($this->datatables->generate('json'));
		else:
		 show_404();
		endif;
	}





	public function receipt()
	{
		if (can(['read-quotation', 'read-invoice'])):
			$quotation_id = $this->input->post('quotation_id');

			$this->datatables->select('r.id,
										r.payment_method,
										r.created_on as receipt_date,
										r.paid_amount,
										r.discount,
                                        r.status as receipt_status,
										note.note,
										note.first_name,
										note.last_name,
										cin.combined_invoice_number,
										cis.combined_invoice_status,
										note.created_on as note_created_on')->from('receipt r')
							 ->join('quotation q', 'r.quotation_id = q.id')
							 ->join('(
								 select note.*, u.first_name, u.last_name from receipt_note note
									join user u on note.created_by = u.id
									where note.id in (
									select MAX(note.id) as id from receipt_note note
									  join user u on note.created_by = u.id
									  group by note.receipt_id
									) ORDER by note.receipt_id
								 ) as note', 'note.receipt_id = r.id', 'left')
							 ->join('(SELECT dr.receipt_id as cin_r_id, GROUP_CONCAT(i.number) AS combined_invoice_number
								FROM detail_receipt dr
								JOIN invoice i ON FIND_IN_SET(i.id, dr.invoice_id)
								group BY dr.receipt_id) as cin', 'cin.cin_r_id = r.id', 'left')
							 ->join('(SELECT dr.receipt_id as cis_r_id, GROUP_CONCAT(dr.invoice_status) AS combined_invoice_status
   								FROM detail_receipt dr
   								group BY dr.receipt_id) as cis', 'cis.cis_r_id = r.id', 'left')
							 ->where('q.id', $quotation_id)
							 // ->where('r.status', 'Success')
							 ->add_column('tools', '$1', 'dt_tools_receipt(id, receipt_status)')
							 ->add_column('invoice_number', '$1', 'dt_format_receipt_invoice_number(combined_invoice_number)')
							 ->add_column('invoice_status', '$1', 'dt_format_receipt_invoice_status(combined_invoice_status)')
                             ->add_column('receipt_status', '$1', 'dt_format_receipt_status(receipt_status)')
							 ->edit_column('receipt_date', '$1', 'human_timestamp(receipt_date, "d/m/Y")')
							 ->edit_column('note', '$1', 'dt_note(id, note, first_name, last_name, note_created_on)')
							 ->edit_column('paid_amount', '$1', 'money_number_format(paid_amount, 0)')
			                 ->edit_column('discount', '$1', 'money_number_format(discount, 0)');
							 // ->edit_column('audit_fixed_date', '$1', 'human_date(audit_fixed_date, "d/m/Y")')
							 // ->edit_column('amount', '$1', 'dt_money_format(amount, 0)');


			$this->output->set_content_type('application/json')
						 ->set_status_header(200)
						 ->set_output($this->datatables->generate('json'));
		else:
		 show_404();
		endif;
	}










    /**
     * All application form template
     */
    public function application_form_template()
    {
        if (can('read-application-form-template')):
            $this->datatables->select('t.id as id, t.name as name, t.notes, t.created_on as created_on, file.filename, file.path, file.url, user.first_name, user.last_name')
                             ->from('application_form_template t')
						     ->join('file', 't.file_id = file.id')
							 ->join('user', 't.created_by = user.id')
						     ->edit_column('created_on', '$1', 'dt_timestamp_to_format(created_on)')
							 ->edit_column('user', '$1', 'dt_combine_user_name(first_name, last_name)')
                             ->add_column('tools', '$1', 'dt_tools_template(id, url)');

            $this->output->set_content_type('application/json')
                         ->set_status_header(200)
                         ->set_output($this->datatables->generate('json'));
        else:
            show_404();
        endif;
    }


    // All application form
    public function application_form()
    {
        if (can(['read-application-form', 'read-client'])):
            $this->datatables->select('a.id, a.id as application_id, a.created_on as created_on, a.number, a.client_name, fu.id as follow_up_id, a.standard, a.send_quotation_status, a.send_date, a.receive_date, fu.first_name, fu.last_name, fu.notes, fu.created_on as notes_created_on, created_by.first_name as created_by_first_name, created_by.last_name as created_by_last_name')
                             ->from('application_form a')
							 ->where('a.deleted', '0')
							 ->join('user created_by', 'a.created_by = created_by.id')
							 ->join('(
								 select fu.*, u.first_name, u.last_name from application_follow_up fu
								 join user u on fu.created_by = u.id
								 where fu.id in (
								 select MAX(fu.id) as id from application_follow_up fu
										  join user u on fu.created_by = u.id
										  group by fu.application_id
								 ) order by fu.application_id
								 ) as fu', 'fu.application_id = a.id', 'left')
						     ->edit_column('id', '$1', 'dt_leading_zero(id, 4)')
							 ->edit_column('number', '$1', 'dt_name(application_id, number, application-form)')
							 ->edit_column('send_quotation_status', '$1', 'dt_send_quotation_status(send_quotation_status)')
							 ->edit_column('standard', '$1', 'dt_application_form_standard(standard)')
							 ->edit_column('created_on', '$1', 'human_timestamp(created_on)')
                             ->add_column('dates', '$1', 'dt_application_dates(send_date, receive_date)')
							 ->add_column('follow_up', '$1', 'dt_application_follow_up(follow_up_id, first_name, last_name, notes, notes_created_on)')
							 ->add_column('tools', '$1', 'dt_tools_application_form(id, follow_up_id)');

            $this->output->set_content_type('application/json')
                         ->set_status_header(200)
                         ->set_output($this->datatables->generate('json'));
        else:
            show_404();
        endif;
    }





	/**
     * All Client's Address by Client ID
     */
    public function address()
    {
		$client_id = $this->input->post('client_id');

		$this->datatables->select('address.id, address.client_id, address.address, address.address_2, address.country, address.postal_code, address.total_employee, address.primary')
						 ->from('address')
						 ->where('client_id', $client_id)
						 ->where('address.deleted', '0')
						 ->add_column('tools', '$1', 'dt_tools_address(id, client_id, primary, edit|delete)');

		$this->output->set_content_type('application/json')
					 ->set_status_header(200)
					 ->set_output($this->datatables->generate('json'));
    }






	/**
	 * All certification scheme
	 */
	public function certification_scheme()
	{
		$this->datatables->select('c.id, c.name, c.created_on, c.updated_on')
						 ->from('certification_scheme c')
						 ->where('c.deleted', '0')
						 ->edit_column('created_on', '$1', 'dt_timestamp_to_format(created_on)')
						 ->edit_column('updated_on', '$1', 'dt_timestamp_to_format(updated_on)')
						 ->add_column('tools', '$1', 'dt_tools(id, certification-scheme, edit_modal|delete_sa)');

		$this->output->set_content_type('application/json')
					 ->set_status_header(200)
					 ->set_output($this->datatables->generate('json'));
	}





	/**
	 * All Accreditation
	 */
	public function accreditation()
	{
		$this->datatables->select('a.id, a.name, a.created_on, a.updated_on')
						 ->from('accreditation a')
						 ->where('a.deleted', '0')
						 ->edit_column('created_on', '$1', 'dt_timestamp_to_format(created_on)')
						 ->edit_column('updated_on', '$1', 'dt_timestamp_to_format(updated_on)')
						 ->add_column('tools', '$1', 'dt_tools(id, accreditation, edit_modal|delete_sa)');

		$this->output->set_content_type('application/json')
					 ->set_status_header(200)
					 ->set_output($this->datatables->generate('json'));
	}

    public function poc() {
		$client_id = $this->input->post('client_id');
		$this->datatables->select('contact.id, contact.salutation, contact.status, contact.name, contact.position, contact.department, contact.mobile, contact.phone, contact.fax, contact.email, contact.primary')
				 ->from('contact')
				 ->where('contact.deleted', '0')
				 ->where('contact.client_id', $client_id)
				 ->add_column('tools', '$1', 'dt_tools_poc(id, contact, edit|delete)');

		$this->output->set_content_type('application/json')
			 ->set_status_header(200)
			 ->set_output($this->datatables->generate('json'));
	}


	/**
	 * Finance Summary
	 */
	public function finance_summary()
	{
		if (can(['read-quotation', 'read-invoice'])):
			$filter = $this->input->post('filter');
      		$search_by_key = $this->input->post('search')['value'];

			$where = [
				$this->datatables->where('q.deleted', '0'),
				$this->datatables->where('i.deleted', '0'),
				$this->datatables->where('i.request_status !=', 'Pending'),
			];

			if ($filter) {
				parse_str($filter, $filter_arr);
				array_shift($filter_arr);

				if (isset($filter_arr['client_id'])) {
					$filter_client_id = $filter_arr['client_id'];
					if (!in_array("All", $filter_client_id)) {
						$where_client_id = '( ';
						foreach ($filter_client_id as $key => $status) {
							$where_client_id .= 'c.id' .' = '. "'$status'";
							if ($key < count($filter_client_id)-1) {
								$where_client_id .= ' OR ';
							}
						}
						$where_client_id .= ') ';
						array_push($where, $this->datatables->where($where_client_id));
					}
				}

				if (isset($filter_arr['invoice_status'])) {
					$filter_invoice_status = $filter_arr['invoice_status'];
					if (!in_array("All", $filter_invoice_status)) {
						$where_invoice_status = '( ';
						foreach ($filter_invoice_status as $key => $status) {
							$where_invoice_status .= 'i.status' .' = '. "'$status'";
							if ($key < count($filter_invoice_status)-1) {
								$where_invoice_status .= ' OR ';
							}
						}
						$where_invoice_status .= ') ';
						array_push($where, $this->datatables->where($where_invoice_status));
					}
				}

				if (isset($filter_arr['certification_cycle'])) {
					$filter_certification_cycle = $filter_arr['certification_cycle'];
					if (!in_array("All", $filter_certification_cycle)) {
						$where_certification_cycle = '( ';
						foreach ($filter_certification_cycle as $key => $status) {
							$where_certification_cycle .= 'q.certification_cycle' .' = '. "'$status'";
							if ($key < count($filter_certification_cycle)-1) {
								$where_certification_cycle .= ' OR ';
							}
						}
						$where_certification_cycle .= ') ';
						array_push($where, $this->datatables->where($where_certification_cycle));
					}
				}

				if (isset($filter_arr['month_created'])) {
					$filter_month_created = $filter_arr['month_created'];
					if (!in_array("All", $filter_month_created)) {
						$where_month_created = '( ';
						foreach ($filter_month_created as $key => $month) {
							$where_month_created .= 'MONTH(FROM_UNIXTIME(i.created_on))' .' = '. "'$month'";
							if ($key < count($filter_month_created)-1) {
								$where_month_created .= ' OR ';
							}
						}
						$where_month_created .= ') ';
						array_push($where, $this->datatables->where($where_month_created));
					}
				}

				if (isset($filter_arr['year_created'])) {
					$filter_year_created = $filter_arr['year_created'];
					if (!in_array("All", $filter_year_created)) {
						$where_year_created = '( ';
						foreach ($filter_year_created as $key => $month) {
							$where_year_created .= 'YEAR(FROM_UNIXTIME(i.created_on))' .' = '. "'$month'";
							if ($key < count($filter_year_created)-1) {
								$where_year_created .= ' OR ';
							}
						}
						$where_year_created .= ') ';
						array_push($where, $this->datatables->where($where_year_created));
					}
				}

				// filter date cerated
				if ($filter_arr['date_created_start'] && $filter_arr['date_created_end']) {
					$date_created_start = $filter_arr['date_created_start'];
					$date_created_end = $filter_arr['date_created_end'];

					array_push($where, $this->datatables->where('i.created_on >= unix_timestamp("'.$date_created_start.'")
					AND i.created_on <= unix_timestamp("'.$date_created_end.'")'));
				}
			}

			// if ($search_by_key) {
			// 	$where_combined = '(combined_invoice_number LIKE "%'.$search_by_key.'%" OR combined_invoice_status LIKE "%'.$search_by_key.'%" OR q.number LIKE "%'.$search_by_key.'%"
			// 	OR c.name LIKE "%'.$search_by_key.'%" OR i.invoice_type LIKE "%'.$search_by_key.'%" OR i.status LIKE "%'.$search_by_key.'%")';
			// 	array_push($where, $this->datatables->where($where_combined));
			// }

			$this->datatables->select('c.id as client_id,
										c.name as client_name,
										c.uen as client_uen,
										c.website as client_website,
										c.phone as client_phone,
										c.email as client_email,
										c.fax as client_fax,
										cth.name as contact_name,
										cth.email as contact_email,
										cth.position as contact_position,
										cth.department as contact_department,
										cth.phone as contact_phone,
										cth.fax as contact_fax,
										cth.mobile as contact_mobile,
										ah.address,
										ah.address_2,
										ah.country,
										ah.postal_code,
										q.id as quotation_id,
										q.number as quote_number,
										q.certification_scheme,
										q.accreditation,
										q.status as quote_status,
										i.id as invoice_id,
										i.created_on as invoice_date_created,
										i.status as invoice_status,
										t.combined_invoice_id,
										t.combined_invoice_number,
										t.combined_invoice_type,
										t.combined_invoice_status,
										t.combined_invoice_date_created,
										t.combined_invoice_amount,
										ccs.combined_certification_scheme')
							 ->from('quotation q')
							 ->join('client_history ch', 'q.client_history_id = ch.id')
							 ->join('contact_history cth', 'q.contact_history_id = cth.id')
							 ->join('address_history ah', 'q.address_history_id = ah.id')
							 ->join('client c', 'ch.client_id = c.id')
							 ->join('invoice i', 'i.quotation_id = q.id')
							 ->join('(SELECT q.id as ccs_q_id, GROUP_CONCAT(cs.name) AS combined_certification_scheme
			                           FROM quotation q
			                           JOIN certification_scheme cs ON FIND_IN_SET(cs.id, q.certification_scheme)
			                           GROUP BY q.id) as ccs', 'ccs.ccs_q_id = q.id', 'left')
						   	 ->join("(SELECT
									  q.id AS quotation_id,
									  GROUP_CONCAT(i.id ORDER BY i.id ASC SEPARATOR ', ') AS combined_invoice_id,
									  GROUP_CONCAT(i.number ORDER BY i.id ASC SEPARATOR ', ') AS combined_invoice_number,
									  GROUP_CONCAT(i.invoice_type ORDER BY i.id ASC SEPARATOR ', ') AS combined_invoice_type,
									  GROUP_CONCAT(i.created_on ORDER BY i.id ASC SEPARATOR ', ') AS combined_invoice_date_created,
									  GROUP_CONCAT(i.status ORDER BY i.id ASC SEPARATOR ', ') AS combined_invoice_status,
									  GROUP_CONCAT(i.amount ORDER BY i.id ASC SEPARATOR ', ') AS combined_invoice_amount
								  FROM
									  quotation q
								  JOIN
									  invoice i ON q.id = i.quotation_id
								  WHERE i.request_status = 'Approved' AND q.deleted = 0
								  GROUP BY q.id) as t", 't.quotation_id = q.id');

							 $where;
	 		$this->datatables->group_by('q.id');
			$this->datatables->add_column('client_name_formatted', '$1', 'dt_client_name_finance_summary(client_name, certification_scheme, accreditation)')
				->add_column('quote_number_formatted', '$1', 'dt_quote_number(quotation_id, quote_number, quotation)')
				->edit_column('quote_status', '$1', 'dt_quote_status(quote_status)')
				->add_column('invoice_number', '$1', 'dt_format_invoice_number_by_quotation(combined_invoice_id, combined_invoice_number)')
				->add_column('invoice_type', '$1', 'dt_format_invoice_type_by_quotation(combined_invoice_type)')
				->add_column('date_created_formatted', '$1', 'dt_format_invoice_date_created_by_quotation(combined_invoice_date_created)')
				->add_column('invoice_status_formatted', '$1', 'dt_format_invoice_status_by_quotation(combined_invoice_status)')
				->add_column('action', '$1', 'dt_tools_finance_summary(quotation_id)');
			$this->output->set_content_type('application/json')
						 ->set_status_header(200)
						 ->set_output($this->datatables->generate('json'));
		else:
		 show_404();
		endif;
	}

  public function client_quotation()
  {
    if (can(['read-quotation', 'read-client'])):
      $client_id = $this->input->post('client_id');

      $this->datatables->select('q.id as id, q.number as quote_number, q.flagged, q.training_type, c.name as client_name, client.status as client_status, qn.note, qn.first_name, qn.last_name, qn.created_on as note_created_on, q.status as quote_status, q.certification_scheme, q.accreditation, q.created_on, q.confirmed_on, cc.name as certificate_cycle')
               ->from('quotation q')
               ->join('client_history c', 'q.client_history_id = c.id')
			   ->join('client', 'c.client_id = client.id')
               ->join('certification_cycle cc', 'q.certification_cycle = cc.id', 'LEFT')
               ->join('accreditation a', 'q.accreditation = a.id', 'LEFT')
               ->join('certification_scheme cs', 'q.certification_scheme = cs.id', 'LEFT')
               ->join('(
                  select qn.*, u.first_name, u.last_name from quotation_note qn
                  join user u on qn.created_by = u.id
                  where qn.id in (
                  select MAX(qn.id) as id from quotation_note qn
                       join user u on qn.created_by = u.id
                       group by qn.quotation_id
                  ) order by qn.quotation_id
                 ) as qn', 'qn.quotation_id = q.id', 'left')
               ->join('(
                 select memo.id, memo.quotation_id from memo
                  where memo.id in (
                  select MAX(memo.id) as id from memo
                  group by memo.quotation_id
                  ) order by memo.quotation_id
                 ) as m', 'm.quotation_id = q.id', 'left');
               $this->datatables->where('c.client_id', $client_id);
               $this->datatables->where('q.deleted', '0');
      $this->datatables->add_column('quote_number_formatted', '$1', 'dt_quote_number(id, quote_number, quotation)')
               ->edit_column('quote_status', '$1', 'dt_quote_status(quote_status)')
               ->edit_column('client_status', '$1', 'dt_client_status(client_status)')
               ->add_column('total_branch', '$1', 'dt_total_branch(id)')
               ->edit_column('created_on', '$1', 'dt_timestamp_to_format(created_on, "d/m/Y")')
               ->edit_column('confirmed_on', '$1', 'dt_timestamp_to_format(confirmed_on, "d/m/Y")')
               ->edit_column('note', '$1', 'dt_note(id, note, first_name, last_name, note_created_on)')
			   ->edit_column('certification_scheme', '$1', 'dt_quotation_certification_scheme(certification_scheme, accreditation, training_type)')
               ->add_column('tools', '$1', 'dt_tools_quotation(id, quote_status, view_notes|view_history|view_memo|view_invoice|edit|send_email)');

      $this->output->set_content_type('application/json')
             ->set_status_header(200)
             ->set_output($this->datatables->generate('json'));
    else:
     show_404();
    endif;
  }

  public function client_application_form()
{
	$client_id = $this->input->post('client_id');

	$this->datatables->select('q.application_form, af.created_on, af.id')
						->from('quotation q')
						->join('client_history ch', 'q.client_history_id = ch.id')
						->join('application_form af', 'af.number = q.application_form')
						->where('ch.client_id', $client_id)
						->where('q.application_form != ', '')
						->edit_column('created_on', '$1', 'dt_timestamp_to_format(created_on, "d/m/Y")')
						->add_column('tools', '$1', 'dt_tools_client_application_form(id)');

	$this->output->set_content_type('application/json')
					->set_status_header(200)
					->set_output($this->datatables->generate('json'));
}


}
