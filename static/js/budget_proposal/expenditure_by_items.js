$(document).ready(function() {
  //各項費用彙計表
  var expenditureByItemsTable = $('#expenditure-by-items-table').DataTable({
    pageLength: 100,
    order: [],
    columnDefs: [
      {
        targets: '_all',
        className: 'text-end',
      },
      {
        targets: [0, 1, 2, 3],
        className: 'text-center',
      },
    ],
  });
});
