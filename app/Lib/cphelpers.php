<?php



function rmkdir($path){
    return mkdir($path,0755,true);
}

function validateFolder($folder){
    $path = UPLOAD_PATH.'/'.$folder;
    if(!file_exists($path)){
        rmkdir($path);
    }
}

function salaryType($type){
    if($type=='m'){
        return __('site.per-month');
    }
    elseif($type=='a'){
        return __('site.per-annum');
    }
    else{
        return '';
    }
}

function tview($view,$data =[],$merge=[]){

    if(setting('frontend_status')=='0'){
        return view($view,$data,$merge);
    }

    $currentTemplate = \App\Template::where('enabled',1)->first();
    $path=$currentTemplate->directory.'.views.'.$view;

    if(!view()->exists($path)){
        $path = $view;
    }
    return view($path,$data,$merge);
}

function tasset($path,$secure=null){
    $currentTemplate = \App\Template::where('enabled',1)->first();
    $path= TEMPLATE_PATH.'/'.$currentTemplate->directory.'/assets/'.$path;
    return asset($path,$secure);
}

function toption($option,$field){
    $currentTemplate = \App\Template::where('enabled',1)->first();

    $optionRow = $currentTemplate->templateOptions()->where('name',$option)->first();
    if(!$optionRow){
        return '';
    }

    $value = $optionRow->value;

    $values = sunserialize($value);
    if(isset($values[$field])){
        return $values[$field];
    }
    else{
        return '';
    }

}

function sunserialize($value){
    $value = preg_replace_callback(
        '!s:(\d+):"(.*?)";!',
        function($m) {
            return 's:'.strlen($m[2]).':"'.$m[2].'";';
        },
        $value);
    return unserialize($value);
}

function optionActive($option){
    $currentTemplate = \App\Template::where('enabled',1)->first();
    $option =$currentTemplate->templateOptions()->where('name',$option)->first();
    if(!$option){
        return false;
    }
    $enabled = $option->enabled;
    return $enabled==1;
}

function newLinesToArray($content){
    $list = preg_split('/\r\n|\r|\n/', $content);
    return $list;
}

function phoneUser($number){
    return \App\User::where('telephone',$number)->first();
}

function getPhoneNumber($text){

    //remove any whitespace
    $text = str_replace(' ','',$text);
    $text = trim($text);
    $text = str_replace(',','',$text);

    $array = str_split($text);
    $amount = array();
    $counter = 0;
    foreach ($array as $value)
    {
        if (is_numeric($value) || $value=='+')
        {
            @$amount[$counter] .= $value;
        }
        else
        {
            //$counter++;
        }


    }

    $price = @$amount[0];
    return purgeNumber($price);
}

function purgeNumber($number){
    $number = str_ireplace('+undefined0','0',$number);
    $number = str_ireplace('+undefined','0',$number);
    return $number;
}
function isImage($path)
{
    if(empty($path) || !file_exists($path)){
        return false;
    }
    $a = getimagesize($path);

    if (!is_array($a)){
        return false;
    }

    $image_type = $a[2];

    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        return true;
    }
    return false;
}

function boolToString($val){
    return (empty($val)) ? __('site.no'):__('site.yes');
}

function stringToBool($val){
    $val = strtolower(trim($val));
    if($val=='yes'){
        return 1;
    }
    else{
        return 0;
    }
}

function is_decimal( $val )
{
    return is_numeric( $val ) && floor( $val ) != $val;
}

function saveInlineImages($html){
    if(empty($html)){
        return $html;
    }
    $savePath = UPLOAD_PATH.'/'.EDITOR_IMAGES.'/'.date('m_Y');
    $saveUrl = url('/').'/'.$savePath;
    if(!file_exists($savePath)){
        mkdir($savePath,0777, true);
    }
    $dom = new \DOMDocument();

    //  @$dom->loadHTML($html);
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    foreach($dom->getElementsByTagName('img') as $element){
        //This selects all elements
        $data = $element->getAttribute('src');



        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                throw new \Exception('invalid image type');
            }

            $data = base64_decode($data);

            if ($data === false) {
                continue;
            }

            $fileName = time().rand(100,10000);
            file_put_contents($savePath."/{$fileName}.{$type}", $data);
            $element->setAttribute('src',$saveUrl.'/'.$fileName.'.'.$type);

        } else {
            continue;
        }



    }

    $body = "";
    foreach($dom->getElementsByTagName("body")->item(0)->childNodes as $child) {
        $body .= $dom->saveHTML($child);
    }

    return $body;


}

