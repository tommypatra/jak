<footer class="footer">
    <div class="container">
        &copy; 2024 Admin Dashboard
    </div>
</footer>


<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ asset('images/loading.gif') }}" height="150px" alt="Loading..." />
                <p>Sabar... lagi proses <span class='proses-berjalan'></span></p>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> --}}

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('plugins/toastr/build/toastr.min.js') }}"></script>

<script>
    //untuk logout dari token API Sanctum backend server
    // function logoutApi() {
    //     $.post('/api/logout', function(response) {
    //         localStorage.removeItem('access_token');
    //         window.location.replace("{{ route('login') }}");
    //     }).fail(function(xhr) {
    //         var errorMessage = "An error occurred while logging out from API: ";
    //         if (xhr.responseJSON && xhr.responseJSON.message) {
    //             errorMessage += xhr.responseJSON.message;
    //         } else {
    //             errorMessage += "Unknown error.";
    //         }
    //         console.log(errorMessage);
    //         window.location.replace("{{ route('login') }}");
    //     });
    // }

    //untuk logout dari session web
    // function logoutWeb() {
    //     var csrfToken = $('meta[name="csrf-token"]').attr('content');
    //     $.ajax({
    //         url: base_url + '/web-logout',
    //         type: 'POST',
    //         dataType: 'json',
    //         headers: {
    //             'X-CSRF-TOKEN': csrfToken
    //         },
    //         success: function(response) {
    //             logoutApi();
    //         },
    //         error: function(xhr) {
    //             var errorMessage = "An error occurred while logging out from web session: ";
    //             if (xhr.responseJSON && xhr.responseJSON.message) {
    //                 errorMessage += xhr.responseJSON.message;
    //             } else {
    //                 errorMessage += "Unknown error.";
    //             }
    //             alert(errorMessage);
    //         }
    //     });
    // }
</script>