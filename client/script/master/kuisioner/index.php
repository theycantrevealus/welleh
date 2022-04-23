<script type="text/javascript">
  $(function() {
    var DT = $("#table-kuisioner").DataTable({
      processing: true,
      serverSide: true,
      sPaginationType: "full_numbers",
      bPaginate: true,
      lengthMenu: [
        [20, 50, -1],
        [20, 50, "All"]
      ],
      serverMethod: "POST",
      "ajax": {
        url: __HOSTAPI__ + "/Kuisioner",
        type: "POST",
        data: function(d) {
          d.request = "get_kuisioner_back_end";
        },
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        dataSrc: function(response) {
          var returnedData = [];
          if (response == undefined || response.response_package == undefined) {
            returnedData = [];
          } else {
            returnedData = response.response_package.response_data;
          }

          response.draw = parseInt(response.response_package.response_draw);
          response.recordsTotal = response.response_package.recordsTotal;
          response.recordsFiltered = response.response_package.recordsFiltered;

          return returnedData;
        }
      },
      autoWidth: false,
      language: {
        search: "",
        searchPlaceholder: "Cari Pertanyaan"
      },
      "columns": [{
        "data": null,
        render: function(data, type, row, meta) {
          return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return row.nama_domain;
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return row.nama_aspek;
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return row.nama_indikator;
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return row.pertanyaan;
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return row.nama_pegawai;
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
            "<a class=\"btn btn-info btn-sm btn-edit-domain\" href=\"" + __HOSTNAME__ + "/master/kuisioner/edit/" + row.uid + "\">" +
            "<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
            "</a>" +
            "<button id=\"kuisioner_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-kuisioner\">" +
            "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
            "</button>" +
            "</div>";
        }
      }]
    });

    $("body").on("click", ".btn-delete-kuisioner", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];
      Swal.fire({
        title: "Hapus Kuisioner?",
        showDenyButton: true,
        confirmButtonText: "Ya",
        denyButtonText: "Tidak",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Kuisioner",
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            data: {
              request: 'hapus_kuisioner',
              uid: uid
            },
            type: "DELETE",
            success: function(response) {
              var response = response.response_package;
              Swal.fire(
                "Master Kuisioner",
                response.response_message,
                (response.response_result > 0) ? "success" : "error"
              ).then((result) => {
                if (response.response_result > 0) {
                  DT.ajax.reload();
                }
              });
            },
            error: function(response) {},
          });
        }
      });
    });
  });
</script>