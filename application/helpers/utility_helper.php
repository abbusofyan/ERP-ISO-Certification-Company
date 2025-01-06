<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_app_version')) {
    function get_app_version() {
        return VERSION;
    }
}




if (!function_exists('assets_url')) {
    function assets_url($string = NULL) {
        return base_url() . 'assets/' . $string;
    }
}




if (!function_exists('temp_url')) {
    function temp_url($string = NULL) {
        return base_url() . 'temp/' . $string;
    }
}




if (!function_exists('uploads_url')) {
    function uploads_url($string = NULL) {
        return base_url() . 'uploads/' . $string;
    }
}




/**
 * Merge user defined arguments into defaults array.
 *
 * This function is used throughout the application for both string or array
 * to be merged into another array.
 *
 * @param  string|array|object  $args      Value to merge with $defaults.
 * @param  string               $defaults  Optional. Array that serves as the defaults. Default
 *                                         empty.
 *
 * @return [type]                          Merged user defined values with defaults.
 */
// function parse_args($args, $defaults = '') {
//     if (is_object($args)) {
//         $output = get_object_vars($args);
//     } elseif (is_array($args)) {
//         $output =& $args;
//     } else {
//         parse_str($args, $output);
//     }
//
//     if (is_array($defaults)) {
//         return array_merge($defaults, $output);
//     }
//
//     return $output;
// }

function parse_args($args, $defaults = '') {
    if ($args === null) {
        $output = array();
    } elseif (is_object($args)) {
        $output = get_object_vars($args);
    } elseif (is_array($args)) {
        $output =& $args;
    } else {
        parse_str($args, $output);
    }

    if (is_array($defaults)) {
        return array_merge($defaults, $output);
    }

    return $output;
}




/**
 * Outputs all enqueued styles and scripts on header.
 */
function app_head() {
    webimp_styles()->do_items();
    webimp_scripts()->do_head_items();
	$version = VERSION;
    $output = [];

    // vendor js & css
    array_push($output, '<script type="text/javascript" src="' . assets_url('js/vendors.min.js') . '"></script>');
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/vendors.min.css?$version") . '">');

    // bootstrap
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/bootstrap.css?$version") . '">');
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/bootstrap-extended.css?$version") . '">');

    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/colors.css?$version") . '">');
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/components.css?$version") . '">');
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/page-auth.css?$version") . '">');

    // toastr css
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/toastr.min.css?$version") . '">');
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/ext-component-toastr.min.css?$version") . '">');

    // custom css
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/style.css?$version") . '">');

	// custom js
	array_push($output, '<script type="text/javascript" src="' . assets_url('js/custom.js') . '"></script>');

    // core css
    array_push($output, '<link rel="stylesheet" href="' . assets_url("css/core/vertical-menu.css?$version") . '">');

    // toastr js
    array_push($output, '<script type="text/javascript" src="' . assets_url('js/toastr.min.js') . '"></script>');

    return implode('', $output);
}




/**
 * Outputs all enqueued styles and scripts for Datatables.
 */
if (!function_exists('datatable_scripts')) {
    function datatable_scripts()
    {
        $output = [];

        // css
        array_push($output, '<link rel="stylesheet" href="' . assets_url("css/datatables/dataTables.bootstrap4.min.css") . '">');
        array_push($output, '<link rel="stylesheet" href="' . assets_url("css/datatables/responsive.bootstrap4.min.css") . '">');
        array_push($output, '<link rel="stylesheet" href="' . assets_url("css/datatables/buttons.bootstrap4.min.css") . '">');

        // js
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/datatables/jquery.dataTables.min.js') . '"></script>');
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/datatables/datatables.bootstrap4.min.js') . '"></script>');
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/datatables/dataTables.responsive.min.js') . '"></script>');
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/datatables/responsive.bootstrap4.min.js') . '"></script>');
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/datatables/datatables.buttons.min.js') . '"></script>');

        return implode('', $output);
    }
}




/**
 * Outputs all enqueued styles and scripts for form.
 */