function saveSaasInlineImages($html){
    if(empty($html)){
        return $html;
    }
    $savePath = 'saas_uploads/editor_images/'.date('m_Y');
    $saveUrl = url('/').'/'.$savePath;
    if(!file_exists($savePath)){
        mkdir($savePath,0777, true);
    }
    $dom = new \DOMDocument();

    @$dom->loadHTML($html);
    foreach($dom->getElementsByTagName('img') as $element){
        //This selects all elements
        $data = $element->getAttribute('src');



        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                throw new \Exception('invalid image type');
            }

            $data = base64_decode($data);

            if ($data === false) {
                continue;
            }

            $fileName = time().rand(100,10000);
            file_put_contents($savePath."/{$fileName}.{$type}", $data);
            $element->setAttribute('src',$saveUrl.'/'.$fileName.'.'.$type);

        } else {
            continue;
        }



    }

    $body = "";
    foreach($dom->getElementsByTagName("body")->item(0)->childNodes as $child) {
        $body .= $dom->saveHTML($child);
    }

    return $body;


}


function prevPage(){
    if(isset($_SERVER['HTTP_REFERER']))
    {
        return  $_SERVER['HTTP_REFERER'];
    }
    else{
        return 'javascript:history.go(-1)';
    }

}

function removeDirectory($path) {
    if(!is_dir($path)){
        return false;
    }
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);
    return true;
}

function selfURL() {
    $s = empty($_SERVER["HTTPS"]) ? '' : (($_SERVER["HTTPS"] == "on") ? "s" : "");
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}

function safeUrl($url) {

    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    return $url;
}

function safeFile($path){

    $info = pathinfo($path);

    $file = safeUrl($info['filename']);


    return $file.'.'.$info['extension'];
}

function uniqueName($path){
    $info = pathinfo($path);

    return uniqid().uniqid().'.'.$info['extension'];
}

function getCronUrl($url){
    try{
        ini_set('default_socket_timeout',1);
        file_get_contents($url);
    }
    catch(\Exception $ex)
    {

    }
}

function get_domain($url)
{
    try{
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
    }
    catch(\Exception $ex){

    }

    return false;
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}

function limitLength($string,$maxLength){
    $string = strip_tags($string);
    if(strlen($string) <= $maxLength){
        return strip_tags($string);
    }
    else{
        return strip_tags(substr($string,0,$maxLength).'...');
    }

}

function br2nl($text){

    $breaks = array("<br />","<br>","<br/>");
    $text = str_ireplace($breaks, "\r\n", $text);
    return $text;
}

function resizeImage($filename, $width, $height,$basePath) {

    $dirImage = 'public/tmp/';
    $baseDir = 'public/';
    if (!file_exists($baseDir . $filename) || !is_file($baseDir . $filename)) {

        return;
    }


    $info = pathinfo($filename);

    $extension = $info['extension'];

    $old_image = $filename;
    $new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

    if (!file_exists($dirImage . $new_image) || (filemtime($baseDir . $old_image) > filemtime($dirImage . $new_image))) {
        $path = '';

        $directories = explode('/', dirname(str_replace('../', '', $new_image)));

        foreach ($directories as $directory) {
            $path = $path . '/' . $directory;

            if (!file_exists($dirImage . $path)) {
                @mkdir($dirImage . $path, 0777);
            }
        }

        $image = new \App\Lib\Image($baseDir . $old_image);

        $image->resize($width, $height);
        $image->save($dirImage . $new_image);
    }


    return $basePath.'/tmp/'. $new_image;
}

function avatar($gender){
    if($gender=='m'){
        return asset('img/man.jpg');
    }
    else{
        return asset('img/woman.jpg');
    }
}


function userPic($userId){
    $user = \App\User::find($userId);
    if($user->role_id==3 && $user->candidate()->exists() && !empty($user->candidate->picture) && file_exists($user->candidate->picture)){
        return asset($user->candidate->picture);
    }
    elseif($user->role_id==3 && $user->candidate()->exists()){
        return avatar($user->candidate->gender);
    }
    else{
        return asset('img/man.jpg');
    }
}


