<script type="text/javascript">
  $(function() {
    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    var filelist = {};

    //Load Pertanyaan
    $.ajax({
      async: false,
      url: __HOSTAPI__ + "/Kuisioner/list",
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      type: "GET",
      success: function(response) {
        var data = response.response_package.response_data;

        var autonum = 1;

        for (var a in data) {
          var pStep = document.createElement("LI");
          $(pStep).html("<strong>" + autonum + "</strong>");
          $("#progressbar").append(pStep);

          var fContainer = document.createElement("FIELDSET");
          $("#field_container").append(fContainer);
          $.ajax({
            async: false,
            url: __HOSTNAME__ + "/pages/kuisioner/single_builder.php",
            data: {
              uid: data[a].uid,
              counter: autonum,
              max_counter: data.length,
              nama_domain: data[a].nama_domain,
              nama_aspek: data[a].nama_aspek,
              nama_indikator: data[a].nama_indikator,
              level_0: data[a].level_0,
              level_1: data[a].level_1,
              level_2: data[a].level_2,
              level_3: data[a].level_3,
              level_4: data[a].level_4,
              level_5: data[a].level_5,
              pertanyaan: data[a].pertanyaan,
              jawab: data[a].jawab,
              jawaban: (data[a].jawaban !== undefined) ? data[a].jawaban.response_data : [],
              penjelasan: data[a].penjelasan_indikator
            },
            type: "POST",
            success: function(response) {
              $(fContainer).html(response);
            }
          });
          autonum++;
        }

        $("#progressbar li:eq(0)").addClass("active");
        // #progressbar #account:before
      },
      error: function(response) {}
    });

    $("body").on("click", ".confirm", function() {
      alert();
    });

    $("body").on("click", ".btn-hint", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      $("body").append($("#form_petunjuk_" + id));
      $("#form_petunjuk_" + id).modal("show");
    });

    var file;
    var targettedUIDFile;

    $("body").on("click", ".btn-add-upload", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];
      targettedUIDFile = id;
      var dis = $(this).attr("disabled");
      if (typeof dis !== 'undefined' && dis !== false) {
        //
      } else {
        $("body").append($("#form_upload_" + id));
        $("#form_upload_" + id).modal("show");
      }
    });

    $("body").on("change", ".file_uploader", function(e) {
      file = e.target.files[0];
    });



    $("body").on("click", ".btnSubmitFile", function() {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];

      if (filelist[id] === undefined) {
        filelist[id] = {};
      }

      if (filelist[id][file.name] === undefined) {
        filelist[id][file.name] = {};
      }

      filelist[id][file.name] = file

      $("#file_" + targettedUIDFile).val("");

      populateFile(targettedUIDFile, filelist);
    });

    $("body").on("click", ".hapus_file", function(e) {
      var id = $(this).attr("id").split("_");
      id = id[id.length - 1];

      var targetFile = $(this).attr("target-file");

      delete filelist[id][targetFile];

      populateFile(id, filelist);
      return false;
    });

    function populateFile(uid, target) {
      $("#" + uid + "_file_list li").remove();
      for (var a in target) {
        for (var b in target[a]) {
          $("#" + uid + "_file_list").append("<li>" + b + " <a href=\"#\" target-file=\"" + b + "\" id=\"hapus_file_" + uid + "\" class=\"hapus_file\">hapus</a></li>");
        }
      }
    }

    var selectedChecked;
    var selectedPenjelasan = "";


    $("body").on("change", ".penjelasan_input", function() {
      selectedPenjelasan = $(this).val();
    });

    $("body").on("change", ".checker_level", function() {
      // var id = $(this).attr("id").split("_");
      // var target = id[id.length - 1];

      // if ($(this).is(":checked")) {
      //   for (var a = target; a <= 5; a++) {
      //     $("#" + id[0] + "_level_" + a).attr("disabled", "disabled");
      //   }
      // }

      if ($(this).is(":checked")) {
        selectedChecked = $(this).val();
      }
    });

    var form_data = new FormData();



    $(".next").click(function() {
      var id = $(this).attr("id").split("_");
      var id = id[id.length - 1];

      //Save dulu
      form_data.append("request", "kuisioner_jawaban");
      form_data.append("pertanyaan", id);
      form_data.append("level", selectedChecked);
      form_data.append("penjelasan", selectedPenjelasan);

      for (var a in filelist[id]) {
        console.log(filelist[id][a]);
        form_data.append("fileList[]", filelist[id][a]);
      }

      var THIS = $(this);

      $.ajax({
        async: false,
        processData: false,
        contentType: false,
        url: __HOSTAPI__ + "/Kuisioner",
        data: form_data,
        headers: {
          Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
        },
        type: "POST",
        success: function(response) {
          var resp = response.response_package;
          if (resp.response_result > 0) {
            current_fs = THIS.parent();
            next_fs = THIS.parent().next();

            //Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
              opacity: 0
            }, {
              step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                  'display': 'none',
                  'position': 'relative'
                });
                next_fs.css({
                  'opacity': opacity
                });
              },
              duration: 600
            });
          }
        },
        error: function(response) {
          //
        }
      });
    });

    $(".previous").click(function() {

      current_fs = $(this).parent();
      previous_fs = $(this).parent().prev();

      //Remove class active
      $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

      //show the previous fieldset
      previous_fs.show();

      //hide the current fieldset with style
      current_fs.animate({
        opacity: 0
      }, {
        step: function(now) {
          // for making fielset appear animation
          opacity = 1 - now;

          current_fs.css({
            'display': 'none',
            'position': 'relative'
          });
          previous_fs.css({
            'opacity': opacity
          });
        },
        duration: 600
      });
    });

    $('.radio-group .radio').click(function() {
      $(this).parent().find('.radio').removeClass('selected');
      $(this).addClass('selected');
    });

    $(".submit").click(function() {
      return false;
    })
  });
</script>