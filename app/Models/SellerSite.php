<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerSite extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */

    protected $table = 'blmkt_sites';

    protected $fillable = [
        'id', 'seller_id', 'site_url', 'site_auth_key', 'is_active', 'country', 'tld', 'plugin_version', 'is_ban', 'update_remote_authkey_allowed'
    ];

    public function pages()
    {
        return $this->hasMany(\App\Models\Page::class, 'seller_site_id');
    }


    public function save(array $options = [])
    {

      $isCreated=parent::save($options);
      if($isCreated){
        if($this->tld=="0" || $this->tld==""){
          $url=$this->site_url;
          $parse_url=parse_url($url, PHP_URL_HOST);
          //print_r($parse_url); exit;
          $url_parts=explode(".",$parse_url );
          $tld=end($url_parts);
          //echo $tld; exit;
          $this->tld=$tld;
          $this->update();
        }
        if($this->country=="0" || $this->country==""){
          $this->country=getServerCountry($this->site_url);
          $this->update();
        }
        //add_google_index_url_quey($this->site_url,$this->id);

      }

    }
}
