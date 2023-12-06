<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Models\Withdraw;
use App\Models\User;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Carbon;
use Ibericode\Vat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReceiptController extends Controller
{


    public function index($id, $opt_user_id = NULL)
    {

        $validator = Validator::make(['id' => $id], ['id' => 'required|integer|gt:0']);
        if ($validator->fails()) {
            return "Access forbidden!";
        }
        if (isset($opt_user_id)) {
            $user_id = $opt_user_id;
        } else {
            $user_id = \Auth::user()->id;
        }

        if (isset($id)) {
            $receipt = Withdraw::where("id", $id)->where("user_id", $user_id)->where("amount_withdrawn", ">", 0)->first();
            if (!isset($receipt)) return "Access forbidden!";
            if ($receipt->count() == 0) return "Access forbidden!";
        } else return "Access forbidden!";

        if (Storage::disk('invoice')->exists($user_id . '_' . $id . '.pdf')) {
            return response()->download(Storage::path("invoice") . '\\' . $user_id . '_' . $id . '.pdf');
        }
        $countries = new Vat\Countries();
        $user = User::find($user_id);

        $receiver = new Party([
            'name' => $user->firstname . " " . $user->lastname,
            'address' => $user->street . " " . $user->street_number,
            'code' => $user->zip . " " . $user->city,
            'vat' => $user->vat,
            'custom_fields' => [
                'country' => $countries[$user->country]
            ],
        ]);

        $Backlinkmarket = new Party([
            'name' => "SEO Service",
            'address' => "Alter Landweg 74",
            'code' => "25795 Weddingstedt",
            'vat' => "DE815247111",
            'custom_fields' => [
                'country' => "Germany"
            ],
        ]);

        $items = [
            (new InvoiceItem())
                ->title('Credit Payout (ID:' . $receipt->id . ')')
                ->description('Paid via PayPal to: ' . $receipt->paypal_email)
                ->pricePerUnit($receipt->amount)


        ];

        $notes = [
            'According to the reverse-charge regulation tax liability transfers to the recipient of services. '

        ];
        $notes = implode("<br>", $notes);
        if ($user->country == "DE" && $user->vat != "") {
            $tax = 19;
            $template = "receipt_de";
            if ($user->kleinunternehmer == 1) {
                $notes = "Kleinunternehmer nach § 19 UStG";
                $tax = 0;
            } else $notes = "";

        } elseif ($user->country == "DE" && $user->vat == "") {
            $tax = 0;
            $template = "receipt_de";
            $notes = "";
        } else {
            $tax = 0;
            $template = "receipt_world";
        }

        $invoice = Invoice::make('Self Billing Invoice')
            ->template($template)
            ->series($receipt->id)
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence(date("Y"))
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($receiver)
            ->buyer($Backlinkmarket)
            ->date(Carbon::parse($receipt->payout_at))
            ->dateFormat('d.m.Y')
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->currencyFormat('{VALUE}{SYMBOL}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($user_id . '_' . $id)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('/images/logo.png'))
            ->taxRate($tax);
        //->totalTaxes(0,0)
        // You can additionally save generated invoice to configured disk
        $invoice->save("invoice");


        return $invoice->stream();


    }
}
