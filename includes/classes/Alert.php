<?php

class Alert{

             // allowEscapeKey: false,

    public static function success($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '$text',
                backdrop: false,

            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }

    public static function successAutoRedirect($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '$text',
            backdrop: false,
            confirmButtonText: 'Wait',
            showCancelButton: false,
            showCloseButton: false,
        });
            setTimeout(() => {
                window.location.href = '$redirectUrl';
            }, 1000);
        </script>";
    }


    public static function error($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oh no!',
                text: '$text'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }
    public static function remove($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Removal!',
                text: '$text'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }

    public static function confirm($text, $redirectUrl) {
        echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '$text',
                    showCancelButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '$redirectUrl';
                    }
                });
            </script>
        ";

    }
}
?>