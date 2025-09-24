// Call the dataTables jQuery plugin
$(document).ready(function() {
  const $tbl = $('#dataTable');
  if ($tbl.length) {
    $tbl.DataTable({
      responsive: true,
      autoWidth: false,
      pageLength: 10,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
      },
      columnDefs: [
        { targets: 'no-wrap', className: 'text-nowrap' }
      ]
    });
  }
});