function gender($gender){
    if($gender=='m'){
        return __('site.male');
    }
    else{
        return __('site.female');
    }
}

function setting($key){

    $setting = \App\Setting::where('key',trim(strtolower($key)))->first();

    if($setting){
        return trim($setting->value);
    }
    else{
        return false;
    }
}

function getFileMimeType($file){
    if(!function_exists('mime_content_type')) {

        function mime_content_type($filename) {

            $mime_types = array(

                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',

                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',

                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',

                // audio/video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',

                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',

                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',

                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            );

            $ext = strtolower(array_pop(explode('.',$filename)));
            if (array_key_exists($ext, $mime_types)) {
                return $mime_types[$ext];
            }
            elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mimetype = finfo_file($finfo, $filename);
                finfo_close($finfo);
                return $mimetype;
            }
            else {
                return 'application/octet-stream';
            }
        }
    }
    return mime_content_type($file);
}


function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        return false;
        //throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function getDepartment(){
    $id = session('department');

    if(empty($id) || !\App\Department::find($id)){
        return false;
    }

    return  \App\Department::find($id);
}

function isDeptAdmin($user){
    $admin = $user->departments()->where('department_id',getDepartment()->id)->first()->pivot->department_admin;

    if($admin==1){
        return true;
    }
    else{
        return false;
    }
}

function filesize_r($path){
    if(!file_exists($path)) return 0;
    if(is_file($path)) return filesize($path);
    $ret = 0;
    foreach(glob($path."/*") as $fn)
        $ret += filesize_r($fn);
    return $ret;
}

function human_filesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

/*function price($amount){
    return number_format($amount,2);
}*/

function limit($limit){
    return empty($limit)? __('saas.unlimited'):$limit;

}

function price($amount){

    $amount = floatval(strip_tags($amount));

    if(is_decimal($amount)){
        return setting('general_currency_symbol').number_format($amount,2);
    }
    else{
        return setting('general_currency_symbol').number_format($amount);
    }

}


function showDate($format,$timestamp){
    if(!empty($timestamp)){
        return date($format,$timestamp);
    }
    else{
        return '';
    }
}

function paymentSetting($methodId,$key){

    $value = \App\PaymentMethodField::where('payment_method_id',$methodId)->where('key',$key)->first()->value;
    return $value;
}

function getPaypalClient(){
    $clientId = paymentSetting(1,'client_id');
    $secret = paymentSetting(1,'secret');

    $paypal = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            $clientId,
            $secret
        )
    );

    return $paypal;
}

function getMonthStr($offset)
{
    return date("M", strtotime("$offset months"));
}


function getPageAsync($url, $params = array('noparam'=>'true'), $type='GET')
{
    //echo "Attempting to get $url <br/>";

    $post_params= [];
    foreach ($params as $key => $val) {
        if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);

    $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);

    if(isset($parts['query'])){


        $cusParams = $parts['query'];

        // Data goes in the path for a GET request
        if('GET' == $type) $parts['path'] .= '?'.$cusParams;
    }
    $out = "$type ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    // Data goes in the request body for a POST request
    if ('POST' == $type && isset($post_string)) $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}


function sanitizeInput($html){

    $dom = new DOMDocument();

    $dom->loadHTML($html);

    $script = $dom->getElementsByTagName('script');

    $remove = [];
    foreach($script as $item)
    {
        $remove[] = $item;
    }

    foreach ($remove as $item)
    {
        $item->parentNode->removeChild($item);
    }

    $html = $dom->saveHTML();
    return $html;
}


function getAge($birth){
    $t = time();
    $age = ($birth < 0) ? ( $t + ($birth * -1) ) : $t - $birth;
    return floor($age/31536000);
}


function userType($user){
    if($user->role_id==2){
        return 'employer';
    }
    elseif($user->role_id==3){
        return 'candidate';
    }
    else{
        return 'administrator';
    }
}

function userLink($user){
    if($user->role_id==2){
        return route('admin.employers.show',['employer'=>$user->id]);
    }
    elseif($user->role_id==3){
        return route('admin.candidates.show',['candidate'=>$user->id]);
    }
    else{
        return route('admin.admins.show',['admin'=>$user->id]);
    }
}

