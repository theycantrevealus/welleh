<script src="<?php echo __HOSTNAME__; ?>/plugins/ckeditor5-build-classic/ckeditor.js"></script>
<script type="text/javascript">
  $(function() {
    var editorPertanyaan, editorPenjelasan;

    console.log(__PAGES__);

    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Kuisioner/kuisioner_detail/" + __PAGES__[3],
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      type: "GET",
      success: function(response) {
        var data = response.response_package.response_data[0];

        $("#txt_domain").append("<option title=\"" + data.nama_domain + "\" value=\"" + data.domain + "\">" + data.nama_domain + "</option>");
        $("#txt_domain").trigger("change");

        $("#txt_aspek").append("<option title=\"" + data.nama_aspek + "\" value=\"" + data.aspek + "\">" + data.nama_aspek + "</option>");
        $("#txt_aspek").trigger("change");

        $("#txt_indikator").append("<option title=\"" + data.nama_indikator + "\" value=\"" + data.indikator + "\">" + data.nama_indikator + "</option>");
        $("#txt_indikator").trigger("change");

        for (var a = 0; a <= 5; a++) {
          $("#txt_level_" + a).val(data["level_" + a]);
        }

        ClassicEditor
          .create(document.querySelector('#txt_pertanyaan'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
            placeholder: "Isi Pertanyaan"
          })
          .then(editor => {
            editor.setData(data.pertanyaan)
            editorPertanyaan = editor;
            window.editor = editor;
          })
          .catch(err => {
            //console.error( err.stack );
          });

        ClassicEditor
          .create(document.querySelector('#txt_penjelasan'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
            placeholder: "Penjelasan"
          })
          .then(editor => {
            editor.setData(data.penjelasan_indikator)
            editorPenjelasan = editor;
            window.editor = editor;
          })
          .catch(err => {
            //console.error( err.stack );
          });
      },
      error: function(response) {}
    });

    class MyUploadAdapter {
      static loader;
      constructor(loader) {
        // CKEditor 5's FileLoader instance.
        this.loader = loader;

        // URL where to send files.
        this.url = __HOSTAPI__ + "/Upload";

        this.imageList = [];
      }

      // Starts the upload process.
      upload() {
        return new Promise((resolve, reject) => {
          this._initRequest();
          this._initListeners(resolve, reject);
          this._sendRequest();
        });
      }

      // Aborts the upload process.
      abort() {
        if (this.xhr) {
          this.xhr.abort();
        }
      }

      // Example implementation using XMLHttpRequest.
      _initRequest() {
        const xhr = this.xhr = new XMLHttpRequest();

        xhr.open('POST', this.url, true);
        xhr.setRequestHeader("Authorization", 'Bearer ' + <?php echo json_encode($_SESSION["token"]); ?>);
        xhr.responseType = 'json';
      }

      // Initializes XMLHttpRequest listeners.
      _initListeners(resolve, reject) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = 'Couldn\'t upload file:' + ` ${ loader.file.name }.`;

        xhr.addEventListener('error', () => reject(genericErrorText));
        xhr.addEventListener('abort', () => reject());
        xhr.addEventListener('load', () => {
          const response = xhr.response;

          if (!response || response.error) {
            return reject(response && response.error ? response.error.message : genericErrorText);
          }

          // If the upload is successful, resolve the upload promise with an object containing
          // at least the "default" URL, pointing to the image on the server.
          resolve({
            default: response.url
          });
        });

        if (xhr.upload) {
          xhr.upload.addEventListener('progress', evt => {
            if (evt.lengthComputable) {
              loader.uploadTotal = evt.total;
              loader.uploaded = evt.loaded;
            }
          });
        }
      }


      // Prepares the data and sends the request.
      _sendRequest() {
        const toBase64 = file => new Promise((resolve, reject) => {
          const reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = () => resolve(reader.result);
          reader.onerror = error => reject(error);
        });
        var Axhr = this.xhr;

        async function doSomething(fileTarget) {
          fileTarget.then(function(result) {
            var ImageName = result.name;

            toBase64(result).then(function(renderRes) {
              const data = new FormData();
              data.append('upload', renderRes);
              data.append('name', ImageName);
              Axhr.send(data);
            });
          });
        }

        var ImageList = this.imageList;

        this.loader.file.then(function(toAddImage) {

          ImageList.push(toAddImage.name);

        });

        this.imageList = ImageList;

        doSomething(this.loader.file);
      }
    }


    function MyCustomUploadAdapterPlugin(editor) {
      editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        var MyCust = new MyUploadAdapter(loader);
        var dataToPush = MyCust.imageList;
        hiJackImage(dataToPush);
        return MyCust;
      };
    }

    var imageResultPopulator = [];

    function hiJackImage(toHi) {
      imageResultPopulator.push(toHi);
    }

    $("#txt_domain").select2({
      minimumInputLength: 1,
      "language": {
        "noResults": function() {
          return "Domain tidak ditemukan";
        }
      },
      placeholder: "Cari Domain",
      cache: true,
      dropdownParent: $("body"),
      selectOnClose: true,
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Kuisioner/get_select2_domain",
        type: "GET",
        data: function(term) {
          return {
            search: term.term
          };
        },
        processResults: function(response) {
          var data = response.response_package.response_data;
          return {
            results: $.map(data, function(item) {
              return {
                text: item.nama_domain,
                id: item.uid
              }
            })
          };
        }
      }
    }).on("select2:select", function(e) {
      var data = e.params.data;

    }).on('results:message', function(params) {
      this.dropdown._resizeDropdown();
      this.dropdown._positionDropdown();
    });

    $("#txt_aspek").select2({
      minimumInputLength: 1,
      "language": {
        "noResults": function() {
          return "Aspek tidak ditemukan";
        }
      },
      placeholder: "Cari Aspek",
      cache: true,
      dropdownParent: $("body"),
      selectOnClose: true,
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Kuisioner/get_select2_aspek",
        type: "GET",
        data: function(term) {
          return {
            search: term.term
          };
        },
        processResults: function(response) {
          var data = response.response_package.response_data;
          return {
            results: $.map(data, function(item) {
              return {
                text: item.nama_aspek,
                id: item.uid
              }
            })
          };
        }
      }
    }).on("select2:select", function(e) {
      var data = e.params.data;

    }).on('results:message', function(params) {
      this.dropdown._resizeDropdown();
      this.dropdown._positionDropdown();
    });

    $("#txt_indikator").select2({
      minimumInputLength: 1,
      "language": {
        "noResults": function() {
          return "Indikator tidak ditemukan";
        }
      },
      placeholder: "Cari Indikator",
      cache: true,
      dropdownParent: $("body"),
      selectOnClose: true,
      ajax: {
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
          "Content-Type": "application/json",
        },
        url: __HOSTAPI__ + "/Kuisioner/get_select2_indikator",
        type: "GET",
        data: function(term) {
          return {
            search: term.term
          };
        },
        processResults: function(response) {
          var data = response.response_package.response_data;
          return {
            results: $.map(data, function(item) {
              return {
                text: item.nama_indikator,
                id: item.uid
              }
            })
          };
        }
      }
    }).on("select2:select", function(e) {
      var data = e.params.data;

    }).on('results:message', function(params) {
      this.dropdown._resizeDropdown();
      this.dropdown._positionDropdown();
    });

    $("#btnSubmit").click(function() {
      var domain = $("#txt_domain").val();
      var aspek = $("#txt_aspek").val();
      var indikator = $("#txt_indikator").val();
      var pertanyaan = editorPertanyaan.getData();
      var level0 = $("#txt_level_0").val();
      var level1 = $("#txt_level_1").val();
      var level2 = $("#txt_level_2").val();
      var level3 = $("#txt_level_3").val();
      var level4 = $("#txt_level_4").val();
      var level5 = $("#txt_level_5").val();
      var penjelasan = editorPenjelasan.getData();

      if (
        domain !== null &&
        domain !== undefined &&

        aspek !== null &&
        aspek !== undefined &&

        indikator !== null &&
        indikator !== undefined &&

        pertanyaan !== null &&
        pertanyaan !== undefined &&

        level0 !== null &&
        level0 !== undefined &&

        level1 !== null &&
        level1 !== undefined &&

        level2 !== null &&
        level2 !== undefined &&

        level3 !== null &&
        level3 !== undefined &&

        level4 !== null &&
        level4 !== undefined &&

        level5 !== null &&
        level5 !== undefined &&

        penjelasan !== null &&
        penjelasan !== undefined
      ) {
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
                uid: __PAGES__[3],
                request: 'edit_kuisioner',
                domain: domain,
                aspek: aspek,
                indikator: indikator,
                pertanyaan: pertanyaan,
                level0: level0,
                level1: level1,
                level2: level2,
                level3: level3,
                level4: level4,
                level5: level5,
                penjelasan: penjelasan
              },
              type: "POST",
              success: function(response) {
                console.log(response)
                var response = response.response_package;
                Swal.fire(
                  "Master Kuisioner",
                  response.response_message,
                  (response.response_result > 0) ? "success" : "error"
                ).then((result) => {
                  if (response.response_result > 0) {
                    location.href = __HOSTNAME__ + "/master/kuisioner";
                  }
                });
              },
              error: function(response) {}
            });
          }
        });
      } else {
        console.log(domain);
        console.log(aspek);
        console.log(indikator);
        console.log(pertanyaan);
        console.log(level0);
        console.log(level1);
        console.log(level2);
        console.log(level3);
        console.log(level4);
        console.log(level5);
        console.log(penjelasan);
      }

    });
  });
</script>