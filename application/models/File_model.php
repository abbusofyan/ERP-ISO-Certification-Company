<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends WebimpModel
{
    protected $_table = 'file';

    protected $before_create = ['_log_created_by'];
    protected $before_delete = ['_cache_file'];
    protected $after_delete  = ['_unlink_file'];

    protected $file;

    public function __construct()
    {
        parent::__construct();
    }


    protected function _log_created_by($post)
    {
        $current_user = $this->session->userdata();
        
        if (!empty($current_user['user_id'])) {
            $post['created_by'] = $current_user['user_id'];
        }

        return $post;
    }

    

    
    /**
     * Cache the file as an object for use later.
     * 
     * @access protected
     * @param int $file_id
     * @return obj
     */
    protected function _cache_file($file_id)
    {
        $this->file = $this->get((int) $file_id);

        return $this;
    }




    /**
     * Unlink the file when unreferenced from DB.
     * 
     * @access protected
     * @param array $data[$file_id, $result]
     * @return void
     */
    protected function _unlink_file($data)
    {
        if (isset($this->file)) {
            $storageClient = register_stream_wrapper();
            
            if (file_exists($this->file->path)) {
                unlink($this->file->path);
            }
        }     

        return $data;
    }




    /**
     * Process the uploaded file.
     *
     * @access protected
     * @param string $file
     * @param string $directory
     * @return file_id
     */
    public function process_uploaded_file($file, $directory)
    {
        $this->load->config('upload', true);

        // stop process if there's error
        if ($file['error'] !== 0)
            return null;

        // ignore if file size greater than config
        if (($file['size']/1000) > $this->config->item('max_size', 'upload'))
            return null;
            
        $expl = explode(".", $file['name']);
        $ext  = '.' . end($expl); # extra () to prevent notice
        $type = $file['type'];
        $size = $file['size'];
        $path = '/uploads/' . $directory . '/';
        
        $clean_filename = ucwords(url_title(preg_replace('/\\.[^.\\s]{3,4}$/', '', $file['name']), ' ', true)) . $ext;
        $path_filename  = time() . '-' . url_title(preg_replace('/\\.[^.\\s]{3,4}$/', '', $file['name']), '-', true) . $ext;
        
        if (is_uploaded_file($file['tmp_name'])) {
            if (isset($_SERVER['SERVER_SOFTWARE'])
               && strpos($_SERVER['SERVER_SOFTWARE'], 'Google App Engine') !== false
            ) {
                $final_path = GS_BUCKET . $path . $path_filename;
                
                $storageClient = register_stream_wrapper();
                
                if (!file_exists(GS_BUCKET . $path))
                    mkdir(GS_BUCKET . $path, 0755, true);

                move_uploaded_file($file['tmp_name'], $final_path);

                $bucket = $storageClient->bucket(GS_BUCKET_NAME);
                $object = $bucket->object('uploads/' . $directory . '/' . $path_filename);
                $object->update(['acl' => []], ['predefinedAcl' => 'publicRead']);
                
                $public_url = "https://storage.googleapis.com/" . GS_BUCKET_NAME . $path . $path_filename;
            } else {
                $path       = "." . $path;
                $final_path = $path . $path_filename;

                if (!file_exists($path))
                    mkdir($path, 0755, true);
                
                move_uploaded_file($file['tmp_name'], $final_path);

                $public_url = site_url() . substr($final_path, 2);
            }
        }
        
        $current_user = $this->session->userdata();
        
        return $this->file_model->insert([
            'filename'       => $clean_filename,
            'path'           => $final_path,
            'url'            => $public_url,
            'mime'           => $type,
            'size'           => $size,
            'created_on'     => time(),
            'created_by'     => $current_user['user_id'],
        ]);
    }
}
