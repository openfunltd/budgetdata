$(document).ready(function() {
  //歲出計畫提要及分支計畫概況表 
  var projectTable = $('#project-table').DataTable({
    pageLength: 50,
    columnDefs: [
      {
        targets: 0,
        orderable: false,
        className: 'details-control',
      },
      {
        targets: 6, //col:說明
        visible: false,
        searchable: false,
      },
      {
        targets: [4],
        className: 'text-end',
      },
      {
        targets: [5],
        className: 'text-center',
      },
    ],
    initComplete: function() {
      var api = this.api();
      api.rows().every(function() {
        var description = this.data()[6];
        var td = $(this.node()).find('td.details-control');
        if (description.trim() === '') {
          td.addClass('no-description');
        } else {
          td.html('<i class="bi bi-caret-right-fill"></i>');
        }
      });

      //hide sorting arrow on the first col for open/close
      $('#project-table thead th').eq(0).find('span.dt-column-order').addClass('d-none');
    }
  });

  $('#project-table tbody').on('click', 'td.details-control', function() {
    var td = $(this);
    //don't provide action when description data
    if (td.hasClass('no-description')) { return; }

    var tr = $(this).closest('tr');
    var row = projectTable.row(tr);

    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass('shown');
      td.html('<i class="bi bi-caret-right-fill"></i>');
    } else {
      var rowData = row.data();
      var description = rowData[6];

      row.child(description).show();
      tr.addClass('shown');
      td.html('<i class="bi bi-caret-down-fill"></i>');
    }
  });
});
