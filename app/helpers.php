<?php


use App\Mail\MailAdminNotification;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\UserBalanceHistory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illumninate\Support\Facades\Auth;
use App\Models\GoogleIndexUrl;
use App\Models\Page;
use App\Models\SellerSite;
use App\Models\Links;
use App\Models\PageMetricHistory;
use App\Jobs\ProcessBulkPageMyAddrAPI;
use App\Jobs\CalculatePageOBLJob;
use App\Jobs\FindPageCategories;
use App\Jobs\AddPageDigiMetr;
use App\Jobs\CheckSitePagesIndexed;
use App\Jobs\CheckPageIndexed;
use App\Jobs\FindPageSEORankMyAddr;
use App\Models\Batch;
use Illuminate\Support\Facades\Log;



if (!function_exists("get_http_code")) {
    function get_http_code($url)
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response['content'] = curl_exec($handle);
        $response['httpCode'] = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        return $response;
    }
}
if (!function_exists('add_seo_rank_url_quey')) {
    function add_seo_rank_url_quey($site_page_url)
    {
        $api = env('SEORANKAPI');
        $apiurl = env('SEORANKURL');

        /* $ch = curl_init();
         $url = $apiurl.'api3/'.$api.'/'.$site_page_url;
         $curlConfig = array(
              CURLOPT_URL            => $url,
              CURLOPT_POST           => true,
              CURLOPT_RETURNTRANSFER => true
          );
          curl_setopt_array($ch, $curlConfig);
          $result = curl_exec($ch);
          curl_close($ch);
     */

        $ch = curl_init();
        $url = $apiurl . 'api2/moz/' . $api . '/' . $site_page_url;
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
    }
}

if (!function_exists('add_links_seller_blog')) {
    function add_links_seller_blog($page_id)
    {
        $page = Page::where('id', '=', $page_id)->first();

        if (isset($page)) {

            $header = array();
            $header['Siteauthkey'] = $page->site->site_auth_key;
            $params = array();
            $params['content_id'] = $page->seller_site_page_id;

            $links = Links::where('status', '=', 20)->where('page_id', '=', $page_id)->orderBy("id", "asc")->get();
            $i = 0;
            $orderContent = array();
            foreach ($links as $link) {
                $orderContent[$i]['link_content'] = $link->link_content;
                $orderContent[$i]['promoted_url'] = $link->promoted_url;
                $orderContent[$i]['anchor_text'] = $link->anchor_text;
                $orderContent[$i]['nofollow'] = $link->no_follow;
                $i++;
            }
            $params['content'] = json_encode($orderContent);
            $client = new \GuzzleHttp\Client(['verify' => false]);
            try {
                $response = $client->post($page->site->site_url . "/wp-json/wp/v2/update-posts-pages-orders", array(
                    'form_params' => $params,
                    'headers' => $header
                ));
                $result = json_decode($response->getBody()->getContents());
                if ($result->status == "success") {
                    return "success";
                } else {
                    return $result->msg;
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                return $e->getMessage();
            }
        }

    }
}

if (!function_exists('add_seo_rank_url_file_upload')) {
    function add_seo_rank_url_file_upload($file_name)
    {
        $api = env('SEORANKAPI');
        $apiurl = env('SEORANKURL');

        $ch = curl_init();
        $url = $apiurl . 'upload_file.php?secret=' . $api . '&filename=' . $file_name . '&moz=1&alexa=1';

        if (function_exists('curl_file_create')) $f = curl_file_create(Storage::path($file_name));
        else $f = '@' . Storage::path($file_name);
        $settings['mainfile'] = $f;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $settings);
        $moz_file_id = curl_exec($ch); //you need to save this FILE_ID for get file status and download reports in future
        curl_close($ch);

        /*$ch = curl_init();
        $url = $apiurl.'upload_file_majestic.php?secret='.$api.'&filename='.$file_name;

        if (function_exists('curl_file_create')) $f = curl_file_create(Storage::path($file_name));
        else $f = '@'.Storage::path($file_name);
        $settings['mainfile'] = $f;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $settings);
        $maj_file_id = curl_exec($ch); //you need to save this FILE_ID for get file status and download reports in future
        curl_close($ch);*/

        return ['moz_file_id' => $moz_file_id];
    }
}

if (!function_exists('add_google_index_url_quey')) {
    function add_google_index_url_quey($site_url, $site_id)
    {

        $api = env('SCRAPERAPI');
        $apiurl = env('SCRAPERURL');

        $ch = curl_init();
        $url = $apiurl . '?api_key=' . $api . '&url=https://www.google.com/search?q=site:' . $site_url . '&autoparse=true';
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        $result_array = json_decode($result, true);
        foreach ($result_array['organic_results'] as $data) {
            $pages = Page::where('seller_site_page_url', '=', $data['link'])->get();
            if (count($pages) > 0) {
                Page::where('id', $pages[0]->id)
                    ->update([
                        'indexed' => 1
                    ]);
            }
            GoogleIndexUrl::firstOrCreate([
                'site_id' => $site_id,
                'page_url' => $data['link'],
            ]);
        }
    }
}

