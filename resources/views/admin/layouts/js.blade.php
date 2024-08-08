<!-- jQuery UI 1.11.4 -->
<script src="{{asset('assets/admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('assets/admin/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('assets/admin/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('assets/admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('assets/admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('assets/admin/dist/js/pages/dashboard.js')}}"></script>

<script>

$('.select2').select2({
    theme: 'bootstrap4'
})

let table = new DataTable('#data');

$("#logout").click(function(e) {
    Swal.fire({
        title: "Confirmation",
        text: "Are you sure want to logout?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then(function(result) {
        if (result.value) {
            window.location.href = "{{route('admin.logout')}}";
        } 
    });
});

function delData(url){
    Swal.fire({
        title: "Confirmation",
        text: "Are you sure want to delete this data?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                type: 'DELETE',
                url: url,
                data: {_token: '{{ csrf_token() }}'},
                dataType: 'json',
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: 'Data deleted successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }
    });
}
</script>