if (!function_exists('form_scripts')) {
    function form_scripts()
    {
        $output = [];

        // select2
        array_push($output, '<link rel="stylesheet" href="' . assets_url("css/forms/select2.min.css") . '">');
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/forms/select2.min.js') . '"></script>');

		// multipl select
		array_push($output, '<link rel="stylesheet" href="' . assets_url("css/forms/bootstrap-select.css") . '">');
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/forms/bootstrap-select.js') . '"></script>');

        // flatpickr
        array_push($output, '<link rel="stylesheet" href="' . assets_url("css/forms/flatpickr.min.css") . '">');
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/forms/flatpickr.min.js') . '"></script>');

		// jquery ui for autocomplete input
		array_push($output, '<script type="text/javascript" src="' . assets_url('js/forms/jquery-ui.js') . '"></script>');

		array_push($output, '<script type="text/javascript" src="' . assets_url('js/ckeditor/ckeditor.js') . '"></script>');

        return implode('', $output);
    }
}




/**
 * Outputs all enqueued styles and scripts on footer.
 */
function app_tail() {
    $output = [];

    // core js
    array_push($output, '<script type="text/javascript" src="' . assets_url('js/core/app-menu.min.js') . '"></script>');
    array_push($output, '<script type="text/javascript" src="' . assets_url('js/core/app.min.js') . '"></script>');
    array_push($output, '<script type="text/javascript" src="' . assets_url('js/customizer.js') . '"></script>');

    return implode('', $output);
}




/**
 * Outputs all enqueued styles and scripts for sweetalert.
 */
if (!function_exists('sweetalert_scripts')) {
    function sweetalert_scripts()
    {
        $output = [];

        // css
        array_push($output, '<link rel="stylesheet" href="' . assets_url("css/extensions/sweetalert2.min.css") . '">');

        // js
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/extensions/sweetalert2.all.min.js') . '"></script>');

        return implode('', $output);
    }
}




/**
 * Outputs all enqueued styles and scripts for calendar.
 */
if (!function_exists('calendar_scripts')) {
    function calendar_scripts()
    {
        $output = [];

        // css
        array_push($output, '<link rel="stylesheet" href="' . assets_url("css/extensions/fullcalendar.min.css") . '">');
        array_push($output, '<link rel="stylesheet" href="' . assets_url("css/extensions/app-calendar.css") . '">');

        // js
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/extensions/fullcalendar.min.js') . '"></script>');

        return implode('', $output);
    }
}




/**
 * Retrieves a modified URL query string.
 *
 * @param string|array  $key    Either a query variable key, or an associative array of query variables.
 * @param string        $value  Optional. Either a query variable value, or a URL to act upon.
 * @param string        $url    Optional. A URL to act upon.
 *
 * @return string               New URL query (unescaped).
 */
function add_query_arg() {
    $args = func_get_args();
    if (is_array($args[0])) {
        if (count($args) < 2 || false === $args[1]) {
            $uri = $_SERVER['REQUEST_URI'];
        } else {
            $uri = $args[1];
        }
    } else {
        if (count($args) < 3 || false === $args[2]) {
            $uri = $_SERVER['REQUEST_URI'];
        } else {
            $uri = $args[2];
        }
    }

    if ($frag = strstr($uri, '#')) {
        $uri = substr($uri, 0, -strlen($frag));
    } else {
        $frag = '';
    }

    if (0 === stripos($uri, 'http://')) {
        $protocol = 'http://';
        $uri = substr($uri, 7);
    } elseif (0 === stripos($uri, 'https://')) {
        $protocol = 'https://';
        $uri = substr($uri, 8);
    } else {
        $protocol = '';
    }

    if (strpos($uri, '?') !== false) {
        list($base, $query) = explode('?', $uri, 2);
        $base .= '?';
    } elseif ($protocol || strpos($uri, '=') === false) {
        $base = $uri . '?';
        $query = '';
    } else {
        $base = '';
        $query = $uri;
    }

    parse_str($query, $qs);
    $qs = map_deep($qs, 'urlencode');

    if (is_array($args[0])) {
        foreach ($args[0] as $k => $v) {
            $qs[ $k ] = $v;
        }
    } else {
        $qs[ $args[0] ] = $args[1];
    }

    foreach ($qs as $k => $v) {
        if ($v === false) {
            unset($qs[$k]);
        }
    }

    $ret = build_query($qs);
    $ret = trim($ret, '?');
    $ret = preg_replace('#=(&|$)#', '$1', $ret);
    $ret = $protocol . $base . $ret . $frag;
    $ret = rtrim($ret, '?');
    return $ret;
}