function roleName($roleId){
    if($roleId==2){
        return __('site.employer');
    }
    elseif($roleId==3){
        return __('site.candidate');
    }
    else{
        return __('site.administrator');
    }
}
function orderStatus($status){

    switch($status){
        case 'p':
            return __('site.pending');
            break;
        case 'i':
            return __('site.in-progress');
            break;
        case 'c':
            return __('site.completed');
            break;
        case 'x':
            return __('site.cancelled');
            break;
    }

}

function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' '.__('site.and').' ';
    $separator   = ', ';
    $negative    = __('site.negative').' ';
    $decimal     = ' '.__('site.point').' ';
    $dictionary  = array(
        0                   => __('site.zero'),
        1                   => __('site.one'),
        2                   => __('site.two'),
        3                   => __('site.three'),
        4                   => __('site.four'),
        5                   => __('site.five'),
        6                   => __('site.six'),
        7                   => __('site.seven'),
        8                   => __('site.eight'),
        9                   => __('site.nine'),
        10                  => __('site.ten'),
        11                  => __('site.eleven'),
        12                  => __('site.twelve'),
        13                  => __('site.thirteen'),
        14                  => __('site.fourteen'),
        15                  => __('site.fifteen'),
        16                  => __('site.sixteen'),
        17                  => __('site.seventeen'),
        18                  => __('site.eighteen'),
        19                  => __('site.nineteen'),
        20                  => __('site.twenty'),
        30                  => __('site.thirty'),
        40                  => __('site.fourty'),
        50                  => __('site.fifty'),
        60                  => __('site.sixty'),
        70                  => __('site.seventy'),
        80                  => __('site.eighty'),
        90                  => __('site.ninety'),
        100                 => __('site.hundred'),
        1000                => __('site.thousand'),
        1000000             => __('site.million'),
        1000000000          => __('site.billion'),
        1000000000000       => __('site.trillion'),
        1000000000000000    => __('site.quadrillion'),
        1000000000000000000 => __('site.quintillion'),
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}


function getDirectoryContents($path){
    $list = scandir($path);
    $newList = [];
    foreach($list as $item){
        if($item!='.' && $item != '..'){
            $newList[] = $item;
        }
    }

    return $newList;
}

function tlang($dir,$key){
    return 'temp_'.$dir.'.'.$key;
}

function __t($key){
    return __(tlang(currentTemplate()->directory,$key));
}

function templateInfo($dir){
    $info = include "templates/{$dir}/info.php";

    return $info;
}

function currentTemplate(){
    $currentTemplate = \App\Template::where('enabled',1)->first();
    return $currentTemplate;
}

function headerMenu(){

    $items= \App\HeaderMenu::where('parent_id',0)->orderBy('sort_order')->get();
    $menu = [];
    $counter = 0;
    foreach($items as $item){
        $menu[$counter] = [
            'label'=> $item->label,
            'url'=> url($item->url)
        ];
        //check for children
        $children = \App\HeaderMenu::where('parent_id',$item->id)->orderBy('sort_order')->get();
        if($children->count()>0){
            foreach($children as $child){
                $menu[$counter]['children'][] = [
                    'label'=> $child->label,
                    'url'=> url($child->url)
                ];
            }

        }
        else{
            $menu[$counter]['children'] = false;
        }

        $counter++;
    }

    return $menu;

}

function footerMenu(){

    $items= \App\FooterMenu::where('parent_id',0)->orderBy('sort_order')->get();
    $menu = [];
    $counter = 0;
    foreach($items as $item){
        $menu[$counter] = [
            'label'=> $item->label,
            'url'=> url($item->url)
        ];
        //check for children
        $children = \App\FooterMenu::where('parent_id',$item->id)->orderBy('sort_order')->get();
        if($children->count()>0){
            foreach($children as $child){
                $menu[$counter]['children'][] = [
                    'label'=> $child->label,
                    'url'=> url($child->url)
                ];
            }

        }
        else{
            $menu[$counter]['children'] = false;
        }

        $counter++;
    }

    return $menu;
}

