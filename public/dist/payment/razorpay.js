"use strict";

let debitcard = {
  method: "card",
  types: ["debit"]
};
let creditcard = {
  method: "card",
  types: ["credit"]
};
let allcard = {
  method: "card",
  types: ["debit","credit"]
};
let netbanking = {
  method: "netbanking"
};
let upi = {
  method: "upi"
};
let wallets = {
  method: "wallets"
};

// let banks = {name: 'Pay using card',instruments: [{method: 'card',types: ["credit"]},]};



let hiddenInstruments = [upi, wallets];

var logo = 'https://smartpaytech.in/public/template_assets/assets/images/icon.png';
var options = {
    "key": $('#razorpayId').val(),
    "amount": $('#rzp_amount').val(),
    "currency": 'INR',
    "name": 'SmartPay',
    "description": $('#rzp_description').val(),
    "image": logo,
    "order_id": $('#rzp_orderId').val(),
    config: {
    display: {
      blocks: {
        banks: {
          name: 'Pay using card',
          instruments: [
            {
              method: 'card',
              types: ["credit"]
            },
            {
              method: 'card',
              types: ["debit"]
            }
          ],
        },
      },
    },
    },
      sequence: ['block.banks'],
      preferences: {
        show_default_blocks: false,
      },
    "handler": function (response){
        document.getElementById('rzp_paymentid').value = response.razorpay_payment_id;
        document.getElementById('rzp_orderid').value = response.razorpay_order_id;
        document.getElementById('rzp_signature').value = response.razorpay_signature;

        // // Let's submit the form automatically
        document.getElementById('rzp-paymentresponse').click();
    },
    "prefill": {
        "name": $('#rzp_name').val(),
        "email": $('#rzp_email').val(),
        "contact": $('#rzp_mobile').val()
    },
    "notes": {
        "username": $('#rzp_username').val(),
        "store_name": $('#rzp_store_name').val(),
    },
    "theme": {
        "color": "#F37254"
    }
};
var rzp1 = new Razorpay(options);
window.onload = function(){
    document.getElementById('pay_now').click();
};

document.getElementById('pay_now').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
