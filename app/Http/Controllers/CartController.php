<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Page;
use App\Models\SellerSite;
use App\Rules\ContainsInput;

use Illuminate\Support\Facades\Session;
class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
       $country = \Auth::user()->country;
       $country_detail = $this->country_code_to_country($country);
       $country_array = json_decode($country_detail,true);
       $tax = $country_array['tax'];
       $total_days=cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
       $left_days=$total_days-date('d');
       //\Cart::setGlobalTax($tax);
       $tax=0;
        $carts = \Cart::content();
         return view('member.carts',compact('carts'),['tax'=>$tax,'total_days'=>$total_days,'left_days'=>$left_days]);
     }
    public  function saveCart(Request $request)
    {
        if ($request->ajax()) {
          $request->validate([
              'promoted_url' => 'required|url',
              'anchor_text' => 'required',
              'link_content' => ['required','string','max:350','min:1',new ContainsInput($request,'anchor_text')]
          ], [], [
              'promoted_url' => 'Your URL',
              'anchor_text' => 'Anchor Text',
              'link_content' => 'Content Text'
          ]);

          $data = $request->all();
          $prids = $data['product_id'];

              $page = Page::find($prids);
              $title = $page['seller_site_page_url'];
              $price = $page['page_price_buyer'];
              $cart_content=\Cart::content();
              if(count($cart_content)>0){
                  foreach($cart_content as $item){
                      if($item->id==$prids && $item->options->promoted_url==$data['promoted_url'] ){
                           return response()->json(['errors' => ['msg' => 'You already have this product in your Cart.']], 422);
                      }
                  }
              }
              $cart = \Cart::add(['id' => $prids, 'name' =>$title, 'qty' => 1, 'price' => $price, 'weight' => '1.00', 'options' => ['promoted_url' => $data['promoted_url'],'link_content' => $data['link_content'],'anchor_text' => $data['anchor_text'],'no_follow' => (isset($data['no_follow']) ? 1 : 0)]]);


          //$cart = \Cart::content();
          $response['success']=true;
          $response['message']= \Cart::count()." links for ".\Config::get('app.currency_symbol').\Cart::total();
          $response['product_id']= $prids;
          $response['cart_id']= $cart->rowId;
          $response['price'] = $price;
          $response['cart_count'] = count(session()->get('cart')['default']);
          return json_encode($response);
        }
    }

    public  function updateCart(Request $request)
    {
        if ($request->ajax()) {
         $request->validate([
              'promoted_url' => 'required|url',
              'anchor_text' => 'required',
              'link_content' => ['required','string','max:350','min:1',new ContainsInput($request,'anchor_text')]
          ], [], [
              'promoted_url' => 'Your URL',
              'anchor_text' => 'Anchor Text',
              'link_content' => 'Content Text'
          ]);

          $data = $request->all();
          $prids = $data['product_id'];

              $page = Page::find($prids);
              $title = $page['seller_site_page_url'];
              $price = $page['page_price_buyer'];
              $rowId = $data['rowId'];

              $cart = \Cart::update($rowId,['price' => $price, 'options' => ['promoted_url' => $data['promoted_url'],'link_content' => $data['link_content'],'anchor_text' => $data['anchor_text'],'no_follow' => (isset($data['no_follow']) ? 1 : 0)]]);


          //$cart = \Cart::content();
          $response['success']=true;
          $response['message']= \Cart::count()." links for ".\Config::get('app.currency_symbol').\Cart::total();
          $response['product_id']= $prids;
          $response['cart_id']= $cart->rowId;
            $response['price'] = $price;
            $response['cart_count'] = count(session()->get('cart')['default']);
          return json_encode($response);
        }
    }

    public function deleteCartItem($id, Request $request)
    {
        if(isset($id)) {
            \Cart::remove($id);
            return response()->json(['success' => 'success', 'cart_count' => count(session()->get('cart')['default'])], 200);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }

    public function country_code_to_country($code)
    {
      $country = '';
      $tax="0";
 if( $code == 'AD' ) { $country = 'Andorra'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AE' ) { $country = 'Vereinigte Arabische Emirate'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AF' ) { $country = 'Afghanistan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AG' ) { $country = 'Antigua und Barbuda'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AI' ) { $country = 'Anguilla'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AL' ) { $country = 'Albanien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AM' ) { $country = 'Armenien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AN' ) { $country = 'Niederländische Antillen'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AO' ) { $country = 'Angola'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AQ' ) { $country = 'Antarktis'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AR' ) { $country = 'Arlgentinien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AS' ) { $country = 'Amerikanisch-Samoa'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AT' ) { $country = 'Österreich'; $kategorie = 'EU';$tax="20";}
if( $code == 'AU' ) { $country = 'Australien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AW' ) { $country = 'Aruba'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AX' ) { $country = 'Aland Islands'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'AZ' ) { $country = 'Azerbaijan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BA' ) { $country = 'Bosnien-Herzegovina'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BB' ) { $country = 'Barbados'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BD' ) { $country = 'Bangladesh'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BE' ) { $country = 'Belgien'; $kategorie = 'EU';$tax="21";}
if( $code == 'BF' ) { $country = 'Burkina Faso'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BG' ) { $country = 'Bulgarien'; $kategorie = 'EU';$tax="20";}
if( $code == 'BH' ) { $country = 'Bahrain'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BI' ) { $country = 'Burundi'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BJ' ) { $country = 'Benin'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BL' ) { $country = 'Saint Barthelemy'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BM' ) { $country = 'Bermudas'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BN' ) { $country = 'Brunei Darussalam'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BO' ) { $country = 'Bolivien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BR' ) { $country = 'Brasilien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BS' ) { $country = 'Bahamas'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BT' ) { $country = 'Bhutan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BV' ) { $country = 'Bouvet Island'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BW' ) { $country = 'Botswana'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BY' ) { $country = 'Weißrußland'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'BZ' ) { $country = 'Belize'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CA' ) { $country = 'Canada'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CI' ) { $country = 'Canary Islands'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CC' ) { $country = 'Cocos Islands'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CD' ) { $country = 'Demokratische Republik Kongo'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CF' ) { $country = 'Zentralafrikanische Republik'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CG' ) { $country = 'Kongo'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CH' ) { $country = 'Schweiz'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CI' ) { $country = 'Elfenbeinküste'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CK' ) { $country = 'Cook Islands'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CL' ) { $country = 'Chile'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CM' ) { $country = 'Kamerun'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CN' ) { $country = 'China'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CO' ) { $country = 'Kolumbien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CR' ) { $country = 'Costa Rica'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CS' ) { $country = 'Tschechoslowakei'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CU' ) { $country = 'Kuba'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CV' ) { $country = 'Kap Verde'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CX' ) { $country = 'Christmas Island'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'CY' ) { $country = 'Zypern'; $kategorie = 'EU';$tax="19";}
if( $code == 'CZ' ) { $country = 'Tschechische Republik'; $kategorie = 'EU';$tax="21";}
if( $code == 'DE' ) { $country = 'Deutschland'; $kategorie = 'Deutschland';$tax="19";}
if( $code == 'DJ' ) { $country = 'Djibouti'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'DK' ) { $country = 'Dänemark'; $kategorie = 'EU';$tax="25";}
if( $code == 'DM' ) { $country = 'Dominica'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'DO' ) { $country = 'Dominikanische Republik'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'DZ' ) { $country = 'Algerien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'EC' ) { $country = 'Ecuador'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'EE' ) { $country = 'Estland'; $kategorie = 'EU';$tax="20";}
if( $code == 'EG' ) { $country = 'Ägypten'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'EH' ) { $country = 'Westsahara'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'ER' ) { $country = 'Eritrea'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'ES' ) { $country = 'Spanien'; $kategorie = 'EU';$tax="21";}
if( $code == 'ET' ) { $country = 'Äthiopien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'FI' ) { $country = 'Finnland'; $kategorie = 'EU';$tax="24";}
if( $code == 'FJ' ) { $country = 'Fiji'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'FK' ) { $country = 'Falkland-Inseln'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'FM' ) { $country = 'Micronesien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'FO' ) { $country = 'Faröer-Inseln'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'FR' ) { $country = 'Frankreich'; $kategorie = 'EU';$tax="20";}
if( $code == 'FX' ) { $country = 'France Metropolitan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GA' ) { $country = 'Gabon'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GB' ) { $country = 'Großbritannien'; $kategorie = 'EU';$tax="20";}
if( $code == 'UK' ) { $country = 'Großbritannien'; $kategorie = 'EU';$tax="20";}
if( $code == 'GD' ) { $country = 'Grenada'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GE' ) { $country = 'Georgien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GF' ) { $country = 'Französisch Guiana'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GG' ) { $country = 'Guernsey Kanalinsel'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GH' ) { $country = 'Ghana'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GI' ) { $country = 'Gibraltar'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GL' ) { $country = 'Grönland'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GM' ) { $country = 'Gambia'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GN' ) { $country = 'Guinea'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GP' ) { $country = 'Guadeloupe'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GQ' ) { $country = 'Äquatorialguinea'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GR' ) { $country = 'Griechenland'; $kategorie = 'EU';$tax="24";}
if( $code == 'GS' ) { $country = 'Südgeorgien und Südliche Sandwich-Inseln'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GT' ) { $country = 'Guatemala'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GU' ) { $country = 'Guam'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GW' ) { $country = 'Guinea-Bissau'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'GY' ) { $country = 'Guyana'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'HK' ) { $country = 'Hong Kong'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'HM' ) { $country = 'Heard und Mc Donald Islands'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'HN' ) { $country = 'Honduras'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'HR' ) { $country = 'Kroatien'; $kategorie = 'EU';$tax="25";}
if( $code == 'HT' ) { $country = 'Haiti'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'HU' ) { $country = 'Ungarn'; $kategorie = 'EU';$tax="27";}
if( $code == 'ID' ) { $country = 'Indonesien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'IE' ) { $country = 'Irland'; $kategorie = 'EU';$tax="23";}
if( $code == 'IL' ) { $country = 'Israel'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'IM' ) { $country = 'Insel Man'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'IN' ) { $country = 'Indien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'IO' ) { $country = 'Britisches Territorium im Indischen Ozean'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'IQ' ) { $country = 'Irak'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'IR' ) { $country = 'Iran'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'IS' ) { $country = 'Island'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'IT' ) { $country = 'Italien'; $kategorie = 'EU';$tax="22";}
if( $code == 'JE' ) { $country = 'Jersey Kanalinsel'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'JM' ) { $country = 'Jamaica'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'JO' ) { $country = 'Jordanien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'JP' ) { $country = 'Japan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KE' ) { $country = 'Kenya'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KG' ) { $country = 'Kirgisien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KH' ) { $country = 'Königreich Kambodscha'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KI' ) { $country = 'Kiribati'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KM' ) { $country = 'Komoren'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KN' ) { $country = 'Saint Kitts und Nevis'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KP' ) { $country = 'Korea Volksrepublik'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KR' ) { $country = 'Korea'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KW' ) { $country = 'Kuwait'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KY' ) { $country = 'Kaiman Inseln'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'KZ' ) { $country = 'Kasachstan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'LA' ) { $country = 'Laos'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'LB' ) { $country = 'Libanon'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'LC' ) { $country = 'Saint Lucia'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'LI' ) { $country = 'Liechtenstein'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'LK' ) { $country = 'Sri Lanka'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'LR' ) { $country = 'Liberia'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'LS' ) { $country = 'Lesotho'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'LT' ) { $country = 'Litauen'; $kategorie = 'EU';$tax="21";}
if( $code == 'LU' ) { $country = 'Luxemburg'; $kategorie = 'EU';$tax="17";}
if( $code == 'LV' ) { $country = 'Lettland'; $kategorie = 'EU';$tax="21";}
if( $code == 'LY' ) { $country = 'Libyen'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MA' ) { $country = 'Marokko'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MC' ) { $country = 'Monaco'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MD' ) { $country = 'Moldavien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'ME' ) { $country = 'Montenegro'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MF' ) { $country = 'Saint-Martin'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MG' ) { $country = 'Madagaskar'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MH' ) { $country = 'Marshall-Inseln'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MK' ) { $country = 'Mazedonien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'ML' ) { $country = 'Mali'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MM' ) { $country = 'Myanmar'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MN' ) { $country = 'Mongolei'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MO' ) { $country = 'Macao'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MP' ) { $country = 'Nördliche Marianneninseln'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MQ' ) { $country = 'Martinique'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MR' ) { $country = 'Mauretanien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MS' ) { $country = 'Montserrat'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MT' ) { $country = 'Malta'; $kategorie = 'EU';$tax="18";}
if( $code == 'MU' ) { $country = 'Mauritius'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MV' ) { $country = 'Malediven'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MW' ) { $country = 'Malawi'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MX' ) { $country = 'Mexico'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MY' ) { $country = 'Malaysien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'MZ' ) { $country = 'Mozambique'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NA' ) { $country = 'Namibia'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NC' ) { $country = 'Neu Kaledonien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NE' ) { $country = 'Niger'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NF' ) { $country = 'Norfolk Insel'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NG' ) { $country = 'Nigeria'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NI' ) { $country = 'Nicaragua'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NL' ) { $country = 'Niederlande'; $kategorie = 'EU';$tax="21";}
if( $code == 'NO' ) { $country = 'Norwegen'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NP' ) { $country = 'Nepal'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NR' ) { $country = 'Nauru'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NU' ) { $country = 'Niue'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'NZ' ) { $country = 'Neuseeland'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'OM' ) { $country = 'Oman'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PA' ) { $country = 'Panama'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PE' ) { $country = 'Peru'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PF' ) { $country = 'Französisch Polynesien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PG' ) { $country = 'Papua Neuguinea'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PH' ) { $country = 'Philippinen'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PK' ) { $country = 'Pakistan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PL' ) { $country = 'Polen'; $kategorie = 'EU';$tax="23";}
if( $code == 'PM' ) { $country = 'St. Pierre und Miquelon'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PN' ) { $country = 'Pitcairn'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PR' ) { $country = 'Puerto Rico'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PS' ) { $country = 'Staat Palästina'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PT' ) { $country = 'Portugal'; $kategorie = 'EU';$tax="23";}
if( $code == 'PW' ) { $country = 'Palau'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'PY' ) { $country = 'Paraguay'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'QA' ) { $country = 'Katar'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'RE' ) { $country = 'Reunion'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'RO' ) { $country = 'Rumänien'; $kategorie = 'EU';$tax="19";}
if( $code == 'RS' ) { $country = 'Serbien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'RU' ) { $country = 'Russische Föderation'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'RW' ) { $country = 'Ruanda'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SA' ) { $country = 'Saudi Arabien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SB' ) { $country = 'Salomonen'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SC' ) { $country = 'Seychellen'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SD' ) { $country = 'Sudan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SE' ) { $country = 'Schweden'; $kategorie = 'EU';$tax="25";}
if( $code == 'SG' ) { $country = 'Singapur'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SH' ) { $country = 'St. Helena'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SI' ) { $country = 'Slovenien'; $kategorie = 'EU';$tax="22";}
if( $code == 'SJ' ) { $country = 'Svalbard und Jan Mayen Islands'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SK' ) { $country = 'Slowakei'; $kategorie = 'EU';$tax="20";}
if( $code == 'SL' ) { $country = 'Sierra Leone'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SM' ) { $country = 'San Marino'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SN' ) { $country = 'Senegal'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SO' ) { $country = 'Somalia'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SR' ) { $country = 'Surinam'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'ST' ) { $country = 'Sao Tome und Principe'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SV' ) { $country = 'El Salvador'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SY' ) { $country = 'Syrien Arabische Republik'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'SZ' ) { $country = 'Swaziland'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TC' ) { $country = 'Turk und Caicos-Inseln'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TD' ) { $country = 'Tschad'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TF' ) { $country = 'Französisches Südl.Territorium'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TG' ) { $country = 'Togo'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TH' ) { $country = 'Thailand'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TJ' ) { $country = 'Tadschikistan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TK' ) { $country = 'Tokelau'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TL' ) { $country = 'Osttimor'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TM' ) { $country = 'Turkmenistan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TN' ) { $country = 'Tunesien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TO' ) { $country = 'Tonga'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TP' ) { $country = 'Ost-Timor'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TR' ) { $country = 'Türkei'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TT' ) { $country = 'Trinidad und Tobago'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TV' ) { $country = 'Tuvalu'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TW' ) { $country = 'Taiwan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'TZ' ) { $country = 'Tansania United Republic of'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'UA' ) { $country = 'Ukraine'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'UG' ) { $country = 'Uganda'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'UM' ) { $country = 'Vereinigte Staaten Minor Outlying Islands'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'US' ) { $country = 'Vereinigte Staaten'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'UY' ) { $country = 'Uruguay'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'UZ' ) { $country = 'Usbekistan'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'VA' ) { $country = 'Vatikanstaat'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'VC' ) { $country = 'Saint Vincent und Grenadines'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'VE' ) { $country = 'Venezuela'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'VG' ) { $country = 'Virgin Islands (Britisch)'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'VI' ) { $country = 'Virgin Islands (U.S.)'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'VN' ) { $country = 'Vietnam'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'VU' ) { $country = 'Vanuatu'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'WF' ) { $country = 'Wallis und Futuna Islands'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'WS' ) { $country = 'Samoa'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'YE' ) { $country = 'Jemen'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'YT' ) { $country = 'Mayotte'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'YU' ) { $country = 'Jugoslawien'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'ZA' ) { $country = 'Südafrika'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'ZM' ) { $country = 'Sambia'; $kategorie = 'Drittland';$tax="0";}
if( $code == 'ZW' ) { $country = 'Zimbabwe'; $kategorie = 'Drittland';$tax="0";}
 if( $country == '') { $country = $code; $kategorie= '-'; }
 return json_encode(['status' => 'success', 'country' => $country,'kategorie' =>$kategorie,'tax'=>$tax]);
    }

}
