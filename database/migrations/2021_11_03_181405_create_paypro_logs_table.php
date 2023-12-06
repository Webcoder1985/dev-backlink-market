<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayproLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blmkt_paypro_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('paypro_order_id');
            $table->integer('product_id');
            $table->text('order_referrer_url')->nullable();
            $table->string('order_status')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_country_name')->nullable();
            $table->string('customer_state_name')->nullable();
            $table->integer('order_item_id');
            $table->string('order_item_name')->nullable();
            $table->string('order_currency_code')->nullable();
            $table->decimal('order_item_vendor_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_price',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_unit_price',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_total_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_paypro_expenses_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_total_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_taxes_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_total_amount_shown',10,2)->unsigned()->default(0.00);
            $table->decimal('order_total_amount_with_taxes_shown',10,2)->unsigned()->default(0.00);
            $table->string('vendor_balance_currency_code')->nullable();
            $table->decimal('order_item_balance_currency_vendor_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_balance_currency_total_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_balance_currency_paypro_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_balance_currency_paypro_expenses_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_total_balance_currency_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_taxes_balance_currency_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_balance_currency_vendor_amount',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_refunded',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_vendor_refunded',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_balance_currency_refunded',10,2)->unsigned()->default(0.00);
            $table->decimal('order_item_balance_currency_vendor_refunded',10,2)->unsigned()->default(0.00);
            $table->decimal('order_refunded',10,2)->unsigned()->default(0.00);
            $table->decimal('order_vendor_refunded',10,2)->unsigned()->default(0.00);
            $table->decimal('order_balance_currency_refunded',10,2)->unsigned()->default(0.00);
            $table->decimal('order_balance_currency_vendor_refunded',10,2)->unsigned()->default(0.00);
            $table->string('order_item_tax_name_1')->nullable();
            $table->decimal('order_item_tax_rate_1',10,2)->unsigned()->default(0.00);
            $table->integer('payment_method_id');
            $table->string('payment_method_name')->nullable();
            $table->string('order_placed_time_utc');
            $table->string('order_placed_time_customer_timezone');
            $table->string('customer_timezone')->nullable();
            $table->integer('customer_id');
            $table->string('company_name')->nullable();
            $table->string('customer_name_ascii')->nullable();
            $table->string('customer_first_name_ascii')->nullable();
            $table->string('customer_last_name_ascii')->nullable();
            $table->string('customer_ip')->nullable();
            $table->string('customer_country_code_by_ip')->nullable();
            $table->string('customer_country_name_by_ip')->nullable();
            $table->string('customer_country_code')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_language_code')->nullable();
            $table->string('customer_state_code')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_street_address')->nullable();
            $table->string('customer_zipcode')->nullable();
            $table->string('order_custom_fields')->nullable();
            $table->string('corporate_purchase')->nullable();
            $table->string('subscription_id')->nullable();
            $table->string('ec_subscription_id')->nullable();
            $table->string('subscription_status_id')->nullable();
            $table->string('subscription_status_name')->nullable();
            $table->string('subscription_next_charge_date')->nullable();
            $table->string('subscription_renewal_type')->nullable();
            $table->string('subscription_initial_order_id')->nullable();
            $table->string('subscription_initial_ec_order_id')->nullable();
            $table->integer('creditcard_last4');
            $table->string('paypal_account')->nullable();
            $table->integer('is_delayed_payment');
            $table->integer('ipn_type_id');
            $table->string('ipn_type_name')->nullable();
            $table->integer('paypro_global');
            $table->text('hash')->nullable();
            $table->text('signature')->nullable();
            $table->integer('test_mode');
            $table->text('invoice_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypro_logs');
    }
}
