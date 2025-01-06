<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group_model extends WebimpModel
{
    protected $_table = 'group';

	protected $before_create = ['_format_submission', '_record_created'];
	protected $before_update = ['_format_submission', '_record_updated'];

	protected $has_many = [
        'permissions' => [
            'primary_key' => 'group_id',
            'model'       => 'group_permission_model',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }




	protected function _format_submission($post)
    {
		$full_access = 0;
		if (in_array('all', $post['permission_id'])) {
			$full_access = 1;
		}

		$name = $post['name'];
		$data = [
			'name' => strtolower($name),
		    'description' => ucfirst($name),
			'full_access' => $full_access
		];

        return $data;
    }




	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}



	protected function _record_updated($post)
	{
		$current_user = $this->session->userdata();

		$post['updated_by'] = $current_user['user_id'];
		$post['updated_on'] = time();

		return $post;
	}




    public function get_superadmin_id()
    {
        $superadmin_group = $this->get_by([
            'name' => 'superadmin',
        ]);

        return ($superadmin_group) ? $superadmin_group->id : '';
    }




    public function get_crew_id()
    {
        $crew_group = $this->get_by([
            'name' => 'crew',
        ]);

        return ($crew_group) ? $crew_group->id : '';
    }




    /**
     * Generate a dropdown of Group if any filter
     *
     * @access public
     * @param array $filter (default: null)
     * @return array
     */
    public function dropdown_filter($filter = null)
    {
        $options = [];

        if ($filter) {
            $groups = $this->get_many_by($filter);
        } else {
            $groups = $this->get_all();
        }

        foreach($groups as $group) {
            $options[$group->id] = $group->description;
        }

        return $options;
    }



	/**
	 * Insert permissions.
	 *
	 * @param $role_id
	 * @param $permissions
	 * @return int
	 */
	public function addPermissions($role_id, $permissions)
	{
		$data["role_id"] = $role_id;
		if (is_array($permissions)) {
			foreach ($permissions as $permission) {
				$data["permission_id"] = $permission;
				$this->addPermission($data);
			}
		}
		else {
			$data["permission_id"] = $permissions;
			$this->addPermission($data);
		}

		return 1;
	}

	/**
	 * Insert permission.
	 *
	 * @param $data
	 * @return mixed
	 */
	public function addPermission($data)
	{
		return $this->db->insert('permission_roles', $data);
	}

	/**
	 * Edit permissions.
	 *
	 * @param $role_id
	 * @param $permissions
	 * @return int
	 */
	public function editPermissions($role_id, $permissions)
	{
		if($this->deletePermissions($role_id, $permissions))
			$this->addPermissions($role_id, $permissions);

		return 1;
	}

	/**
	 * Delete permission.
	 *
	 * @param $role_id
	 * @param $permissions
	 * @return mixed
	 */
	public function deletePermissions($role_id, $permissions)
	{

		return $this->db->delete('permission_roles', array('role_id' => $role_id));
	}

	/**
	 * Read role wise permissions.
	 *
	 * @param $id
	 * @return array
	 */
	public function roleWisePermissions($id)
	{
		return array_map(function($item){
			return $item["permission_id"];
		}, $this->db->get_where("group_permissions", array("group_id" => $id))->result_array());
	}

	/**
	 * Read permission details associated with role.
	 *
	 * @param $id
	 * @return array
	 */
	public function roleWisePermissionDetails($id)
	{
		return array_map(function($item){
			$group = new Group_model();
			return $group->findPermission($item);
		}, $this->roleWisePermissions($id));
	}

	/**
	 * Find permission.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function findPermission($id)
	{
		return $this->db->get_where("permissions", array("id" => $id, "deleted_at" => null))->row(0);
	}

	/**
	 * Read role id against name.
	 *
	 * @param $name
	 * @return mixed
	 */
	public function roleID($name)
	{
		return $this->db->get_where("roles", array("name" => $name, "deleted_at" => null))->row(0)->id;
	}


	/**
	 * check if group has full access by group id
	 *
	 * @param $name
	 * @return mixed
	 */
	public function hasFullAccess($id)
	{
		return $this->db->get_where("group", array("id" => $id))->row(0)->full_access;
	}

}