function getCart(){
    if(!session()->exists('cart')){
        return ['total'=>0,'candidates'=>[]];
    }

    $cart= session()->get('cart');

    if(!is_array($cart)){
        return ['total'=>0,'candidates'=>[]];
    }

    $candidates = [];
    foreach($cart as $value){
        if(\App\Candidate::find($value)){
            $candidates[] = \App\Candidate::find($value);
        }

    }

    return [
        'total'=> count($candidates),
        'candidates'=> $candidates
    ];

}

function getLoginToken($userId){
    $user = \App\User::findOrFail($userId);

    if(empty($user->login_token) || \Illuminate\Support\Carbon::parse($user->login_token_expires)->lt(\Illuminate\Support\Carbon::now())  ){
        do{
            $token = \Illuminate\Support\Str::random(20);
        }while(\App\User::where('login_token',$token)->count()>0);

        $user->login_token = $token;
        $current = \Illuminate\Support\Carbon::now();
        $user->login_token_expires = $current->addDays(30)->toDateString();
        $user->save();
    }

    return 'login-token='.$user->login_token;

}

/**
 * Returns the size of a file without downloading it, or -1 if the file
 * size could not be determined.
 *
 * @param $url - The location of the remote file to download. Cannot
 * be null or empty.
 *
 * @return The size of the file referenced by $url, or -1 if the size
 * could not be determined.
 */
function curl_get_file_size( $url ) {
//Create a cURL handle with the URL of
//the remote file.
    $curl = curl_init($url);

//Set CURLOPT_FOLLOWLOCATION to TRUE so that our
//cURL request follows any redirects.
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

//We want curl_exec to return  the output as a string.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//Set CURLOPT_HEADER to TRUE so that cURL returns
//the header information.
    curl_setopt($curl, CURLOPT_HEADER, true);

//Set CURLOPT_NOBODY to TRUE to send a HEAD request.
//This stops cURL from downloading the entire body
//of the content.
    curl_setopt($curl, CURLOPT_NOBODY, true);

//Execute the request.
    curl_exec($curl);

//Retrieve the size of the remote file in bytes.
    $fileSize = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    return $fileSize;
}


function saas(){
    $mode = config('app.mode');
    if($mode=='saas'){
        return true;
    }
    else{
        return false;
    }
}

function credits(){
    if(saas()){
        return '';
    //    return 'Powered by <a target="_blank" href="'.env('APP_URL').'">'.env('APP_NAME').'</a>';
    }
    else{
        return '';
    }

}

function fullstop($text){
    if(empty($text) || substr($text, -1)=='.'){
        return $text;
    }
else{
    return $text.'.';
}

}

function latestPosts($limit){
    $recent = \App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('status',1)->orderBy('publish_date','desc')->limit(5)->get();
    return $recent;
}

function check($val){
    return $val;
}

function paymentInfo($dir){
    $info = include PAYMENT_PATH."/{$dir}/info.php";

    return $info;
}

function smsInfo($dir){
    $info = include MESSAGING_PATH."/{$dir}/info.php";
    return $info;
}

function __lang($key, $params=[]){
    $key = str_ireplace(' ','-',$key);
    $key = strtolower($key);

    return __('site.'.$key,$params);
}

function paymentOption($directory,$option){
    $gateway = \App\PaymentMethod::where('code',$directory)->first();
    if(!$gateway){
        return false;
    }
    $data = unserialize($gateway->settings);
    if (isset($data[$option])){
        return $data[$option];
    }
    else{
        return false;
    }
}

function messagingOption($directory,$option){
    $gateway = \App\SmsGateway::where('code',$directory)->first();
    if(!$gateway){
        return false;
    }
    $data = unserialize($gateway->settings);
    if (isset($data[$option])){
        return $data[$option];
    }
    else{
        return false;
    }
}

function approveInvoice(\App\Invoice $invoice){
    $invoice->paid = 1;
    $invoice->save();

    $title = __('site.invoice-approved');
    $message = __('site.invoice-approved-msg',['invoiceId'=>$invoice->id]);
    sendEmail($invoice->user->email,$title,$message);
    session()->forget('invoice');
    return $title;
}

function extract_emails($str){
    // This regular expression extracts all emails from a string:
    $regexp = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
    preg_match_all($regexp, $str, $m);

    $emails= isset($m[0]) ? $m[0] : array();
    $newEmails = [];
    foreach($emails as $key=>$value){
        $newEmails[$value] = $value;
    }

    if(count($newEmails)>0){
        $addresses = implode(' , ',$newEmails);
        return $addresses;
    }
    else{
        return null;
    }


}


