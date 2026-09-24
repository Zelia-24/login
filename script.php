<!-- AdminLTE App -->
<script src="../asetweb/dist/js/adminlte.min.js"></script>
<script src="../asetweb/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../asetweb/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="../asetweb/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="../asetweb/plugins/chart.js/Chart.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="../asetweb/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../asetweb/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../asetweb/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../asetweb/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../asetweb/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../asetweb/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../asetweb/plugins/jszip/jszip.min.js"></script>
<script src="../asetweb/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../asetweb/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../asetweb/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../asetweb/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../asetweb/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

<!-- SweetAlert2 -->
<script src="../asetweb/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="../asetweb/plugins/toastr/toastr.min.js"></script>