if (!function_exists('get_html_from_url_quey')) {
    function get_html_from_url_quey($page_url)
    {

        $api = env('SCRAPERAPI');
        $apiurl = env('SCRAPERURL');

        $ch = curl_init();
        $url = $apiurl . '?api_key=' . $api . '&url=' . $page_url;
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if (!function_exists('get_ext_count_from_html_quey')) {
    function get_ext_count_from_html_quey($site_html, $host)
    {

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($site_html);
        libxml_use_internal_errors($internalErrors);
        $extrenal_url = 0;
        foreach ($dom->getElementsByTagName('a') as $node) {
            $url = $node->getAttribute('href');
            $components = parse_url($url);
            if (!empty($components['host']) && strcasecmp($components['host'], $host)) {
                $extrenal_url++;
            }
        }
        return $extrenal_url;
    }
}

if (!function_exists('check_link_added_to_blog')) {
    function check_link_added_to_blog($site_html, $url)
    {

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($site_html);
        libxml_use_internal_errors($internalErrors);
        $finder = new DomXPath($dom);
        $classname = "backlink-data";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        foreach ($nodes as $node) {
            $arr = $node->getElementsByTagName("a");
            foreach ($arr as $item) {
                $href = $item->getAttribute("href");
                if ($href == $url)
                    return true;
            }
        }
        return false;
        //print_r($nodes);exit;
        //return  $extrenal_url;
    }
}

if (!function_exists('add_digimetr_url_quey')) {
    function add_digimetr_url_quey($site_page_url)
    {
        $api = env('DIGIMETR');
        $apiurl = env('DIGIMETRURL');
        $site_page_url = trim($site_page_url);
        $ch = curl_init();
        //https://digimetr.com/api/mj?access-token=4afff33048a469cf588cd59eb325cc6d&url=https://www.backlink-market.com&setting=4
        $url = $apiurl . '?access-token=' . $api . '&url=' . $site_page_url . "&setting=4";
        //echo $url;
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            log::info("add_digimetr_url_quey function failed URL: " . $site_page_url . " Error: " . $error_msg);

        }
        curl_close($ch);
        $response = json_decode($result);
        //print_r($response);
        if (isset($response) && $response->status == 1) {
            log::info("add_digimetr_url_quey Success: response: " . print_r($response, true));
            return $response->data;
        }

        log::info("add_digimetr_url_quey response not status==1: " . print_r($response, true));
        return false;
    }
}

if (!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

if (!function_exists('getCountry')) {
    function getCountry()
    {
        $countryList = array();
        $countryList[0]['code'] = 'AF';
        $countryList[0]['country'] = "Afghanistan";
        $countryList[0]['eucountry'] = 0;
        $countryList[1]['code'] = 'AX';
        $countryList[1]['country'] = "Åland Islands";
        $countryList[1]['eucountry'] = 0;
        $countryList[2]['code'] = 'AL';
        $countryList[2]['country'] = "Albania";
        $countryList[2]['eucountry'] = 1;
        $countryList[3]['code'] = 'DZ';
        $countryList[3]['country'] = "Algeria";
        $countryList[3]['eucountry'] = 0;
        $countryList[4]['code'] = 'AS';
        $countryList[4]['country'] = "American Samoa";
        $countryList[4]['eucountry'] = 0;
        $countryList[5]['code'] = 'AD';
        $countryList[5]['country'] = "Andorra";
        $countryList[5]['eucountry'] = 1;
        $countryList[6]['code'] = 'AO';
        $countryList[6]['country'] = "Angola";
        $countryList[6]['eucountry'] = 0;
        $countryList[7]['code'] = 'AI';
        $countryList[7]['country'] = "Anguilla";
        $countryList[7]['eucountry'] = 0;
        $countryList[8]['code'] = 'AQ';
        $countryList[8]['country'] = "Antarctica";
        $countryList[8]['eucountry'] = 0;
        $countryList[9]['code'] = 'AG';
        $countryList[9]['country'] = "Antigua and Barbuda";
        $countryList[9]['eucountry'] = 0;
        $countryList[10]['code'] = 'AR';
        $countryList[10]['country'] = "Argentina";
        $countryList[10]['eucountry'] = 0;
        $countryList[11]['code'] = 'AM';
        $countryList[11]['country'] = "Armenia";
        $countryList[11]['eucountry'] = 0;
        $countryList[12]['code'] = 'AW';
        $countryList[12]['country'] = "Aruba";
        $countryList[12]['eucountry'] = 0;
        $countryList[13]['code'] = 'AU';
        $countryList[13]['country'] = "Australia";
        $countryList[13]['eucountry'] = 0;
        $countryList[14]['code'] = 'AT';
        $countryList[14]['country'] = "Austria";
        $countryList[14]['eucountry'] = 1;
        $countryList[15]['code'] = 'AZ';
        $countryList[15]['country'] = "Azerbaijan";
        $countryList[15]['eucountry'] = 0;
        $countryList[16]['code'] = 'BS';
        $countryList[16]['country'] = "Bahamas";
        $countryList[16]['eucountry'] = 0;
        $countryList[17]['code'] = 'BH';
        $countryList[17]['country'] = "Bahrain";
        $countryList[17]['eucountry'] = 0;
        $countryList[18]['code'] = 'BD';
        $countryList[18]['country'] = "Bangladesh";
        $countryList[18]['eucountry'] = 0;
        $countryList[19]['code'] = 'BB';
        $countryList[19]['country'] = "Barbados";
        $countryList[19]['eucountry'] = 0;
        $countryList[20]['code'] = 'BY';
        $countryList[20]['country'] = "Belarus";
        $countryList[20]['eucountry'] = 1;
        $countryList[21]['code'] = 'BE';
        $countryList[21]['country'] = "Belgium";
        $countryList[21]['eucountry'] = 1;
        $countryList[22]['code'] = 'BZ';
        $countryList[22]['country'] = "Belize";
        $countryList[22]['eucountry'] = 0;
        $countryList[23]['code'] = 'BJ';
        $countryList[23]['country'] = "Benin";
        $countryList[23]['eucountry'] = 0;
        $countryList[24]['code'] = 'BM';
        $countryList[24]['country'] = "Bermuda";
        $countryList[24]['eucountry'] = 0;
        $countryList[25]['code'] = 'BT';
        $countryList[25]['country'] = "Bhutan";
        $countryList[25]['eucountry'] = 0;
        $countryList[26]['code'] = 'BO';
        $countryList[26]['country'] = "Bolivia, Plurinational State of";
        $countryList[26]['eucountry'] = 0;
        $countryList[27]['code'] = 'BQ';
        $countryList[27]['country'] = "Bonaire, Sint Eustatius and Saba";
        $countryList[27]['eucountry'] = 0;
        $countryList[28]['code'] = 'BA';
        $countryList[28]['country'] = "Bosnia and Herzegovina";
        $countryList[28]['eucountry'] = 1;
        $countryList[29]['code'] = 'BW';
        $countryList[29]['country'] = "Botswana";
        $countryList[29]['eucountry'] = 0;
        $countryList[30]['code'] = 'BV';
        $countryList[30]['country'] = "Bouvet Island";
        $countryList[30]['eucountry'] = 0;
        $countryList[31]['code'] = 'BR';
        $countryList[31]['country'] = "Brazil";
        $countryList[31]['eucountry'] = 0;
        $countryList[32]['code'] = 'IO';
        $countryList[32]['country'] = "British Indian Ocean Territory";
        $countryList[32]['eucountry'] = 0;
        $countryList[33]['code'] = 'BN';
        $countryList[33]['country'] = "Brunei Darussalam";
        $countryList[33]['eucountry'] = 0;
        $countryList[34]['code'] = 'BG';
        $countryList[34]['country'] = "Bulgaria";
        $countryList[34]['eucountry'] = 1;
        $countryList[35]['code'] = 'BF';
        $countryList[35]['country'] = "Burkina Faso";
        $countryList[35]['eucountry'] = 0;
        $countryList[36]['code'] = 'BI';
        $countryList[36]['country'] = "Burundi";
        $countryList[36]['eucountry'] = 0;
        $countryList[37]['code'] = 'KH';
        $countryList[37]['country'] = "Cambodia";
        $countryList[37]['eucountry'] = 0;
        $countryList[38]['code'] = 'CM';
        $countryList[38]['country'] = "Cameroon";
        $countryList[38]['eucountry'] = 0;
        $countryList[39]['code'] = 'CA';
        $countryList[39]['country'] = "Canada";
        $countryList[39]['eucountry'] = 0;
        $countryList[40]['code'] = 'CI';
        $countryList[40]['country'] = "Canary Islands";
        $countryList[40]['eucountry'] = 0;
        $countryList[41]['code'] = 'CV';
        $countryList[41]['country'] = "Cape Verde";
        $countryList[41]['eucountry'] = 0;
        $countryList[42]['code'] = 'KY';
        $countryList[42]['country'] = "Cayman Islands";
        $countryList[42]['eucountry'] = 0;
        $countryList[43]['code'] = 'CF';
        $countryList[43]['country'] = "Central African Republic";
        $countryList[43]['eucountry'] = 0;
        $countryList[44]['code'] = 'TD';
        $countryList[44]['country'] = "Chad";
        $countryList[44]['eucountry'] = 0;
        $countryList[45]['code'] = 'CL';
        $countryList[45]['country'] = "Chile";
        $countryList[45]['eucountry'] = 0;
        $countryList[46]['code'] = 'CN';
        $countryList[46]['country'] = "China";
        $countryList[46]['eucountry'] = 0;
        $countryList[47]['code'] = 'CX';
        $countryList[47]['country'] = "Christmas Island";
        $countryList[47]['eucountry'] = 0;
        $countryList[48]['code'] = 'CC';
        $countryList[48]['country'] = "Cocos (Keeling) Islands";
        $countryList[48]['eucountry'] = 0;
        $countryList[49]['code'] = 'CO';
        $countryList[49]['country'] = "Colombia";
        $countryList[49]['eucountry'] = 0;
        $countryList[50]['code'] = 'KM';
        $countryList[50]['country'] = "Comoros";
        $countryList[50]['eucountry'] = 0;
        $countryList[51]['code'] = 'CG';
        $countryList[51]['country'] = "Congo";
        $countryList[51]['eucountry'] = 0;
        $countryList[52]['code'] = 'CD';
        $countryList[52]['country'] = "Congo, the Democratic Republic of the";
        $countryList[52]['eucountry'] = 0;
        $countryList[53]['code'] = 'CK';
        $countryList[53]['country'] = "Cook Islands";
        $countryList[53]['eucountry'] = 0;
        $countryList[54]['code'] = 'CR';
        $countryList[54]['country'] = "Costa Rica";
        $countryList[54]['eucountry'] = 0;
        $countryList[55]['code'] = 'CI';
        $countryList[55]['country'] = "Côte d'Ivoire";
        $countryList[55]['eucountry'] = 0;
        $countryList[56]['code'] = 'HR';
        $countryList[56]['country'] = "Croatia";
        $countryList[56]['eucountry'] = 1;
        $countryList[57]['code'] = 'CU';
        $countryList[57]['country'] = "Cuba";
        $countryList[57]['eucountry'] = 0;
        $countryList[58]['code'] = 'CW';
        $countryList[58]['country'] = "Curaçao";
        $countryList[58]['eucountry'] = 0;
        $countryList[59]['code'] = 'CY';
        $countryList[59]['country'] = "Cyprus";
        $countryList[59]['eucountry'] = 0;
        $countryList[60]['code'] = 'CZ';
        $countryList[60]['country'] = "Czech Republic";
        $countryList[60]['eucountry'] = 1;
        $countryList[61]['code'] = 'DK';
        $countryList[61]['country'] = "Denmark";
        $countryList[61]['eucountry'] = 1;
        $countryList[62]['code'] = 'DJ';
        $countryList[62]['country'] = "Djibouti";
        $countryList[62]['eucountry'] = 0;
        $countryList[63]['code'] = 'DM';
        $countryList[63]['country'] = "Dominica";
        $countryList[63]['eucountry'] = 0;
        $countryList[64]['code'] = 'DO';
        $countryList[64]['country'] = "Dominican Republic";
        $countryList[64]['eucountry'] = 0;
        $countryList[65]['code'] = 'EC';
        $countryList[65]['country'] = "Ecuador";
        $countryList[65]['eucountry'] = 0;
        $countryList[66]['code'] = 'EG';
        $countryList[66]['country'] = "Egypt";
        $countryList[66]['eucountry'] = 0;
        $countryList[67]['code'] = 'SV';
        $countryList[67]['country'] = "El Salvador";
        $countryList[67]['eucountry'] = 0;
        $countryList[68]['code'] = 'GQ';
        $countryList[68]['country'] = "Equatorial Guinea";
        $countryList[68]['eucountry'] = 0;
        $countryList[69]['code'] = 'ER';
        $countryList[69]['country'] = "Eritrea";
        $countryList[69]['eucountry'] = 0;
        $countryList[70]['code'] = 'EE';
        $countryList[70]['country'] = "Estonia";
        $countryList[70]['eucountry'] = 1;
        $countryList[71]['code'] = 'ET';
        $countryList[71]['country'] = "Ethiopia";
        $countryList[71]['eucountry'] = 0;
        $countryList[72]['code'] = 'FK';
        $countryList[72]['country'] = "Falkland Islands (Malvinas)";
        $countryList[72]['eucountry'] = 0;
        $countryList[73]['code'] = 'FO';
        $countryList[73]['country'] = "Faroe Islands";
        $countryList[73]['eucountry'] = 0;
        $countryList[74]['code'] = 'FJ';
        $countryList[74]['country'] = "Fiji";
        $countryList[74]['eucountry'] = 0;
        $countryList[75]['code'] = 'FI';
        $countryList[75]['country'] = "Finland";
        $countryList[75]['eucountry'] = 1;
        $countryList[76]['code'] = 'FR';
        $countryList[76]['country'] = "France";
        $countryList[76]['eucountry'] = 1;
        $countryList[77]['code'] = 'GF';
        $countryList[77]['country'] = "French Guiana";
        $countryList[77]['eucountry'] = 0;
        $countryList[78]['code'] = 'PF';
        $countryList[78]['country'] = "French Polynesia";
        $countryList[78]['eucountry'] = 0;
        $countryList[79]['code'] = 'TF';
        $countryList[79]['country'] = "French Southern Territories";
        $countryList[79]['eucountry'] = 0;
        $countryList[80]['code'] = 'GA';
        $countryList[80]['country'] = "Gabon";
        $countryList[80]['eucountry'] = 0;
        $countryList[81]['code'] = 'GM';
        $countryList[81]['country'] = "Gambia";
        $countryList[81]['eucountry'] = 0;
        $countryList[82]['code'] = 'GE';
        $countryList[82]['country'] = "Georgia";
        $countryList[82]['eucountry'] = 0;
        $countryList[83]['code'] = 'DE';
        $countryList[83]['country'] = "Germany";
        $countryList[83]['eucountry'] = 1;
        $countryList[84]['code'] = 'GH';
        $countryList[84]['country'] = "Ghana";
        $countryList[84]['eucountry'] = 0;
        $countryList[85]['code'] = 'GI';
        $countryList[85]['country'] = "Gibraltar";
        $countryList[85]['eucountry'] = 0;
        $countryList[86]['code'] = 'GR';
        $countryList[86]['country'] = "Greece";
        $countryList[86]['eucountry'] = 1;
        $countryList[87]['code'] = 'GL';
        $countryList[87]['country'] = "Greenland";
        $countryList[87]['eucountry'] = 0;
        $countryList[88]['code'] = 'GD';
        $countryList[88]['country'] = "Grenada";
        $countryList[88]['eucountry'] = 0;
        $countryList[89]['code'] = 'GP';
        $countryList[89]['country'] = "Guadeloupe";
        $countryList[89]['eucountry'] = 0;
        $countryList[90]['code'] = 'GU';
        $countryList[90]['country'] = "Guam";
        $countryList[90]['eucountry'] = 0;
        $countryList[91]['code'] = 'GT';
        $countryList[91]['country'] = "Guatemala";
        $countryList[91]['eucountry'] = 0;
        $countryList[92]['code'] = 'GG';
        $countryList[92]['country'] = "Guernsey";
        $countryList[92]['eucountry'] = 0;
        $countryList[93]['code'] = 'GN';
        $countryList[93]['country'] = "Guinea";
        $countryList[93]['eucountry'] = 0;
        $countryList[94]['code'] = 'GW';
        $countryList[94]['country'] = "Guinea-Bissau";
        $countryList[94]['eucountry'] = 0;
        $countryList[95]['code'] = 'GY';
        $countryList[95]['country'] = "Guyana";
        $countryList[95]['eucountry'] = 0;
        $countryList[96]['code'] = 'HT';
        $countryList[96]['country'] = "Haiti";
        $countryList[96]['eucountry'] = 0;
        $countryList[97]['code'] = 'HM';
        $countryList[97]['country'] = "Heard Island and McDonald Islands";
        $countryList[97]['eucountry'] = 0;
        $countryList[98]['code'] = 'VA';
        $countryList[98]['country'] = "Holy See (Vatican City State)";
        $countryList[98]['eucountry'] = 1;
        $countryList[99]['code'] = 'HN';
        $countryList[99]['country'] = "Honduras";
        $countryList[99]['eucountry'] = 0;
        $countryList[100]['code'] = 'HK';
        $countryList[100]['country'] = "Hong Kong";
        $countryList[100]['eucountry'] = 0;
        $countryList[101]['code'] = 'HU';
        $countryList[101]['country'] = "Hungary";
        $countryList[101]['eucountry'] = 1;
        $countryList[102]['code'] = 'IS';
        $countryList[102]['country'] = "Iceland";
        $countryList[102]['eucountry'] = 1;
        $countryList[103]['code'] = 'IN';
        $countryList[103]['country'] = "India";
        $countryList[103]['eucountry'] = 0;
        $countryList[104]['code'] = 'ID';
        $countryList[104]['country'] = "Indonesia";
        $countryList[104]['eucountry'] = 0;
        $countryList[105]['code'] = 'IR';
        $countryList[105]['country'] = "Iran, Islamic Republic of";
        $countryList[105]['eucountry'] = 0;
        $countryList[106]['code'] = 'IQ';
        $countryList[106]['country'] = "Iraq";
        $countryList[106]['eucountry'] = 0;
        $countryList[107]['code'] = 'IE';
        $countryList[107]['country'] = "Ireland";
        $countryList[107]['eucountry'] = 1;
        $countryList[108]['code'] = 'IM';
        $countryList[108]['country'] = "Isle of Man";
        $countryList[108]['eucountry'] = 0;
        $countryList[109]['code'] = 'IL';
        $countryList[109]['country'] = "Israel";
        $countryList[109]['eucountry'] = 0;
        $countryList[110]['code'] = 'IT';
        $countryList[110]['country'] = "Italy";
        $countryList[110]['eucountry'] = 1;
        $countryList[111]['code'] = 'JM';
        $countryList[111]['country'] = "Jamaica";
        $countryList[111]['eucountry'] = 0;
        $countryList[112]['code'] = 'JP';
        $countryList[112]['country'] = "Japan";
        $countryList[112]['eucountry'] = 0;
        $countryList[113]['code'] = 'JE';
        $countryList[113]['country'] = "Jersey";
        $countryList[113]['eucountry'] = 0;
        $countryList[114]['code'] = 'JO';
        $countryList[114]['country'] = "Jordan";
        $countryList[114]['eucountry'] = 0;
        $countryList[115]['code'] = 'KZ';
        $countryList[115]['country'] = "Kazakhstan";
        $countryList[115]['eucountry'] = 0;
        $countryList[116]['code'] = 'KE';
        $countryList[116]['country'] = "Kenya";
        $countryList[116]['eucountry'] = 0;
        $countryList[117]['code'] = 'KI';
        $countryList[117]['country'] = "Kiribati";
        $countryList[117]['eucountry'] = 0;
        $countryList[118]['code'] = 'KP';
        $countryList[118]['country'] = "Korea, Democratic People's Republic of";
        $countryList[118]['eucountry'] = 0;
        $countryList[119]['code'] = 'KR';
        $countryList[119]['country'] = "Korea, Republic of";
        $countryList[119]['eucountry'] = 0;
        $countryList[120]['code'] = 'KW';
        $countryList[120]['country'] = "Kuwait";
        $countryList[120]['eucountry'] = 0;
        $countryList[121]['code'] = 'KG';
        $countryList[121]['country'] = "Kyrgyzstan";
        $countryList[121]['eucountry'] = 0;
        $countryList[122]['code'] = 'LA';
        $countryList[122]['country'] = "Lao People's Democratic Republic";
        $countryList[122]['eucountry'] = 0;
        $countryList[123]['code'] = 'LV';
        $countryList[123]['country'] = "Latvia";
        $countryList[123]['eucountry'] = 1;
        $countryList[124]['code'] = 'LB';
        $countryList[124]['country'] = "Lebanon";
        $countryList[124]['eucountry'] = 0;
        $countryList[125]['code'] = 'LS';
        $countryList[125]['country'] = "Lesotho";
        $countryList[125]['eucountry'] = 0;
        $countryList[126]['code'] = 'LR';
        $countryList[126]['country'] = "Liberia";
        $countryList[126]['eucountry'] = 0;
        $countryList[127]['code'] = 'LY';
        $countryList[127]['country'] = "Libya";
        $countryList[127]['eucountry'] = 0;
        $countryList[128]['code'] = 'LI';
        $countryList[128]['country'] = "Liechtenstein";
        $countryList[128]['eucountry'] = 1;
        $countryList[129]['code'] = 'LT';
        $countryList[129]['country'] = "Lithuania";
        $countryList[129]['eucountry'] = 1;
        $countryList[130]['code'] = 'LU';
        $countryList[130]['country'] = "Luxembourg";
        $countryList[130]['eucountry'] = 1;
        $countryList[131]['code'] = 'MO';
        $countryList[131]['country'] = "Macao";
        $countryList[131]['eucountry'] = 0;
        $countryList[132]['code'] = 'MK';
        $countryList[132]['country'] = "Macedonia, the former Yugoslav Republic of";
        $countryList[132]['eucountry'] = 0;
        $countryList[133]['code'] = 'MG';
        $countryList[133]['country'] = "Madagascar";
        $countryList[133]['eucountry'] = 0;
        $countryList[134]['code'] = 'MW';
        $countryList[134]['country'] = "Malawi";
        $countryList[134]['eucountry'] = 0;
        $countryList[135]['code'] = 'MY';
        $countryList[135]['country'] = "Malaysia";
        $countryList[135]['eucountry'] = 0;
        $countryList[136]['code'] = 'MV';
        $countryList[136]['country'] = "Maldives";
        $countryList[136]['eucountry'] = 0;
        $countryList[137]['code'] = 'ML';
        $countryList[137]['country'] = "Mali";
        $countryList[137]['eucountry'] = 0;
        $countryList[138]['code'] = 'MT';
        $countryList[138]['country'] = "Malta";
        $countryList[138]['eucountry'] = 1;
        $countryList[139]['code'] = 'MH';
        $countryList[139]['country'] = "Marshall Islands";
        $countryList[139]['eucountry'] = 0;
        $countryList[140]['code'] = 'MQ';
        $countryList[140]['country'] = "Martinique";
        $countryList[140]['eucountry'] = 0;
        $countryList[141]['code'] = 'MR';
        $countryList[141]['country'] = "Mauritania";
        $countryList[141]['eucountry'] = 0;
        $countryList[142]['code'] = 'MU';
        $countryList[142]['country'] = "Mauritius";
        $countryList[142]['eucountry'] = 0;
        $countryList[143]['code'] = 'YT';
        $countryList[143]['country'] = "Mayotte";
        $countryList[143]['eucountry'] = 0;
        $countryList[144]['code'] = 'MX';
        $countryList[144]['country'] = "Mexico";
        $countryList[144]['eucountry'] = 0;
        $countryList[145]['code'] = 'FM';
        $countryList[145]['country'] = "Micronesia, Federated States of";
        $countryList[145]['eucountry'] = 0;
        $countryList[146]['code'] = 'MD';
        $countryList[146]['country'] = "Moldova, Republic of";
        $countryList[146]['eucountry'] = 1;
        $countryList[147]['code'] = 'MC';
        $countryList[147]['country'] = "Monaco";
        $countryList[147]['eucountry'] = 1;
        $countryList[148]['code'] = 'MN';
        $countryList[148]['country'] = "Mongolia";
        $countryList[148]['eucountry'] = 0;
        $countryList[149]['code'] = 'ME';
        $countryList[149]['country'] = "Montenegro";
        $countryList[149]['eucountry'] = 1;
        $countryList[150]['code'] = 'MS';
        $countryList[150]['country'] = "Montserrat";
        $countryList[150]['eucountry'] = 0;
        $countryList[151]['code'] = 'MA';
        $countryList[151]['country'] = "Morocco";
        $countryList[151]['eucountry'] = 0;
        $countryList[152]['code'] = 'MZ';
        $countryList[152]['country'] = "Mozambique";
        $countryList[152]['eucountry'] = 0;
        $countryList[153]['code'] = 'MM';
        $countryList[153]['country'] = "Myanmar";
        $countryList[153]['eucountry'] = 0;
        $countryList[154]['code'] = 'NA';
        $countryList[154]['country'] = "Namibia";
        $countryList[154]['eucountry'] = 0;
        $countryList[155]['code'] = 'NR';
        $countryList[155]['country'] = "Nauru";
        $countryList[155]['eucountry'] = 0;
        $countryList[156]['code'] = 'NP';
        $countryList[156]['country'] = "Nepal";
        $countryList[156]['eucountry'] = 0;
        $countryList[157]['code'] = 'NL';
        $countryList[157]['country'] = "Netherlands";
        $countryList[157]['eucountry'] = 1;
        $countryList[158]['code'] = 'NC';
        $countryList[158]['country'] = "New Caledonia";
        $countryList[158]['eucountry'] = 0;
        $countryList[159]['code'] = 'NZ';
        $countryList[159]['country'] = "New Zealand";
        $countryList[159]['eucountry'] = 0;
        $countryList[160]['code'] = 'NI';
        $countryList[160]['country'] = "Nicaragua";
        $countryList[160]['eucountry'] = 0;
        $countryList[161]['code'] = 'NE';
        $countryList[161]['country'] = "Niger";
        $countryList[161]['eucountry'] = 0;
        $countryList[162]['code'] = 'NG';
        $countryList[162]['country'] = "Nigeria";
        $countryList[162]['eucountry'] = 0;
        $countryList[163]['code'] = 'NU';
        $countryList[163]['country'] = "Niue";
        $countryList[163]['eucountry'] = 0;
        $countryList[164]['code'] = 'NF';
        $countryList[164]['country'] = "Norfolk Island";
        $countryList[164]['eucountry'] = 0;
        $countryList[165]['code'] = 'MP';
        $countryList[165]['country'] = "Northern Mariana Islands";
        $countryList[165]['eucountry'] = 1;
        $countryList[166]['code'] = 'NO';
        $countryList[166]['country'] = "Norway";
        $countryList[166]['eucountry'] = 1;
        $countryList[167]['code'] = 'OM';
        $countryList[167]['country'] = "Oman";
        $countryList[167]['eucountry'] = 0;
        $countryList[168]['code'] = 'PK';
        $countryList[168]['country'] = "Pakistan";
        $countryList[168]['eucountry'] = 0;
        $countryList[169]['code'] = 'PW';
        $countryList[169]['country'] = "Palau";
        $countryList[169]['eucountry'] = 0;
        $countryList[170]['code'] = 'PS';
        $countryList[170]['country'] = "Palestinian Territory, Occupied";
        $countryList[170]['eucountry'] = 0;
        $countryList[171]['code'] = 'PA';
        $countryList[171]['country'] = "Panama";
        $countryList[171]['eucountry'] = 0;
        $countryList[172]['code'] = 'PG';
        $countryList[172]['country'] = "Papua New Guinea";
        $countryList[172]['eucountry'] = 0;
        $countryList[173]['code'] = 'PY';
        $countryList[173]['country'] = "Paraguay";
        $countryList[173]['eucountry'] = 0;
        $countryList[174]['code'] = 'PE';
        $countryList[174]['country'] = "Peru";
        $countryList[174]['eucountry'] = 0;
        $countryList[175]['code'] = 'PH';
        $countryList[175]['country'] = "Philippines";
        $countryList[175]['eucountry'] = 0;
        $countryList[176]['code'] = 'PN';
        $countryList[176]['country'] = "Pitcairn";
        $countryList[176]['eucountry'] = 0;
        $countryList[177]['code'] = 'PL';
        $countryList[177]['country'] = "Poland";
        $countryList[177]['eucountry'] = 1;
        $countryList[178]['code'] = 'PT';
        $countryList[178]['country'] = "Portugal";
        $countryList[178]['eucountry'] = 1;
        $countryList[179]['code'] = 'PR';
        $countryList[179]['country'] = "Puerto Rico";
        $countryList[179]['eucountry'] = 0;
        $countryList[180]['code'] = 'QA';
        $countryList[180]['country'] = "Qatar";
        $countryList[180]['eucountry'] = 0;
        $countryList[181]['code'] = 'RE';
        $countryList[181]['country'] = "Réunion";
        $countryList[181]['eucountry'] = 0;
        $countryList[182]['code'] = 'RO';
        $countryList[182]['country'] = "Romania";
        $countryList[182]['eucountry'] = 1;
        $countryList[183]['code'] = 'RU';
        $countryList[183]['country'] = "Russian Federation";
        $countryList[183]['eucountry'] = 1;
        $countryList[184]['code'] = 'RW';
        $countryList[184]['country'] = "Rwanda";
        $countryList[184]['eucountry'] = 0;
        $countryList[185]['code'] = 'BL';
        $countryList[185]['country'] = "Saint Barthélemy";
        $countryList[185]['eucountry'] = 0;
        $countryList[186]['code'] = 'SH';
        $countryList[186]['country'] = "Saint Helena, Ascension and Tristan da Cunha";
        $countryList[186]['eucountry'] = 0;
        $countryList[187]['code'] = 'KN';
        $countryList[187]['country'] = "Saint Kitts and Nevis";
        $countryList[187]['eucountry'] = 0;
        $countryList[188]['code'] = 'LC';
        $countryList[188]['country'] = "Saint Lucia";
        $countryList[188]['eucountry'] = 0;
        $countryList[189]['code'] = 'MF';
        $countryList[189]['country'] = "Saint Martin (French part)";
        $countryList[189]['eucountry'] = 0;
        $countryList[190]['code'] = 'PM';
        $countryList[190]['country'] = "Saint Pierre and Miquelon";
        $countryList[190]['eucountry'] = 0;
        $countryList[191]['code'] = 'VC';
        $countryList[191]['country'] = "Saint Vincent and the Grenadines";
        $countryList[191]['eucountry'] = 0;
        $countryList[192]['code'] = 'WS';
        $countryList[192]['country'] = "Samoa";
        $countryList[192]['eucountry'] = 0;
        $countryList[193]['code'] = 'SM';
        $countryList[193]['country'] = "San Marino";
        $countryList[193]['eucountry'] = 1;
        $countryList[194]['code'] = 'ST';
        $countryList[194]['country'] = "Sao Tome and Principe";
        $countryList[194]['eucountry'] = 0;
        $countryList[195]['code'] = 'SA';
        $countryList[195]['country'] = "Saudi Arabia";
        $countryList[195]['eucountry'] = 0;
        $countryList[196]['code'] = 'SN';
        $countryList[196]['country'] = "Senegal";
        $countryList[196]['eucountry'] = 0;
        $countryList[197]['code'] = 'RS';
        $countryList[197]['country'] = "Serbia";
        $countryList[197]['eucountry'] = 1;
        $countryList[198]['code'] = 'SC';
        $countryList[198]['country'] = "Seychelles";
        $countryList[198]['eucountry'] = 0;
        $countryList[199]['code'] = 'SL';
        $countryList[199]['country'] = "Sierra Leone";
        $countryList[199]['eucountry'] = 0;
        $countryList[200]['code'] = 'SG';
        $countryList[200]['country'] = "Singapore";
        $countryList[200]['eucountry'] = 0;
        $countryList[201]['code'] = 'SX';
        $countryList[201]['country'] = "Sint Maarten (Dutch part)";
        $countryList[201]['eucountry'] = 0;
        $countryList[202]['code'] = 'SK';
        $countryList[202]['country'] = "Slovakia";
        $countryList[202]['eucountry'] = 1;
        $countryList[203]['code'] = 'SI';
        $countryList[203]['country'] = "Slovenia";
        $countryList[203]['eucountry'] = 1;
        $countryList[204]['code'] = 'SB';
        $countryList[204]['country'] = "Solomon Islands";
        $countryList[204]['eucountry'] = 0;
        $countryList[205]['code'] = 'SO';
        $countryList[205]['country'] = "Somalia";
        $countryList[205]['eucountry'] = 0;
        $countryList[206]['code'] = 'ZA';
        $countryList[206]['country'] = "South Africa";
        $countryList[206]['eucountry'] = 0;
        $countryList[207]['code'] = 'GS';
        $countryList[207]['country'] = "South Georgia and the South Sandwich Islands";
        $countryList[207]['eucountry'] = 0;
        $countryList[208]['code'] = 'SS';
        $countryList[208]['country'] = "South Sudan";
        $countryList[208]['eucountry'] = 0;
        $countryList[209]['code'] = 'ES';
        $countryList[209]['country'] = "Spain";
        $countryList[209]['eucountry'] = 1;
        $countryList[210]['code'] = 'LK';
        $countryList[210]['country'] = "Sri Lanka";
        $countryList[210]['eucountry'] = 0;
        $countryList[211]['code'] = 'SD';
        $countryList[211]['country'] = "Sudan";
        $countryList[211]['eucountry'] = 0;
        $countryList[212]['code'] = 'SR';
        $countryList[212]['country'] = "Suriname";
        $countryList[212]['eucountry'] = 0;
        $countryList[213]['code'] = 'SJ';
        $countryList[213]['country'] = "Svalbard and Jan Mayen";
        $countryList[213]['eucountry'] = 0;
        $countryList[214]['code'] = 'SZ';
        $countryList[214]['country'] = "Swaziland";
        $countryList[214]['eucountry'] = 0;
        $countryList[215]['code'] = 'SE';
        $countryList[215]['country'] = "Sweden";
        $countryList[215]['eucountry'] = 1;
        $countryList[216]['code'] = 'CH';
        $countryList[216]['country'] = "Switzerland";
        $countryList[216]['eucountry'] = 1;
        $countryList[217]['code'] = 'SY';
        $countryList[217]['country'] = "Syrian Arab Republic";
        $countryList[217]['eucountry'] = 0;
        $countryList[218]['code'] = 'TW';
        $countryList[218]['country'] = "Taiwan, Province of China";
        $countryList[218]['eucountry'] = 0;
        $countryList[219]['code'] = 'TJ';
        $countryList[219]['country'] = "Tajikistan";
        $countryList[219]['eucountry'] = 0;
        $countryList[220]['code'] = 'TZ';
        $countryList[220]['country'] = "Tanzania, United Republic of";
        $countryList[220]['eucountry'] = 0;
        $countryList[221]['code'] = 'TH';
        $countryList[221]['country'] = "Thailand";
        $countryList[221]['eucountry'] = 0;
        $countryList[222]['code'] = 'TL';
        $countryList[222]['country'] = "Timor-Leste";
        $countryList[222]['eucountry'] = 0;
        $countryList[223]['code'] = 'TG';
        $countryList[223]['country'] = "Togo";
        $countryList[223]['eucountry'] = 0;
        $countryList[224]['code'] = 'TK';
        $countryList[224]['country'] = "Tokelau";
        $countryList[224]['eucountry'] = 0;
        $countryList[225]['code'] = 'TO';
        $countryList[225]['country'] = "Tonga";
        $countryList[225]['eucountry'] = 0;
        $countryList[226]['code'] = 'TT';
        $countryList[226]['country'] = "Trinidad and Tobago";
        $countryList[226]['eucountry'] = 0;
        $countryList[227]['code'] = 'TN';
        $countryList[227]['country'] = "Tunisia";
        $countryList[227]['eucountry'] = 0;
        $countryList[228]['code'] = 'TR';
        $countryList[228]['country'] = "Turkey";
        $countryList[228]['eucountry'] = 0;
        $countryList[229]['code'] = 'TM';
        $countryList[229]['country'] = "Turkmenistan";
        $countryList[229]['eucountry'] = 0;
        $countryList[230]['code'] = 'TC';
        $countryList[230]['country'] = "Turks and Caicos Islands";
        $countryList[230]['eucountry'] = 0;
        $countryList[231]['code'] = 'TV';
        $countryList[231]['country'] = "Tuvalu";
        $countryList[231]['eucountry'] = 0;
        $countryList[232]['code'] = 'UG';
        $countryList[232]['country'] = "Uganda";
        $countryList[232]['eucountry'] = 0;
        $countryList[233]['code'] = 'UA';
        $countryList[233]['country'] = "Ukraine";
        $countryList[233]['eucountry'] = 1;
        $countryList[234]['code'] = 'AE';
        $countryList[234]['country'] = "United Arab Emirates";
        $countryList[234]['eucountry'] = 0;
        $countryList[235]['code'] = 'GB';
        $countryList[235]['country'] = "United Kingdom";
        $countryList[235]['eucountry'] = 1;
        $countryList[236]['code'] = 'US';
        $countryList[236]['country'] = "United States";
        $countryList[236]['eucountry'] = 0;
        $countryList[237]['code'] = 'UM';
        $countryList[237]['country'] = "United States Minor Outlying Islands";
        $countryList[237]['eucountry'] = 0;
        $countryList[238]['code'] = 'UY';
        $countryList[238]['country'] = "Uruguay";
        $countryList[238]['eucountry'] = 0;
        $countryList[239]['code'] = 'UZ';
        $countryList[239]['country'] = "Uzbekistan";
        $countryList[239]['eucountry'] = 0;
        $countryList[240]['code'] = 'VU';
        $countryList[240]['country'] = "Vanuatu";
        $countryList[240]['eucountry'] = 0;
        $countryList[241]['code'] = 'VE';
        $countryList[241]['country'] = "Venezuela, Bolivarian Republic of";
        $countryList[241]['eucountry'] = 0;
        $countryList[242]['code'] = 'VN';
        $countryList[242]['country'] = "Viet Nam";
        $countryList[242]['eucountry'] = 0;
        $countryList[243]['code'] = 'VG';
        $countryList[243]['country'] = "Virgin Islands, British";
        $countryList[243]['eucountry'] = 0;
        $countryList[244]['code'] = 'VI';
        $countryList[244]['country'] = "Virgin Islands, U.S.";
        $countryList[244]['eucountry'] = 0;
        $countryList[245]['code'] = 'WF';
        $countryList[245]['country'] = "Wallis and Futuna";
        $countryList[245]['eucountry'] = 0;
        $countryList[246]['code'] = 'EH';
        $countryList[246]['country'] = "Western Sahara";
        $countryList[246]['eucountry'] = 0;
        $countryList[247]['code'] = 'YE';
        $countryList[247]['country'] = "Yemen";
        $countryList[247]['eucountry'] = 0;
        $countryList[248]['code'] = 'ZM';
        $countryList[248]['country'] = "Zambia";
        $countryList[248]['eucountry'] = 0;
        $countryList[249]['code'] = 'ZW';
        $countryList[249]['country'] = "Zimbabwe";
        $countryList[249]['eucountry'] = 0;

        return $countryList;

    }
}
if (!function_exists('sortByOrder')) {
    function sortByOrder($a, $b)
    {
        return $a['p'] * 100 - $b['p'] * 100;
    }
}
if (!function_exists('get_categories_using_content')) {
    function get_categories_using_content($plain)
    {

        $payload = '{"texts": ["' . $plain . '"]}';
        $url = "https://api.uclassify.com/v1/uclassify/iab-taxonomy-v2/classify";

        $curl = curl_init($url);

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Token CsQNMH3Cyq0c"
        );

        curl_setopt($curl, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            mail(env('APP_ADMIN_EMAIL'), "Category Checker Error", "content: " . $plain . "\nerror: " . $error_msg);
            Log::info($plain);
            Log::info($error_msg);
        }
        curl_close($curl);

        $result2 = json_decode($resp, true);
        $cats = array();
        if(isset($result2)){
            foreach ($result2["0"]["classification"] as $key => $val) {
                if ($val["p"] > 0.1) {
                    array_push($cats, (array)$val);
                }
            }
        }
        usort($cats, 'sortByOrder');
        $cats = array_reverse($cats);
        $allcats = array();
        $category_minimum_per = env('CATEGORY_MINIMUM_PERCENTAGE');
        foreach ($cats as $cat) {
            if ($cat['p'] > $category_minimum_per) {
                $allcats[] = $cat['className'];
            }
        }
        // Take the most matching cat if no cat found with > env('CATEGORY_MINIMUM_PERCENTAGE')
        if (count($cats) > 0 && count($allcats) == 0) {
            $allcats[] = $cats[0]['className'];
        } elseif (count($cats) == 0) {
            $cats[0]['className'] = "uncategorized_uncategorized_30_1";
            $allcats[] = $cats[0]['className'];
        }
        $output = array();
        foreach ($allcats as $cat) {
            $exp_cat = explode("_", $cat);
            $count = count($exp_cat) - 4;
            $val = '';
            for ($i = $count; $i < count($exp_cat); $i++) {
                $var = $exp_cat[$i];
                if (is_numeric($var)) {
                    $val .= $var . ".";
                }
            }
            $newarraynama = rtrim($val, ".");
            $output[] = $newarraynama;
        }
        //$cats = implode(",",$allcats);
        return $output;
    }
}
if (!function_exists('get_language_using_content')) {
    function get_language_using_content($plain)
    {

        $payload = '{"texts": ["' . html_entity_decode($plain) . '"]}';

        $url = "https://api.uclassify.com/v1/uclassify/language-detector/classify";

        $curl = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Token " . env("UCLASSIFY_KEY")
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            mail(env('APP_ADMIN_EMAIL'), "Language Checker Error returns English", "text: " . $plain . "\nerror: " . $error_msg);
            Log::info($plain);
            Log::info($error_msg);
        }
        curl_close($curl);

        $result2 = json_decode($resp, true);
        $cats = array();
        foreach ($result2["0"]["classification"] as $key => $val) {
            if ($val["p"] > 0.1) {
                array_push($cats, (array)$val);
            }
        }

        usort($cats, 'sortByOrder');
        $cats = array_reverse($cats);

        $allcats = array();
        $category_minimum_per = env('CATEGORY_MINIMUM_PERCENTAGE');
        foreach ($cats as $cat) {
            if ($cat['p'] > $category_minimum_per) {
                $allcats[] = current(explode("_", $cat['className']));
            }
        }

        $cats = implode(",", $allcats);
        if (count($allcats) == 0) {
            mail(env('APP_ADMIN_EMAIL'), "Language Checker Error returns English", "text: " . $plain);
            Log::info("Language Checker did not find a result so it returns English. Text: " . $plain);
            return "English";
        } else {
            return $cats;
        }

    }
}
if (!function_exists('updateAllNewPrices')) {
    function updateAllNewPrices()
    {
        $pages = Page::where('page_price_seller', '=', '0')->where('page_price_buyer', '=', '0')->whereNotNull('moz_da')->whereNotNull('moz_pa')->whereNotNull('maj_tf')->whereNotNull('maj_cf')->whereNotNull('rd')->whereNotNull('maj_cf')->where('indexed', '=', '1')->get();
        if ($pages->count() > 0) {
            foreach ($pages as $page) {
                $prices = getPriceByPage($page);
                $page->update([
                    'page_price_seller' => $prices["seller_price"],
                    'page_price_buyer' => $prices["buyer_price"],
                    'myrank' => $prices["myrank"]
                ]);
            }
        }
    }
}
if (!function_exists('getPriceByPage')) {
    function getPriceByPage($page)
    {
        return getPriceByMetrics($page->moz_da, $page->moz_pa, $page->maj_tf, $page->maj_cf, $page->rd);
    }
}
if (!function_exists('getPriceByMetrics')) {
    function getPriceByMetrics($moz_da, $moz_pa, $maj_tf, $maj_cf, $maj_rd)
    {
        $buyer_price = 0;
        $page_myrank = (($maj_tf * 2) + $moz_pa) / 3;  // Maj. TF Doubled weighted
        $domain_myrank = ($moz_da + $maj_cf) / 4;  // Domain Metrics influence decreased
        $myrank = ($page_myrank * 3 + $domain_myrank) / 4; // Page Metrics increased

        /* add RD Effect
        RD gives a percentage on top $Myrank * ($maj_rd / 10000)+1
        */
        if ($maj_rd < 5000) {
            $myrank_rd_bonus = (($maj_rd / 10000) + 1.1);
        } else {
            $myrank_rd_bonus = 1.5;
        }
        if ($myrank < 10) {
            $seller_price = $myrank * 0.5;
            $buyer_price = $myrank * 1.0 * $myrank_rd_bonus;
        } elseif ($myrank <= 15) {
            $seller_price = $myrank * 0.55;
            $buyer_price = $myrank * 1.1 * $myrank_rd_bonus;
        } elseif ($myrank <= 20) {
            $seller_price = $myrank * 0.6;
            $buyer_price = $myrank * 1.2 * $myrank_rd_bonus;
        } elseif ($myrank <= 25) {
            $seller_price = $myrank * 0.65;
            $buyer_price = $myrank * 1.3 * $myrank_rd_bonus;
        } elseif ($myrank <= 30) {
            $seller_price = $myrank * 0.7;
            $buyer_price = $myrank * 1.5 * $myrank_rd_bonus;
        } elseif ($myrank <= 35) {
            $seller_price = $myrank * 0.75;
            $buyer_price = $myrank * 1.7 * $myrank_rd_bonus;
        } elseif ($myrank <= 40) {
            $seller_price = $myrank * 0.8;
            $buyer_price = $myrank * 1.9 * $myrank_rd_bonus;
        } elseif ($myrank <= 45) {
            $seller_price = $myrank * 0.85;
            $buyer_price = $myrank * 2.25 * $myrank_rd_bonus;
        } elseif ($myrank <= 50) {
            $seller_price = $myrank * 0.9;
            $buyer_price = $myrank * 2.5 * $myrank_rd_bonus;
        } elseif ($myrank <= 55) {
            $seller_price = $myrank * 0.95;
            $buyer_price = $myrank * 2.75 * $myrank_rd_bonus;
        } elseif ($myrank <= 60) {
            $seller_price = $myrank * 1;
            $buyer_price = $myrank * 3 * $myrank_rd_bonus;
        } elseif ($myrank <= 65) {
            $seller_price = $myrank * 1.125;
            $buyer_price = $myrank * 3.25 * $myrank_rd_bonus;
        } elseif ($myrank <= 70) {
            $seller_price = $myrank * 1.25;
            $buyer_price = $myrank * 3.5 * $myrank_rd_bonus;
        } elseif ($myrank <= 75) {
            $seller_price = $myrank * 1.5;
            $buyer_price = $myrank * 4 * $myrank_rd_bonus;
        } elseif ($myrank <= 80) {
            $seller_price = $myrank * 1.75;
            $buyer_price = $myrank * 4.5 * $myrank_rd_bonus;
        } elseif ($myrank <= 85) {
            $seller_price = $myrank * 2;
            $buyer_price = $myrank * 4 * $myrank_rd_bonus;
        } elseif ($myrank <= 90) {
            $seller_price = $myrank * 2;
            $buyer_price = $myrank * 5 * $myrank_rd_bonus;
        } else {
            // $myrank > 90 but Max Myrank = 88
            $seller_price = $myrank * 2 * $myrank_rd_bonus;
            $buyer_price = $myrank * 5 * $myrank_rd_bonus;
        }

        if ($seller_price < 1) $seller_price = 1.00;
        if ($buyer_price < 1) $buyer_price = 2.00;
        if ($myrank < 1) $myrank = 1;
        $seller_price = round($seller_price, 0);
        $buyer_price = round($buyer_price, 0);
        $profit = round($buyer_price - $seller_price);
        $myrank = round($myrank, 0);
        $result = [
            "seller_price" => $seller_price,
            "buyer_price" => $buyer_price,
            "myrank" => $myrank
        ];
        return $result;
    }
}
if (!function_exists('getBuyerPriceFromSellerPrice')) {
    function getBuyerPriceFromSellerPrice($seller_price, $maj_rd)
    {
        $seller_price = round($seller_price, 0);
        if ($maj_rd < 5000) {
            $myrank_rd_bonus = (($maj_rd / 10000) + 1.1);
        } else {
            $myrank_rd_bonus = 1.5;
        }
        $seller_price = round($seller_price, 0);

        if ($seller_price <= 5) {
            $buyer_price = ($seller_price / 0.5) * 1.0 * $myrank_rd_bonus;
        } elseif ($seller_price <= 8) {
            $buyer_price = ($seller_price / 0.55) * 1.1 * $myrank_rd_bonus;
        } elseif ($seller_price <= 11) {
            $buyer_price = ($seller_price / 0.6) * 1.2 * $myrank_rd_bonus;
        } elseif ($seller_price <= 16) {
            $buyer_price = ($seller_price / 0.65) * 1.3 * $myrank_rd_bonus;
        } elseif ($seller_price <= 21) {
            $buyer_price = ($seller_price / 0.7) * 1.5 * $myrank_rd_bonus;
        } elseif ($seller_price <= 26) {
            $buyer_price = ($seller_price / 0.75) * 1.7 * $myrank_rd_bonus;
        } elseif ($seller_price <= 32) {
            $buyer_price = ($seller_price / 0.8) * 1.9 * $myrank_rd_bonus;
        } elseif ($seller_price <= 38) {
            $buyer_price = ($seller_price / 0.85) * 2.25 * $myrank_rd_bonus;
        } elseif ($seller_price <= 45) {
            $buyer_price = ($seller_price / 0.9) * 2.5 * $myrank_rd_bonus;
        } elseif ($seller_price <= 52) {
            $buyer_price = ($seller_price / 0.95) * 2.75 * $myrank_rd_bonus;
        } elseif ($seller_price <= 60) {
            $buyer_price = ($seller_price / 1) * 3 * $myrank_rd_bonus;
        } elseif ($seller_price <= 73) {
            $buyer_price = ($seller_price / 1.125) * 3.25 * $myrank_rd_bonus;
        } elseif ($seller_price <= 88) {
            $buyer_price = ($seller_price / 1.25) * 3.5 * $myrank_rd_bonus;
        } elseif ($seller_price <= 113) {
            $buyer_price = ($seller_price / 1.5) * 4 * $myrank_rd_bonus;
        } elseif ($seller_price <= 140) {
            $buyer_price = ($seller_price / 1.75) * 4.5 * $myrank_rd_bonus;
        } elseif ($seller_price <= 170) {
            $buyer_price = ($seller_price / 2) * 4 * $myrank_rd_bonus;
        } elseif ($seller_price <= 180) {
            $buyer_price = ($seller_price / 2) * 5 * $myrank_rd_bonus;
        } else {
            $buyer_price = ($seller_price / 2) * 5 * $myrank_rd_bonus;
        }
        return round($buyer_price, 0);
    }
}