function sendEmail($recipientEmail,$subject,$message,$from=null,$cc=null,$attachments=null){

    $cc = extract_emails($cc);
    try{

        if(!empty($cc)){

            //generate array from cc
            $ccArray = explode(',',$cc);
            $allCC = [];
            foreach($ccArray as $key=>$value){
                $value = trim($value);
                $validator = \Illuminate\Support\Facades\Validator::make(['email'=>$value],['email'=>'email']);

                if(!$validator->fails()){
                    $allCC[] = $value;
                }

            }

            \Illuminate\Support\Facades\Mail::to($recipientEmail)->cc($allCC)->send(New \App\Mail\Generic($subject,$message,$from,$attachments));
        }
        else{
            \Illuminate\Support\Facades\Mail::to($recipientEmail)->send(New \App\Mail\Generic($subject,$message,$from,$attachments));
        }
        return true;



    }
    catch(\Exception $ex){
        //  dd($ex);
        warningMessage(__('site.send-failed').': '.$ex->getMessage());
        return false;
    }

}

function warningMessage($message){
    request()->session()->flash('alert-warning', $message);
}
function successMessage($message){
    request()->session()->flash('alert-success', $message);
}
function errorMessage($message){
    request()->session()->flash('alert-danger', $message);
}

function flashMessage($message){
    session()->flash('flash_message',$message);
}

