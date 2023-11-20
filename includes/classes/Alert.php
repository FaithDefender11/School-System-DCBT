<?php
 
class Alert{


    public static function success($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '$text',
                backdrop: false,
                allowEscapeKey: false,

            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }

    public static function successEnrollment($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '$text',
                backdrop: false,
                allowEscapeKey: false,

            }).then((result) => {
                if (result.isConfirmed) {
                     
                }
            });
        </script>";
    }

    public static function successFileUpload($text, $additionalText, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                 html: '$text <br> <span>$additionalText</span>', // Display additional text below main text
                backdrop: false,
                allowEscapeKey: false,

            });

            setTimeout(() => {
                window.location.href = '$redirectUrl';
            }, 2000);

        </script>";
    }


    public static function successAutoRedirect($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '$text',
            confirmButtonText: 'Redirecting',
            showCancelButton: false,
            showCloseButton: false,
            backdrop: false,
            allowEscapeKey: false,
        });
            setTimeout(() => {
                window.location.href = '$redirectUrl';
            }, 2100);
        </script>";
    }


    public static function error($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oh no!',
                text: '$text',
                backdrop: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }
    public static function errorNonRedirect($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oh no!',
                text: '$text',
                backdrop: false,
                allowEscapeKey: false
            }).then((result) => {
                
            });
        </script>";
    }
    public static function conflictedMessage($mainText, $additionalText, $redirectUrl) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oh no! Schedule Conflict',
                html: '<div><p>$mainText</p><p>$additionalText</p></div>',
                showCancelButton: false,
                backdrop: false,
                allowEscapeKey: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }

    public static function conflictedMessageNonRedirect($mainText, $additionalText, $redirectUrl) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oh no! Schedule Conflict',
                html: '<div><p>$mainText</p><p>$additionalText</p></div>',
                showCancelButton: false,
                backdrop: false,
                allowEscapeKey: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                
            });
        </script>";
    }


    // public static function errorToast($text, $redirectUrl) {
    //     echo "<script>
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Oh no!',
    //             text: '$text',
    //             toast: true, // Enable toast mode
    //             position: 'bottom-end', // Position the toast at the bottom-end
    //             showConfirmButton: false, // Remove the confirm button
    //             timer: 5000 // Auto-close the toast after 3 seconds (adjust as needed)
    //         }).then((result) => {
    //             if (result.dismiss === Swal.DismissReason.timer) {
    //                 // Redirect after the toast is closed
    //                 window.location.href = '$redirectUrl';
    //             }
    //         });
    //     </script>";
    // }

    public static function errorToast($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oh no!',
                text: '$text',
                toast: true, // Enable toast mode
                position: 'bottom-end', // Position the toast at the bottom-end
                showConfirmButton: false, // Remove the confirm button
                timer: 3000 // Auto-close the toast after 3 seconds (adjust as needed)
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    // Auto-closed by timer, perform redirection
                    window.location.href = '$redirectUrl';
                }
            });

            // Add click event to the toast
            document.querySelector('.swal-toast').addEventListener('click', () => {
                // Clicked on the toast, perform redirection
                window.location.href = '$redirectUrl';
            });
        </script>";
    }


    public static function errorNoRedirect($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oh no!',
                text: '$text'
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