/**
 * Maps a function to all non-iterable elements of an array or an object.
 *
 * @param  mixed     $value     The array, object, or scalar.
 * @param  callable  $callback  The function to map onto $value.
 *
 * @return mixed                The value with the callback applied to all non-arrays and
 *                              non-objects inside it.
 */
function map_deep($value, $callback) {
    if (is_array($value)) {
        foreach ($value as $index => $item) {
            $value[$index] = map_deep($item, $callback);
        }
    } elseif (is_object($value)) {
        $object_vars = get_object_vars($value);

        foreach ($object_vars as $property_name => $property_value) {
            $value->$property_name = map_deep($property_value, $callback);
        }
    } else {
        $value = call_user_func($callback, $value);
    }

    return $value;
}




if (!function_exists('build_query')) {
    function build_query($data, $prefix = null, $sep = '&', $key = '', $urlencode = true) {
        $ret = [];

        foreach ((array) $data as $k => $v) {
            if ($urlencode) {
                $k = urlencode($k);
            }

            if (is_int($k) && $prefix != null) {
                $k = $prefix . $k;
            }

            if (!empty($key)) {
                $k = $key . '%5B' . $k . '%5B';
            }

            if ($v === null) {
                continue;
            } elseif ($v === false) {
                $v = '0';
            }

            if (is_array($v) ||
                is_object($v)
            ) {
                array_push($ret, http_build_query($v, '', $sep, $k, $urlencode));
            } elseif ($urlencode) {
                array_push($ret, $k . '=' . urlencode($v));
            } else {
                array_push($ret, $k . '=' . $v);
            }
        }

        if ($sep === null) {
            $sep = ini_get('arg_seperator.output');
        }

        return implode($sep, $ret);
    }
}




/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 *
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
    $url  = 'http://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";

    if ($img) {
        $url = '<img src="' . $url . '"';
        foreach ($atts as $key => $val)
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}




if (!function_exists('human_date')) {
    function human_date($datestring = NULL, $format = 'j M Y, g:i A') {
        if ($datestring) {
            return date($format, strtotime($datestring));
        }
        return '';
    }
}




if (!function_exists('human_timestamp')) {
    function human_timestamp($timestamp = NULL, $format = 'j M Y, g:i A') {
        if ($timestamp) {
            return date($format, $timestamp);
        }

        return NULL;
    }
}




if (!function_exists('mysql_datetime')) {
    function mysql_datetime($timestamp = NULL, $format = '%Y-%m-%d %H:%i:%s') {
        if ($timestamp) {
            return mdate($format, $timestamp);
        } else {
            return mdate($format, time());
        }

        return NULL;
    }
}




/**
 * Output preformatted data from $data for debugging purposes.
 *
 * @param mixed  $data  The array or object to print.
 */
function verbose($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}




if (!function_exists('money_number_format')) {
    function money_number_format($number, $country = 'Singapore') {
        $decimals = 2;
        $symbol = '$ ';
        if ($country != 'Singapore') {
            $symbol = '';
        }
        if ($number) {
            return $symbol . number_format($number, $decimals);
        }
        return '';
    }
}




if (!function_exists('replace_newline')) {
    function replace_newline($string = NULL , $replace_to = '<BR>') {
		if ($string) {
			return preg_replace('~[\r\n]+~', $replace_to, $string);
		} else {
			return null;
		}
    }
}




if (!function_exists('randomGen')) {
    function randomGen($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }
}




if (!function_exists('convert_to_utf8')) {
    function convert_to_utf8($param , $ent = ENT_QUOTES , $encoding_type = 'UTF-8') {
        $param = html_entity_decode($param , $ent , $encoding_type);
        return $param;
    }
}




if (!function_exists('sort_on_field')) {
    function sort_on_field(&$objects, $on, $order = 'ASC') {
		$comparer = ($order === 'DESC')
			? "return -strcmp(\$a->{$on},\$b->{$on});"
			: "return strcmp(\$a->{$on},\$b->{$on});";
		usort($objects, create_function('$a,$b', $comparer));
	}
}




