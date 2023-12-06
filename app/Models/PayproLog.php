<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayproLog extends Model
{
  use HasFactory;

  protected $table = 'blmkt_paypro_logs';

  protected $fillable = [
      'order_id','paypro_order_id','product_id','order_referrer_url','order_status','customer_first_name','customer_last_name','customer_email','customer_country_name','customer_state_name','order_item_id','order_item_name','order_currency_code','order_item_vendor_amount','order_item_price','order_item_unit_price','order_item_total_amount','order_item_paypro_expenses_amount','order_total_amount','order_taxes_amount','order_total_amount_shown','order_total_amount_with_taxes_shown','vendor_balance_currency_code','order_item_balance_currency_vendor_amount','order_item_balance_currency_total_amount','order_item_balance_currency_paypro_amount','order_item_balance_currency_paypro_expenses_amount','order_total_balance_currency_amount','order_taxes_balance_currency_amount','order_balance_currency_vendor_amount','order_item_refunded','order_item_vendor_refunded','order_item_balance_currency_refunded','order_item_balance_currency_vendor_refunded','order_refunded','order_vendor_refunded','order_balance_currency_refunded','order_balance_currency_vendor_refunded','order_item_tax_name_1','order_item_tax_rate_1','payment_method_id','payment_method_name','order_placed_time_utc','order_placed_time_customer_timezone','customer_timezone','customer_id','company_name','customer_name_ascii','customer_first_name_ascii','customer_last_name_ascii','customer_ip','customer_country_code_by_ip','customer_country_name_by_ip','customer_country_code','customer_phone','customer_language_code','customer_state_code','customer_city','customer_street_address','customer_zipcode','order_custom_fields','corporate_purchase','subscription_id','ec_subscription_id','subscription_status_id','subscription_status_name','subscription_next_charge_date','subscription_renewal_type','subscription_initial_order_id','subscription_initial_ec_order_id','creditcard_last4','paypal_account','is_delayed_payment','ipn_type_id','ipn_type_name','paypro_global','hash','signature','test_mode','invoice_link','pk_user'];

  public function order(){
    return $this->belongsTo(Order::class,'order_id','id');
  }

  public function page(){
    return $this->belongsTo(Page::class)->withTrashed();
  }


}
