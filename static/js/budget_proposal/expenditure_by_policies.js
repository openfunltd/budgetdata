$(document).ready(function() {
  //歲出政事別預算表
  var expenditureByPoliciesTable = $('#expenditure-by-policies-table').DataTable({
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
      $('#expenditure-by-policies-table thead th').eq(0).find('span.dt-column-order').addClass('d-none');
    }
  });

  $('#expenditure-by-policies-table tbody').on('click', 'td.details-control', function() {
    var td = $(this);
    //don't provide action when description data
    if (td.hasClass('no-description')) { return; }

    var tr = $(this).closest('tr');
    var row = expenditureByPoliciesTable.row(tr);

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