if (!function_exists('datestring_to_format')) {
    function datestring_to_format($datestring = null, $format = 'j M Y, g:i A') {
        return timestamp_to_format(strtotime($datestring), $format);
    }
}




if (!function_exists('timestamp_to_format')) {
    // some don't have any timestamp, so return null
    function timestamp_to_format($timestamp = null, $format = 'j M Y, g:i A') {
        if ($timestamp)
            return date($format, $timestamp);
        else
            return null;
    }
}




/**
 * Move an array item to a new index.
 *
 * @param  array  $a       The array to be modified.
 * @param  int    $oldpos  The original index position of item to be moved.
 * @param  int    $newpos  The new index position.
 *
 * @return array           The new modified array.
 */
function array_move(&$a, $oldpos, $newpos) {
    if ($oldpos == $newpos)
        return;

    array_splice($a, max($newpos, 0), 0, array_splice($a, max($oldpos, 0), 1));
}




/**
 * Convert a timestamp string into a relative time string.
 *
 * @param  string  $ts  The timestamp string.
 *
 * @return string       The relative-time formatted string.
 */
function timestamp_to_relative($ts) {
    if (!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;

    if ($diff == 0) {
        return 'now';
    } elseif ($diff > 0) {
        $day_diff = floor($diff / 86400);

        if ($day_diff == 0) {
            if ($diff < 60) return 'just now';
            if ($diff < 120) return '1 minute ago';
            if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if ($diff < 7200) return '1 hour ago';
            if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }

        if ($day_diff == 1) return 'yesterday';
        if ($day_diff < 7) return $day_diff . ' days ago';
        if ($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if ($day_diff < 60) return 'last month';

        return date('F Y', $ts);
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);

        if ($day_diff == 0) {
            if ($diff < 120) return 'in a minute';
            if ($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if ($diff < 7200) return 'in an hour';
            if ($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }

        if ($day_diff == 1) return 'tomorrow';
        if ($day_diff < 4) return date('l', $ts);
        if ($day_diff < 7 + (7 - date('w'))) return 'next week';
        if (ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if (date('n', $ts) == date('n') + 1) return 'next month';

        return date('F Y', $ts);
    }
}




if (!function_exists('display_data')) {
    function display_data($temp = '') {
        if($temp) {
            return $temp;
        } else {
            return ' - ';
        }
    }
}




if (!function_exists('crypto_rand_secure')) {
    function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);

        return $min + $rnd;
    }
}




if (!function_exists('generateToken')) {
    function generateToken($length = 100) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
        }
        return $token;
    }
}