function getCountryDialCode($countryCode){
    $countryArray = array(
        'AD'=>array('name'=>'ANDORRA','code'=>'376'),
        'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
        'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
        'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
        'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
        'AL'=>array('name'=>'ALBANIA','code'=>'355'),
        'AM'=>array('name'=>'ARMENIA','code'=>'374'),
        'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
        'AO'=>array('name'=>'ANGOLA','code'=>'244'),
        'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
        'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
        'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
        'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
        'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
        'AW'=>array('name'=>'ARUBA','code'=>'297'),
        'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
        'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
        'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
        'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
        'BE'=>array('name'=>'BELGIUM','code'=>'32'),
        'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
        'BG'=>array('name'=>'BULGARIA','code'=>'359'),
        'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
        'BI'=>array('name'=>'BURUNDI','code'=>'257'),
        'BJ'=>array('name'=>'BENIN','code'=>'229'),
        'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
        'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
        'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
        'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
        'BR'=>array('name'=>'BRAZIL','code'=>'55'),
        'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
        'BT'=>array('name'=>'BHUTAN','code'=>'975'),
        'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
        'BY'=>array('name'=>'BELARUS','code'=>'375'),
        'BZ'=>array('name'=>'BELIZE','code'=>'501'),
        'CA'=>array('name'=>'CANADA','code'=>'1'),
        'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
        'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
        'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
        'CG'=>array('name'=>'CONGO','code'=>'242'),
        'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
        'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
        'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
        'CL'=>array('name'=>'CHILE','code'=>'56'),
        'CM'=>array('name'=>'CAMEROON','code'=>'237'),
        'CN'=>array('name'=>'CHINA','code'=>'86'),
        'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
        'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
        'CU'=>array('name'=>'CUBA','code'=>'53'),
        'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
        'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
        'CY'=>array('name'=>'CYPRUS','code'=>'357'),
        'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
        'DE'=>array('name'=>'GERMANY','code'=>'49'),
        'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
        'DK'=>array('name'=>'DENMARK','code'=>'45'),
        'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
        'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
        'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
        'EC'=>array('name'=>'ECUADOR','code'=>'593'),
        'EE'=>array('name'=>'ESTONIA','code'=>'372'),
        'EG'=>array('name'=>'EGYPT','code'=>'20'),
        'ER'=>array('name'=>'ERITREA','code'=>'291'),
        'ES'=>array('name'=>'SPAIN','code'=>'34'),
        'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
        'FI'=>array('name'=>'FINLAND','code'=>'358'),
        'FJ'=>array('name'=>'FIJI','code'=>'679'),
        'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
        'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
        'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
        'FR'=>array('name'=>'FRANCE','code'=>'33'),
        'GA'=>array('name'=>'GABON','code'=>'241'),
        'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
        'GD'=>array('name'=>'GRENADA','code'=>'1473'),
        'GE'=>array('name'=>'GEORGIA','code'=>'995'),
        'GH'=>array('name'=>'GHANA','code'=>'233'),
        'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
        'GL'=>array('name'=>'GREENLAND','code'=>'299'),
        'GM'=>array('name'=>'GAMBIA','code'=>'220'),
        'GN'=>array('name'=>'GUINEA','code'=>'224'),
        'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
        'GR'=>array('name'=>'GREECE','code'=>'30'),
        'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
        'GU'=>array('name'=>'GUAM','code'=>'1671'),
        'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
        'GY'=>array('name'=>'GUYANA','code'=>'592'),
        'HK'=>array('name'=>'HONG KONG','code'=>'852'),
        'HN'=>array('name'=>'HONDURAS','code'=>'504'),
        'HR'=>array('name'=>'CROATIA','code'=>'385'),
        'HT'=>array('name'=>'HAITI','code'=>'509'),
        'HU'=>array('name'=>'HUNGARY','code'=>'36'),
        'ID'=>array('name'=>'INDONESIA','code'=>'62'),
        'IE'=>array('name'=>'IRELAND','code'=>'353'),
        'IL'=>array('name'=>'ISRAEL','code'=>'972'),
        'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
        'IN'=>array('name'=>'INDIA','code'=>'91'),
        'IQ'=>array('name'=>'IRAQ','code'=>'964'),
        'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
        'IS'=>array('name'=>'ICELAND','code'=>'354'),
        'IT'=>array('name'=>'ITALY','code'=>'39'),
        'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
        'JO'=>array('name'=>'JORDAN','code'=>'962'),
        'JP'=>array('name'=>'JAPAN','code'=>'81'),
        'KE'=>array('name'=>'KENYA','code'=>'254'),
        'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
        'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
        'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
        'KM'=>array('name'=>'COMOROS','code'=>'269'),
        'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
        'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
        'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
        'KW'=>array('name'=>'KUWAIT','code'=>'965'),
        'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
        'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
        'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
        'LB'=>array('name'=>'LEBANON','code'=>'961'),
        'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
        'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
        'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
        'LR'=>array('name'=>'LIBERIA','code'=>'231'),
        'LS'=>array('name'=>'LESOTHO','code'=>'266'),
        'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
        'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
        'LV'=>array('name'=>'LATVIA','code'=>'371'),
        'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
        'MA'=>array('name'=>'MOROCCO','code'=>'212'),
        'MC'=>array('name'=>'MONACO','code'=>'377'),
        'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
        'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
        'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
        'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
        'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
        'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
        'ML'=>array('name'=>'MALI','code'=>'223'),
        'MM'=>array('name'=>'MYANMAR','code'=>'95'),
        'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
        'MO'=>array('name'=>'MACAU','code'=>'853'),
        'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
        'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
        'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
        'MT'=>array('name'=>'MALTA','code'=>'356'),
        'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
        'MV'=>array('name'=>'MALDIVES','code'=>'960'),
        'MW'=>array('name'=>'MALAWI','code'=>'265'),
        'MX'=>array('name'=>'MEXICO','code'=>'52'),
        'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
        'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
        'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
        'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
        'NE'=>array('name'=>'NIGER','code'=>'227'),
        'NG'=>array('name'=>'NIGERIA','code'=>'234'),
        'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
        'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
        'NO'=>array('name'=>'NORWAY','code'=>'47'),
        'NP'=>array('name'=>'NEPAL','code'=>'977'),
        'NR'=>array('name'=>'NAURU','code'=>'674'),
        'NU'=>array('name'=>'NIUE','code'=>'683'),
        'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
        'OM'=>array('name'=>'OMAN','code'=>'968'),
        'PA'=>array('name'=>'PANAMA','code'=>'507'),
        'PE'=>array('name'=>'PERU','code'=>'51'),
        'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
        'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
        'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
        'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
        'PL'=>array('name'=>'POLAND','code'=>'48'),
        'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
        'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
        'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
        'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
        'PW'=>array('name'=>'PALAU','code'=>'680'),
        'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
        'QA'=>array('name'=>'QATAR','code'=>'974'),
        'RO'=>array('name'=>'ROMANIA','code'=>'40'),
        'RS'=>array('name'=>'SERBIA','code'=>'381'),
        'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
        'RW'=>array('name'=>'RWANDA','code'=>'250'),
        'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
        'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
        'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
        'SD'=>array('name'=>'SUDAN','code'=>'249'),
        'SE'=>array('name'=>'SWEDEN','code'=>'46'),
        'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
        'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
        'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
        'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
        'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
        'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
        'SN'=>array('name'=>'SENEGAL','code'=>'221'),
        'SO'=>array('name'=>'SOMALIA','code'=>'252'),
        'SR'=>array('name'=>'SURINAME','code'=>'597'),
        'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
        'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
        'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
        'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
        'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
        'TD'=>array('name'=>'CHAD','code'=>'235'),
        'TG'=>array('name'=>'TOGO','code'=>'228'),
        'TH'=>array('name'=>'THAILAND','code'=>'66'),
        'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
        'TK'=>array('name'=>'TOKELAU','code'=>'690'),
        'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
        'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
        'TN'=>array('name'=>'TUNISIA','code'=>'216'),
        'TO'=>array('name'=>'TONGA','code'=>'676'),
        'TR'=>array('name'=>'TURKEY','code'=>'90'),
        'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
        'TV'=>array('name'=>'TUVALU','code'=>'688'),
        'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
        'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
        'UA'=>array('name'=>'UKRAINE','code'=>'380'),
        'UG'=>array('name'=>'UGANDA','code'=>'256'),
        'US'=>array('name'=>'UNITED STATES','code'=>'1'),
        'UY'=>array('name'=>'URUGUAY','code'=>'598'),
        'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
        'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
        'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
        'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
        'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
        'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
        'VN'=>array('name'=>'VIET NAM','code'=>'84'),
        'VU'=>array('name'=>'VANUATU','code'=>'678'),
        'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
        'WS'=>array('name'=>'SAMOA','code'=>'685'),
        'XK'=>array('name'=>'KOSOVO','code'=>'381'),
        'YE'=>array('name'=>'YEMEN','code'=>'967'),
        'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
        'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
        'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
        'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
    );

    $countryCode = strtoupper($countryCode);
    return $countryArray[$countryCode]['code'];
}

