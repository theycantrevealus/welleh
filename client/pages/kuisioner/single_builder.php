<table class="table table-bordered largeDataType">
  <tr>
    <td class="bgdark">Domain</td>
    <td class="bgdark"><?php echo $_POST['nama_domain'] ?></td>
    <td class="bgdark">Aspek</td>
    <td class="bgdark"><?php echo $_POST['nama_aspek'] ?></td>
    <td class="bgdark" rowspan="2">Pilihan Saudara</td>
  </tr>
  <tr>
    <td class="bgdark">Indikator</td>
    <td class="bgdark"><?php echo $_POST['nama_indikator'] ?></td>
    <td class="bgdark" colspan="2"><?php echo $_POST['pertanyaan'] ?></td>
  </tr>
  <?php
  for ($a = 0; $a <= 5; $a++) {
  ?>
    <tr>
      <td>Level <?php echo $a; ?></td>
      <td colspan="3"><?php echo $_POST['level_' . $a]; ?></td>
      <td>
        <div style="display: flex !important;">
          <input <?php echo ($_POST['jawaban'][0]['level_' . $a] === 'Y') ? 'checked' : ''; ?> <?php echo ($_POST['jawab'] === 'Y') ? 'disabled' : ''; ?> style="margin: 0 10px;" value="<?php echo $a; ?>" class="checker_level" type="radio" name="<?php echo $_POST['uid'] . '_level'; ?>" />
        </div>
      </td>

      </td>
    </tr>
  <?php
  } ?>
  <tr>
    <td colspan="5">
      <div class="form-group">
        <label for="txt_nama">Penjelasan</label>
        <textarea <?php echo ($_POST['jawab'] === 'Y') ? 'disabled' : ''; ?> id="txt_penjelasan_<?php echo $_POST['uid']; ?>_penjelasan" style="max-width: 100%; min-height: 200px" placeholder="Isi Penjelasan..." class="form-control penjelasan_input"><?php echo $_POST['jawaban'][0]['penjelasan']; ?></textarea>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan="5">
      Data Pendukung:
      <span <?php echo ($_POST['jawab'] === 'Y') ? 'disabled' : ''; ?> class="btn <?php echo ($_POST['jawab'] === 'Y') ? 'btn-danger' : 'btn-info'; ?> btn-add-upload" id="upload_for_<?php echo $_POST['uid']; ?>">Upload Data Pendukung</span>
      <hr />
      <ol class="file_list" id="<?php echo $_POST['uid']; ?>_file_list">
        <?php
        $fileList = explode(',', $_POST['jawaban'][0]['data_dukung']);
        foreach ($fileList as $FK => $FV) {
          $parseFile = explode('/', $FV);
        ?>
          <li><?php echo $parseFile[count($parseFile) - 1]; ?></li>
        <?php
        }
        ?>
      </ol>
    </td>
  </tr>
</table>
<span class="btn btn-info btn-hint" id="hint_for_<?php echo $_POST['uid']; ?>">Petunjuk <i class="fa fa-question"></i></span>


<div id="form_upload_<?php echo $_POST['uid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Tambah Dokumen Pendukung</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="file" class="file_uploader" id="file_<?php echo $_POST['uid']; ?>" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btnSubmitFile" id="btnSubmitFile_<?php echo $_POST['uid']; ?>">Submit</button>
      </div>
    </div>
  </div>
</div>


<div id="form_petunjuk_<?php echo $_POST['uid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Petunjuk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="penjelasan"><?php echo $_POST['penjelasan']; ?></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<?php
if (intval($_POST['counter']) < intval($_POST['max_counter'])) {
?>
  <input type="button" id="next_<?php echo $_POST['uid']; ?>" name="next" class="next btn btn-info action-button" value="Next Step" />
<?php
} else if ($_POST['counter'] == $_POST['max_counter']) {
?>
  <!-- <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> -->
  <input type="button" class="next btn btn-success action-button confirm" value="Confirm" />
<?php
}
?>