function my_form_dropdown($data = '', $options = array(), $selected = array(), $disabled = array(), $hidden= array(), $extra = '')
{
    $defaults = array();

    if (is_array($data))
    {
        if (isset($data['selected']))
        {
            $selected = $data['selected'];
            unset($data['selected']); // select tags don't have a selected attribute
        }

        if (isset($data['options']))
        {
            $options = $data['options'];
            unset($data['options']); // select tags don't use an options attribute
        }

        if (isset($data['disabled']))
        {
            $disabled = $data['disabled'];
            unset($data['disabled']); // select tags don't use an disabled attribute
        }

        if (isset($data['hidden']))
        {
            $hidden = $data['hidden'];
            unset($data['hidden']); // select tags don't use an hidden attribute
        }
    }
    else
    {
        $defaults = array('name' => $data);
    }

    is_array($selected) OR $selected = array($selected);
    is_array($options) OR $options = array($options);
    is_array($disabled) OR $disabled = array($disabled);
    is_array($hidden) OR $hidden = array($hidden);

    // If no selected state was submitted we will attempt to set it automatically
    if (empty($selected))
    {
        if (is_array($data))
        {
            if (isset($data['name'], $_POST[$data['name']]))
            {
                $selected = array($_POST[$data['name']]);
            }
        }
        elseif (isset($_POST[$data]))
        {
            $selected = array($_POST[$data]);
        }
    }

    $extra = _attributes_to_string($extra);

    $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

    $form = '<select '.rtrim(_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

    foreach ($options as $key => $val)
    {
        $key = (string) $key;

        if (is_array($val))
        {
            if (empty($val))
            {
                continue;
            }

            $form .= '<optgroup label="'.$key."\">\n";

            foreach ($val as $optgroup_key => $optgroup_val)
            {
                $sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
                $dis = in_array($optgroup_key, $disabled) ? ' disabled' : '';
                $hid = in_array($optgroup_key, $hidden) ? ' hidden' : '';
                $form .= '<option value="'.html_escape($optgroup_key).'"'.$sel.$dis.$hid.'>'
                    .(string) $optgroup_val."</option>\n";
            }

            $form .= "</optgroup>\n";
        }
        else
        {
            $form .= '<option value="'.html_escape($key).'"'
                .(in_array($key, $selected) ? ' selected="selected"' : ''). (in_array($key, $disabled) ? ' disabled': ''). (in_array($key, $hidden) ? ' hidden': '').'>'
                .(string) $val."</option>\n";
        }
    }

    return $form."</select>\n";
}




function format_data_values($post, $column_name)
{
    $output = [];
    $item   = [];

    foreach ($post[$column_name] as $i => $key) {
        foreach($post as $attr => $val) {
            $item[$attr] = $val[$i];
        }

        if (count($item) > 0)
            array_push($output, $item);
    }

    return $output;
}




function array2csv(array &$array)
{
    if (count($array) == 0) {
        return null;
    }
    ob_start();

    $df = fopen("php://output", 'w');

    // fputcsv($df, array_keys(reset($array)));
    foreach ($array as $row) {
        fputcsv($df, $row);
    }

    fclose($df);

    return ob_get_clean();
}




function download_send_headers($filename)
{
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}




function calculate_date_diff($start, $end, $type='minute')
{
    if (empty($start) || empty($end) || ($start > $end)) {
        return 0;
    }

    $start_time = new Datetime($start);
    $end_time = new Datetime($end);

    $interval = $start_time->diff($end_time);

    $diff = 0;
    if ($type == 'minute') {
        $diff = $interval->days * 24 * 60;
        $diff += $interval->h * 60;
        $diff += $interval->i;
    }

    return $diff;
}




/**
 * Function that groups an array of associative arrays by some key.
 *
 * @param {String} $key Property to sort by.
 * @param {Array} $data Array that stores multiple associative arrays.
 */
function arr_group_by($key, $data) {
    $result = array();

    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        }else{
            $result[""][] = $val;
        }
    }

    return $result;
}




/**
 * Function that sort array of objects by ts_date.
 *
 * @param {Obj} $a
 * @param {Obj} $b
 */
function sort_object_arrays_by_ts_date($a, $b) {
    return $a->ts_date - $b->ts_date;
}


if (!function_exists('pdf_scripts')) {
    function pdf_scripts()
    {
        $output = [];

        array_push($output, '<script type="text/javascript" src="' . assets_url('js/plugins/pdfjs/pdf-min.js') . '"></script>');
        array_push($output, '<script type="text/javascript" src="' . assets_url('js/plugins/pdfjs/pdf.worker-min.js') . '"></script>');

        return implode('', $output);
    }
}


if (!function_exists('leading_zero')) {
	function leading_zero($number, $digits) {
		return str_pad($number, $digits, '0', STR_PAD_LEFT);
	}
}


if (!function_exists('clean_formated_money')) {
	function clean_formated_money($formated_money) {
		return preg_replace('/[^\p{L}\p{N}\s]/u', '', $formated_money);
	}
}


if (!function_exists('get_ip')) {
	function get_ip() {
		// return $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		return $ip = $_SERVER['REMOTE_ADDR'];
	}
}

if (!function_exists('clean_url')) {
    function clean_url($url) {
        $parsedUrl = parse_url($url, PHP_URL_HOST);

        if (is_null($parsedUrl)) {
            $parsedUrl = parse_url('http://' . $url, PHP_URL_HOST);
        }

        if (strpos($parsedUrl, 'www.') === 0) {
            $parsedUrl = substr($parsedUrl, 4);
        }

        return $parsedUrl;
    }

}