function carepro_parseXMLToArray($xml)
{
    if($xml->count() <= 0)
        return false;

    $data = array();
    foreach ($xml as $element) {
        if($element->children()) {
            foreach ($element as $child) {
                if($child->attributes()) {
                    foreach ($child->attributes() as $key => $value) {
                        $data[$element->getName()][$child->getName()][$key] = $value->__toString();
                    }
                } else {
                    $data[$element->getName()][$child->getName()] = $child->__toString();
                }
            }
        } else {
            $data[$element->getName()] = $element->__toString();
        }
    }
    return $data;
}

function getClientIp() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function billingAddress(){
    $user = Illuminate\Support\Facades\Auth::user();
    $addresses = $user->billingAddresses()->count();
    if(empty($addresses)){
        return false;
    }
    elseif(session('billing_address')){
        $addressId = session('billing_address');
        try{
            $address = $user->billingAddresses()->where('id',$addressId)->firstOrFail();
            return $address;
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex){

            $address = $user->billingAddresses()->first();
            return $address;
        }
    }
    else{
        //get the default address
        try{
            $address = $user->billingAddresses()->where('is_default',1)->firstOrFail();
            return $address;
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
            $address = $user->billingAddresses()->first();
            return $address;
        }

    }
}

function sendSms($gateway=null,$recipients,$message){


    $gateway = \App\SmsGateway::findOrFail($gateway);



    $code = $gateway->code;
    $file = 'gateways/messaging/'.$code.'/functions.php';
    if (file_exists($file)){
        require_once($file);
    }
    else{
        return false;
    }

    if (!function_exists('carepro_send')){
        flashMessage(__lang('invalid-gateway'));
        return false;
    }

    return carepro_send($message,$recipients);
}


function isEmployer(){
    if(!\Illuminate\Support\Facades\Auth::check()){
        return false;
    }
    $user  = \Illuminate\Support\Facades\Auth::user();
    return $user->role_id==2;
}

function isCandidate(){
    if(!\Illuminate\Support\Facades\Auth::check()){
        return false;
    }
    $user  = \Illuminate\Support\Facades\Auth::user();
    return $user->role_id==3;
}

