 <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
 <script src="{{ asset('admin-assets/assets/js/libs/jquery-3.1.1.min.js')}}"></script>
 <script src="{{ asset('admin-assets/bootstrap/js/popper.min.js')}}"></script>
 <script src="{{ asset('admin-assets/bootstrap/js/bootstrap.min.js')}}"></script>
 <script src="{{ asset('admin-assets/assets/js/app.js')}}"></script>
 <script src="{{ asset('admin-assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

 <!-- <script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script> -->
 <script src="https://media-library.cloudinary.com/global/all.js"></script>
 <script>
var rootUrl = "{{url('/')}}" + '/';
var baseUrl = rootUrl + 'admin/';
// var apiUrl = "https://ryserved-api.vercel.app/api/";
var apiUrl = "http://localhost:3000/api/";
// var apiUrl = "https://api.ryserved.com/api/";
var clName = "diyc1dizi";
var clPreset = "467722864351132";
$(document).ready(function() {
    App.init();
});
 </script>
 <script src="{{ asset('admin-assets/assets/js/custom.js')}}"></script>
 <script src="{{ asset('js/order.js') }}"></script>
 <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
 <script src="{{ asset('admin-assets/assets/js/scrollspyNav.js')}}"></script>
 <script>
checkall('todoAll', 'todochkbox');
$('[data-toggle="tooltip"]').tooltip()
 </script>
 <script src="{{ asset('admin-assets/plugins/apex/apexcharts.min.js')}}"></script>
 <script src="{{ asset('admin-assets/assets/js/dashboard/dash_1.js')}}"></script>
