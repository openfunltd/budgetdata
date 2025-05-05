$(document).ready(function() {
  //歲入來源別預算表
  var incomeBySourcesTable = $('#income-by-sources-table').DataTable({
    pageLength: 50,
    columnDefs: [
      {
        targets: 0,
        orderable: false,
        className: 'details-control',
      },
      {
        targets: 11, //col:說明
        visible: false,
        searchable: false,
      },
      {
        targets: [1, 2, 3, 4, 5, 7, 8, 9, 10],
        className: 'text-center',
      },
    ],
    initComplete: function() {
      var api = this.api();
      api.rows().every(function() {
        var description = this.data()[11];
        var td = $(this.node()).find('td.details-control');
        if (description.trim() === '') {
          td.addClass('no-description');
        } else {
          td.html('<i class="bi bi-caret-right-fill"></i>');
        }
      });

      //hide sorting arrow on the first col for open/close
      $('#income-by-sources-table thead th').eq(0).find('span.dt-column-order').addClass('d-none');
    }
  });

  $('#income-by-sources-table tbody').on('click', 'td.details-control', function() {
    var td = $(this);
    //don't provide action when description data
    if (td.hasClass('no-description')) { return; }

    var tr = $(this).closest('tr');
    var row = incomeBySourcesTable.row(tr);

    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass('shown');
      td.html('<i class="bi bi-caret-right-fill"></i>');
    } else {
      var rowData = row.data();
      var description = rowData[11];

      row.child(description).show();
      tr.addClass('shown');
      td.html('<i class="bi bi-caret-down-fill"></i>');
    }
  });
});
