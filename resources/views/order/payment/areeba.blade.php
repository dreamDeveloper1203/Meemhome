<html>

<head>
    <script src="https://epayment.areeba.com/static/checkout/checkout.min.js" data-error="errorCallback"
        data-cancel="cancelCallback" data-complete="https://meemhome.com">
        </script>

    <script type="text/javascript">
        function errorCallback(error) {
            console.log(JSON.stringify(error));
        }
        function cancelCallback() {
            console.log('Payment cancelled');
        }

        // order: {
        //     id: '{{$order->id}}',
        //     currency: 'USD',
        //     amount: '{{ $order->total }}',
        // },
        Checkout.configure({
            session: {
                id: '{{$session_id}}'
            },
        })
        Checkout.showPaymentPage();;
    </script>
</head>

<body>

</body>

</html>
