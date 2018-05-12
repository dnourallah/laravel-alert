
@if(Session::has('alert.confirm') && Session::has('alert.cancel'))
    <script>
        pAlert({!! Session::pull('alert.config') !!}).then((result) => {
            if (result.value) {
                pAlert({!! Session::pull('alert.confirm') !!})
            } else if (
                // Read more about handling dismissals
                result.dismiss === pAlert.DismissReason.cancel
            ) {
                pAlert({!! Session::pull('alert.cancel') !!})
            }
        });
    </script>
@elseif(Session::has('alert.config'))
    <script>
       pAlert({!! Session::pull('alert.config') !!});
    </script>
@endif