if (!function_exists('getServerCountry')) {
    function getServerCountry($host)
    {
        $locationData = Location::get($host);
        if (isset($locationData)) {
            if(!isset($locationData->countryName)){
               return "unset" ;
            }else {
               return $locationData->countryName;
            }
        } else return "0";
    }
}
if (!function_exists('checkGoogleIndexDataSerpsbot')) {
    function checkGoogleIndexDataSerpsbot($site)
    {
        $site_url = $site->site_url;
        $apiurl = "https://api.serpsbot.com/v2/google/organic-search";


        $client = new GuzzleHttp\Client(['verify' => false]);
        $response = $client->post($apiurl, [
            'headers' => [
                'X-API-KEY' => env('SERPSBOT_KEY'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ], 'json' => [
                'query' => "site:" . $site_url,
                'pages' => 20
            ]
        ]);
        $data = $response->getBody();
        $result = json_decode($data);


        if ($response->getStatusCode() == 200) {
            Page::where('seller_site_id', '=', $site->id)->whereNull('indexed')->update(['indexed' => 0]);
            if ($result->meta->results > 0) {
                $i = 0;
                $j = 0;
                $bulkURLSubmits = [];
                foreach ($result->data->organic as $data) {
                    //$page = Page::where('seller_site_page_url', '=', $data->url)->where('seller_site_id', '=', $site->id)->first();
                    $page = $page = Page::where(DB::raw('regexp_replace(seller_site_page_url, "^https{0,1}://", "")'), '=', DB::raw('regexp_replace("' . $data->url . '", "^https{0,1}://", "")'))->where('seller_site_id', '=', $site->id)->first();
                    if ($page) {
                        $page->indexed = 1;
                        $page->title = $data->title;
                        $page->update();

                        //adding jobs to queue
                        FindPageCategories::dispatch($page)->onQueue('Category');
                        AddPageDigiMetr::dispatch($page)->onQueue('Digimetr');
                        CalculatePageOBLJob::dispatch($page)->onQueue('HtmlScraper');

                        if ($i == 0) {

                            $url_collection = "";
                        }
                        $url_collection .= $page['seller_site_page_url'] . "\n";
                        $bulkURLSubmits[$j] = $url_collection;
                        $i++;
                        if ($i >= env('MYADDR_BULK_LIMIT')) {
                            $i = 0;
                            $j++;
                        }

                        $pageMetricHistory = PageMetricHistory::where('page_id', $page->id)->whereDate('updated_at', \Carbon\Carbon::today())->first();
                        if (isset($pageMetricHistory) && $pageMetricHistory->id > 0) {
                            $pageMetricHistory->indexed = 1;
                            $pageMetricHistory->update();
                        } else {
                            PageMetricHistory::create(
                                [
                                    'page_id' => $page->id,
                                    'indexed' => 1,
                                ]);
                        }
                    }
                    GoogleIndexUrl::firstOrCreate([
                        'site_id' => $site['id'],
                        'page_url' => $data->url,
                    ]);
                }

                foreach ($bulkURLSubmits as $bulkURLSubmit) {
                    $batch = Batch::create([
                        'file_id' => ''
                    ]);
                    Storage::put($batch->id . "_url.txt", $bulkURLSubmit);
                    $file_ids = add_seo_rank_url_file_upload($batch->id . "_url.txt");
                    if (isset($file_ids) && $file_ids != "") {
                        $batch->moz_file_id = $file_ids['moz_file_id'];
                        //$batch->maj_file_id=$file_ids['maj_file_id'];
                        $batch->update();
                        ProcessBulkPageMyAddrAPI::dispatch($batch)->onQueue('MyAddrAPI')->delay(now()->addMinutes(env('MYADDR_PROCESS_AFTER')));;
                    }
                }

            }
        } else {
            return 0;
            // CheckSitePagesIndexed::dispatch($site)->delay(now()->addSeconds(15));
        }
    }

}
if (!function_exists('checkGoogleIndexDataCrawlbase')) {
    function checkGoogleIndexDataCrawlbase($site, $start = 0)
    {
        $site_url = $site->site_url;
        $apiurl = "https://www.google.com/search?q=site:" . $site_url . "&num=100";
        if ($start > 0) {
            $apiurl .= "&start=" . $start;
        }

        $api = new Crawlbase\ScraperAPI(['token' => env("CRAWLBASE_KEY")]);
        $response = $api->get($apiurl);


        if ($response->statusCode === 200) {
            Page::where('seller_site_id', '=', $site->id)->whereNull('indexed')->update(['indexed' => 0]);
            if (count($response->json->searchResults) > 0) {
                $i = 0;
                $j = 0;
                $bulkURLSubmits = [];
                foreach ($response->json->searchResults as $data) {
                    // match https and http version
                    //where(\DB::raw('regexp_replace'('seller_site_page_url', '^https{0,1}://', '')),'=',DB::raw('regexp_replace'($data->url, '^https{0,1}://', ''))
                    // $page = Page::where('seller_site_page_url', '=', $data->url)->where('seller_site_id', '=', $site->id)->first();
                    $page = Page::where(DB::raw('regexp_replace(seller_site_page_url, "^https{0,1}://", "")'), '=', DB::raw('regexp_replace("' . $data->url . '", "^https{0,1}://", "")'))->where('seller_site_id', '=', $site->id)->first();
                    if ($page) {
                        $page->indexed = 1;
                        $page->title = $data->title;
                        $page->update();

                        //adding jobs to queue
                        FindPageCategories::dispatch($page)->onQueue('Category');
                        AddPageDigiMetr::dispatch($page)->onQueue('Digimetr');
                        CalculatePageOBLJob::dispatch($page)->onQueue('HtmlScraper');
                        if ($i == 0) {

                            $url_collection = "";
                        }
                        $url_collection .= $page['seller_site_page_url'] . "\n";
                        $bulkURLSubmits[$j] = $url_collection;
                        $i++;
                        if ($i >= env('MYADDR_BULK_LIMIT')) {
                            $i = 0;
                            $j++;
                        }

                        $pageMetricHistory = PageMetricHistory::where('page_id', $page->id)->whereDate('updated_at', \Carbon\Carbon::today())->first();
                        if (isset($pageMetricHistory) && $pageMetricHistory->id > 0) {
                            $pageMetricHistory->indexed = 1;
                            $pageMetricHistory->update();
                        } else {
                            PageMetricHistory::create(
                                [
                                    'page_id' => $page->id,
                                    'indexed' => 1,
                                ]);
                        }
                    }
                    GoogleIndexUrl::firstOrCreate([
                        'site_id' => $site['id'],
                        'page_url' => $data->url,
                    ]);
                }

                foreach ($bulkURLSubmits as $bulkURLSubmit) {
                    $batch = Batch::create([
                        'file_id' => ''
                    ]);
                    Storage::put($batch->id . "_url.txt", $bulkURLSubmit);
                    $file_ids = add_seo_rank_url_file_upload($batch->id . "_url.txt");
                    if (isset($file_ids) && $file_ids != "") {
                        $batch->moz_file_id = $file_ids['moz_file_id'];
                        //$batch->maj_file_id=$file_ids['maj_file_id'];
                        $batch->update();
                        ProcessBulkPageMyAddrAPI::dispatch($batch)->onQueue('MyAddrAPI')->delay(now()->addMinutes(env('MYADDR_PROCESS_AFTER')))->onQueue('Digimetr');
                    }
                }
                if (count($response->json->searchResults) == 100) {
                    checkGoogleIndexDataCrawlbase($site, $start + 100);
                }
            }
        } else {
            return 0;
            // CheckSitePagesIndexed::dispatch($site)->delay(now()->addSeconds(15));
        }
    }

}
if (!function_exists('checkGoogleIndexData')) {
    function checkGoogleIndexData($site)
    {
        $api = env('SCRAPERAPI');
        $apiurl = env('SCRAPERURL');
        $site_url = $site->site_url;
        $ch = curl_init();

        $url = $apiurl . '?api_key=' . $api . '&url=https://www.google.com/search?q=site:' . $site_url . '&autoparse=true';
        //echo $url;
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        //print_r($result);
        $result_array = json_decode($result, true);
        if (!is_null($result_array)) {
            Page::where('seller_site_id', '=', $site->id)->whereNull('indexed')->update(['indexed' => 0]);
            if (count($result_array['organic_results']) > 0) {
                $i = 0;
                $j = 0;
                $bulkURLSubmits = [];
                foreach ($result_array['organic_results'] as $data) {
                    // $page = Page::where('seller_site_page_url', '=', $data['link'])->where('seller_site_id', '=', $site->id)->first();
                    $page = Page::where(DB::raw('regexp_replace(seller_site_page_url, "^https{0,1}://", "")'), '=', DB::raw('regexp_replace("' . $data['link'] . '", "^https{0,1}://", "")'))->where('seller_site_id', '=', $site->id)->first();
                    if ($page) {
                        $page->indexed = 1;
                        $page->title = $data['title'];
                        $page->update();

                        //adding jobs to queue
                        FindPageCategories::dispatch($page)->onQueue('Category');
                        AddPageDigiMetr::dispatch($page)->onQueue('Digimetr');
                        CalculatePageOBLJob::dispatch($page)->onQueue('HtmlScraper');

                        if ($i == 0) {

                            $url_collection = "";
                        }
                        $url_collection .= $page['seller_site_page_url'] . "\n";
                        $bulkURLSubmits[$j] = $url_collection;
                        $i++;
                        if ($i >= env('MYADDR_BULK_LIMIT')) {
                            $i = 0;
                            $j++;
                        }

                        $pageMetricHistory = PageMetricHistory::where('page_id', $page->id)->whereDate('updated_at', \Carbon\Carbon::today())->first();
                        if (isset($pageMetricHistory) && $pageMetricHistory->id > 0) {
                            $pageMetricHistory->indexed = 1;
                            $pageMetricHistory->update();
                        } else {
                            PageMetricHistory::create(
                                [
                                    'page_id' => $page->id,
                                    'indexed' => 1,
                                ]);
                        }
                    }
                    GoogleIndexUrl::firstOrCreate([
                        'site_id' => $site['id'],
                        'page_url' => $data['link'],
                    ]);
                }

                foreach ($bulkURLSubmits as $bulkURLSubmit) {
                    $batch = Batch::create([
                        'file_id' => ''
                    ]);
                    Storage::put($batch->id . "_url.txt", $bulkURLSubmit);
                    $file_ids = add_seo_rank_url_file_upload($batch->id . "_url.txt");
                    if (isset($file_ids) && $file_ids != "") {
                        $batch->moz_file_id = $file_ids['moz_file_id'];
                        //$batch->maj_file_id=$file_ids['maj_file_id'];
                        $batch->update();
                        ProcessBulkPageMyAddrAPI::dispatch($batch)->onQueue('MyAddrAPI')->delay(now()->addMinutes(env('MYADDR_PROCESS_AFTER')));;
                    }
                }
            }
        } else {
             return 0;
            //CheckSitePagesIndexed::dispatch($site)->delay(now()->addSeconds(15));
        }
    }

}
if (!function_exists('addSiteForMetricCheck')) {
    function addSiteForMetricCheck($id)
    {
        $site = SellerSite::find($id);
        if ($site) {
            CheckSitePagesIndexed::dispatch($site)->onQueue('IndexCheck')->delay(now()->addSeconds(15));
            /*$i=0;
            $j=0;
            $bulkURLSubmits=[];
            foreach($site->pages as $page){
              FindPageCategories::dispatch($page);
              AddPageDigiMetr::dispatch($page);
              CalculatePageOBLJob::dispatch($page);
              if($i==0){

                $url_collection="";
              }

              $url_collection.=$page['seller_site_page_url']."\n";
              $bulkURLSubmits[$j]=$url_collection;
              $i++;
              if($i>=env('MYADDR_BULK_LIMIT')){
                $i=0;
                $j++;
              }
            }
            foreach($bulkURLSubmits as $bulkURLSubmit){
              $batch=Batch::create([
                'file_id'=>''
              ]);
              Storage::put($batch->id."_url.txt", $bulkURLSubmit);
              $file_ids=add_seo_rank_url_file_upload($batch->id."_url.txt");
              if(isset($file_ids) && $file_ids!=""){
                $batch->moz_file_id=$file_ids['moz_file_id'];
                //$batch->maj_file_id=$file_ids['maj_file_id'];
                $batch->update();
                ProcessBulkPageMyAddrAPI::dispatch($batch)->delay(now()->addMinutes(env('MYADDR_PROCESS_AFTER')));;
              }
            }*/
        }
    }
}

if (!function_exists('addPagesForMetricCheck')) {
    function addPagesForMetricCheck($pages)
    {   
        if (count($pages) > 0) {
            log::info($pages);
            foreach ($pages as $page) {
                CheckPageIndexed::dispatch($page)->onQueue('IndexCheck');
            }
        }
    }
}

if (!function_exists('checkGoogleIndexPageSerpsbot')) {
    function checkGoogleIndexPageSerpsbot($page)
    {

        /* for local debug mode
        $page->indexed = 1;
         $page->title = "my test title";
         $page->update();
         FindPageCategories::dispatch($page);
         AddPageDigiMetr::dispatch($page);
         CalculatePageOBLJob::dispatch($page);
         FindPageSEORankMyAddr::dispatch($page);
         return 1;
         */

        $page_url = $page->seller_site_page_url;
        $apiurl = "https://api.serpsbot.com/v2/google/organic-search";

        $client = new GuzzleHttp\Client(['verify' => false]);
        $response = $client->post($apiurl, [
            'headers' => [
                'X-API-KEY' => env('SERPSBOT_KEY'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ], 'json' => [
                'query' => "site:" . $page_url,
                'pages' => 20
            ]
        ]);
        $data = $response->getBody();
        $result = json_decode($data);


        if ($response->getStatusCode() == 200) {
            if ($result->meta->results > 0) {
                $i = 0;
                $j = 0;
                $bulkURLSubmits = [];
                foreach ($result->data->organic as $data) {
                    if ($page->seller_site_page_url == $data->url) {
                        $page->indexed = 1;
                        $page->title = $data->title;
                        $page->update();

                        //adding jobs to queue
                        FindPageCategories::dispatch($page)->onQueue('Category');
                        AddPageDigiMetr::dispatch($page)->onQueue('Digimetr');
                        CalculatePageOBLJob::dispatch($page)->onQueue('HtmlScraper');
                        FindPageSEORankMyAddr::dispatch($page)->onQueue('MyAddrAPI');


                        $pageMetricHistory = PageMetricHistory::where('page_id', $page->id)->whereDate('updated_at', \Carbon\Carbon::today())->first();
                        if (isset($pageMetricHistory) && $pageMetricHistory->id > 0) {
                            $pageMetricHistory->indexed = 1;
                            $pageMetricHistory->update();
                        } else {
                            PageMetricHistory::create(
                                [
                                    'page_id' => $page->id,
                                    'indexed' => 1,
                                ]);
                        }
                    }
                    GoogleIndexUrl::firstOrCreate([
                        'site_id' => $page->seller_site_id,
                        'page_url' => $data->url,
                    ]);
                }
            } else {
                $page->indexed = 0;
                $page->update();
            }
        } else {
            // CheckPageIndexed::dispatch($page)->delay(now()->addSeconds(15));
            return 0;
        }
        return 1;
    }

}
if (!function_exists('checkGoogleIndexPageCrawlbase')) {
    function checkGoogleIndexPageCrawlbase($page)
    {
        $page_url = $page->seller_site_page_url;
        $apiurl = "https://www.google.com/search?q=site:" . $page_url;

        $api = new Crawlbase\ScraperAPI(['token' => env("CRAWLBASE_KEY")]);
        $response = $api->get($apiurl);


        if ($response->statusCode === 200) {
            if (count($response->json->searchResults) > 0) {
                $i = 0;
                $j = 0;
                $bulkURLSubmits = [];
                foreach ($response->json->searchResults as $data) {
                    if ($page->seller_site_page_url == $data->url) {
                        $page->indexed = 1;
                        $page->title = $data->title;
                        $page->update();

                        //adding jobs to queue
                        FindPageCategories::dispatch($page)->onQueue('Category');
                        AddPageDigiMetr::dispatch($page)->onQueue('Digimetr');
                        CalculatePageOBLJob::dispatch($page)->onQueue('HtmlScraper');
                        FindPageSEORankMyAddr::dispatch($page)->onQueue('MyAddrAPI');


                        $pageMetricHistory = PageMetricHistory::where('page_id', $page->id)->whereDate('updated_at', \Carbon\Carbon::today())->first();
                        if (isset($pageMetricHistory) && $pageMetricHistory->id > 0) {
                            $pageMetricHistory->indexed = 1;
                            $pageMetricHistory->update();
                        } else {
                            PageMetricHistory::create(
                                [
                                    'page_id' => $page->id,
                                    'indexed' => 1,
                                ]);
                        }
                    }
                    GoogleIndexUrl::firstOrCreate([
                        'site_id' => $page->seller_site_id,
                        'page_url' => $data->url,
                    ]);
                }
            } else {
                $page->indexed = 0;
                $page->update();
            }
        } else {
            // CheckPageIndexed::dispatch($page)->delay(now()->addSeconds(15));
            return 0;
        }
        return 1;
    }

}


if (!function_exists('checkGoogleIndexPage')) {
    function checkGoogleIndexPage($page)
    {
        $api = env('SCRAPERAPI');
        $apiurl = env('SCRAPERURL');
        $page_url = $page->seller_site_page_url;
        $ch = curl_init();

        $url = $apiurl . '?api_key=' . $api . '&url=https://www.google.com/search?q=site:' . $page_url . '&autoparse=true';
        //echo $url;
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        //print_r($result);
        $result_array = json_decode($result, true);
        if (!is_null($result_array)) {
            if (count($result_array['organic_results']) > 0) {
                $i = 0;
                $j = 0;
                $bulkURLSubmits = [];
                foreach ($result_array['organic_results'] as $data) {
                    if ($page->seller_site_page_url == $data['link']) {
                        $page->indexed = 1;
                        $page->title = $data['title'];
                        $page->update();

                        //adding jobs to queue
                        FindPageCategories::dispatch($page)->onQueue('Category');
                        AddPageDigiMetr::dispatch($page)->onQueue('Digimetr');
                        CalculatePageOBLJob::dispatch($page)->onQueue('HtmlScraper');
                        FindPageSEORankMyAddr::dispatch($page)->onQueue('MyAddrAPI');


                        $pageMetricHistory = PageMetricHistory::where('page_id', $page->id)->whereDate('updated_at', \Carbon\Carbon::today())->first();
                        if (isset($pageMetricHistory) && $pageMetricHistory->id > 0) {
                            $pageMetricHistory->indexed = 1;
                            $pageMetricHistory->update();
                        } else {
                            PageMetricHistory::create(
                                [
                                    'page_id' => $page->id,
                                    'indexed' => 1,
                                ]);
                        }
                    }
                    GoogleIndexUrl::firstOrCreate([
                        'site_id' => $page->seller_site_id,
                        'page_url' => $data['link'],
                    ]);
                }
            } else {
                $page->indexed = 0;
                $page->update();
            }
        } else {
            return "0";
        }
        return "1";
    }

}

if (!function_exists('refundByLinkID')) {
    function refundByLinkID($link_id, $seller_id, $refund_reason)
    {
        // 	1: Cancelled by buyer, 2: Cancelled by seller, 3: Cancelled by admin, 4: Cancelled website offline
        switch ($refund_reason) {
            case 1:
                $reason = "Cancelled by Buyer";
                break;
            case 2:
                $reason = "Cancelled by Seller";
                break;
            case 3:
                $reason = "Cancelled by Admin";
                break;
            case 4:
                $reason = "Cancelled website offline";
                break;
        }
        $Link = Links::where('seller_id', '=', $seller_id)->where('id', $link_id)->first();
        $order_detail = OrderDetail::where('blmkt_links_id', $link_id)->where('order_id', $Link->last_order_id)->first();
        $order = Order::where('id', $order_detail->order_id)->first();
        $link_online_days = (new DateTime(now()))->diff(new DateTime($Link->created_at))->days;
        if ($link_online_days > 7) {
            // Partial Refund
            $total_month_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            $link_online_percentage = $link_online_days / $total_month_days; //bsp 20 tage online / 30 Monatstage= 0,67

            //Buyer
            $buyer_fee = $Link->page_price_buyer * env("TRANSACTION_FEE");
            $refund_amount_buyer = round($Link->page_price_buyer - ($Link->page_price_buyer * $link_online_percentage) - $buyer_fee, 2);

            //Seller
            $seller_fee = $Link->page_price_seller * env("TRANSACTION_FEE");
            $refund_amount_seller = round(($Link->page_price_seller * $link_online_percentage) - $seller_fee, 2);
            $refund_status = 1; // 0:no refund, 1:partial refund, 2:total refund
        } else {
            // Full Refund
            // was online less than 7 days
            // Full refund to buyer minus 10% Transaction fee
            // No money to Seller
            $refund_amount_seller = 0;
            $refund_amount_buyer = round($Link->page_price_buyer - ($Link->page_price_buyer * env("TRANSACTION_FEE")), 2);
            $refund_status = 2; // 0:no refund, 1:partial refund, 2:total refund
        }

        // status needs to be 0 else add_links_seller_blog would still add the link to blog
        $link_detail = Links::where('id', $Link->id)->first();
        $link_detail->status = 0;
        $link_detail->update();
        if (add_links_seller_blog($Link->page_id) != "success") {
            $params['subject'] = "Error on add_link_seller_blog w. page_id: " . $Link->page_id;
            $params['message'] = "Refund process Failed removing Link after Refund. Order Details id: " . $order_detail->id . "\r\n" . "Check Page: " . $order_detail->page_url . " for link: " . $order_detail->promoted_url . " with anchor: " . $order_detail->anchor_text;
            Mail::to(config('app.admin_email'))->send(new MailAdminNotification($params));
            // on error revert whole process
            $link_detail->status = 20;
            $link_detail->update();
            return "failed to update blog";
        }


        $order_detail->refund_status = $refund_status;
        $order_detail->refund_amount_buyer = $refund_amount_buyer;
        $order_detail->refund_amount_seller = $refund_amount_seller;
        $order_detail->refund_reason = $refund_reason;
        $order_detail->update();


        $total_refund_amount = $refund_amount_buyer + $refund_amount_seller;
        if ($refund_status == 2) {
            if (($total_refund_amount * 0.2) + $total_refund_amount + $Link->page_price_buyer <= $order->order_amount) {
                $refund_status = 1; // 0:no refund, 1:partial refund, 2:total refund
            }
        }

        $order->refund_status = $refund_status;
        $order->refund_amount_buyer += $refund_amount_buyer;
        $order->refund_amount_seller += $refund_amount_seller;
        $order->update();

        // add refund amount to user balance
        $transaction = UserBalanceHistory::create([
            'user_id' => $Link->buyer_id,
            'reference_type' => \Config::get('constants.reference_type.refund'),
            'amount' => $refund_amount_buyer,
            'order_id' => $order_detail->id,
            'history_description' => 'Refund (' . $reason . ') - OrderDetailsID: ' . $order_detail->id,
            'balance_type' => \Config::get('constants.balance_type.credit'),
            'balance_account' => \Config::get('constants.balance_account.buyer')
        ]);

        $notification = new Notification();
        $notification->user_id = $Link->buyer_id;
        $notification->type = "Link (ID" . $Link->id . ") has been refunded with " . \Config::get('app.currency_symbol') . $refund_amount_buyer . " because Link was " . lcfirst($reason);
        $notification->save();

        $user = User::where('id', $order->user_id)->first();
        $user->balance += $refund_amount_buyer;
        $user->update();
        exit;
    }


    return "success";


}
