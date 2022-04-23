<script type="text/javascript">
  $(function() {
    var mode = 'ADD';
    var selectedUID = '';
    var DT = $("#table-domain").DataTable({
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
          d.request = "get_domain_back_end";
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
        searchPlaceholder: "Cari Domain"
      },
      "columns": [{
        "data": null,
        render: function(data, type, row, meta) {
          return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return "<b>" + row.nama + "</b>";
        }
      }, {
        "data": null,
        render: function(data, type, row, meta) {
          return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
            "<button class=\"btn btn-info btn-sm btn-edit-domain\" id=\"domain_edit_" + row.uid + "\">" +
            "<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
            "</button>" +
            "<button id=\"domain_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-domain\">" +
            "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
            "</button>" +
            "</div>";
        }
      }]
    });

    $("body").on("click", ".btn-edit-domain", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];
      selectedUID = uid;
      mode = 'EDIT'
      $.ajax({
        async: false,
        url: __HOSTAPI__ + "/Kuisioner/domain_detail/" + uid,
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          var data = response.response_package.response_data[0];
          console.log(data);
          $("#txt_nama").val(data.nama);
          $("#form-set").modal("show");
        },
        error: function(response) {}
      });
    });

    $("body").on("click", ".btn-delete-domain", function() {
      var uid = $(this).attr("id").split("_");
      uid = uid[uid.length - 1];

      Swal.fire({
        title: "Hapus Domain?",
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
              request: 'hapus_domain',
              uid: uid
            },
            type: "DELETE",
            success: function(response) {
              var response = response.response_package;
              console.log(response);
              Swal.fire(
                "Master Domain",
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




    $("#btnTambah").click(function() {
      mode = 'ADD'
      $("#form-set").modal("show");
    });

    $("#btnSubmit").click(function() {
      var nama = $("#txt_nama").val();
      if (nama !== "") {
        Swal.fire({
          title: "Data sudah benar?",
          showDenyButton: true,
          confirmButtonText: "Sudah",
          denyButtonText: "Belum",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              async: false,
              url: __HOSTAPI__ + "/Kuisioner",
              beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
              },
              data: {
                uid: selectedUID,
                request: (mode === 'ADD') ? 'tambah_domain' : 'edit_domain',
                nama: nama
              },
              type: "POST",
              success: function(response) {
                var response = response.response_package;
                Swal.fire(
                  "Master Domain",
                  response.response_message,
                  (response.response_result > 0) ? "success" : "error"
                ).then((result) => {
                  if (response.response_result > 0) {
                    DT.ajax.reload();
                    $("#txt_nama").val();
                    $("#form-set").modal("hide");
                  }
                });
              },
              error: function(response) {}
            });
          }
        });
      } else {
        Swal.fire(
          "Master Domain",
          "Nama domain tidak boleh kosong",
          "error"
        ).then((result) => {
          //
        });
      }
    });
  });
</script>

<div id="form-set" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Tambah Domain</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group col-md-12">
          <label for="txt_no_skp">Nama Domain :</label>
          <input type="text" class="form-control" id="txt_nama" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
      </div>
    </div>
  </div>
</div>