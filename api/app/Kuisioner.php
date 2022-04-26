<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Kuisioner extends Utility
{
  static $pdo;
  static $query;

  protected static function getConn()
  {
    return self::$pdo;
  }

  public function __construct($connection)
  {
    self::$pdo = $connection;
    self::$query = new Query(self::$pdo);
  }

  public function __DELETE__($parameter = array())
  {
    switch ($parameter['request']) {
      case 'hapus_domain':
        return self::hapus_domain($parameter);
        break;

      case 'hapus_aspek':
        return self::hapus_aspek($parameter);
        break;

      case 'hapus_indikator':
        return self::hapus_indikator($parameter);
        break;

      case 'hapus_kuisioner':
        return self::hapus_kuisioner($parameter);
        break;
    }
  }

  public function __POST__($parameter = array())
  {
    switch ($parameter['request']) {
      case 'get_kuisioner_back_end':
        return self::get_kuisioner_back_end($parameter);
        break;
      case 'get_domain_back_end':
        return self::get_domain_back_end($parameter);
        break;
      case 'tambah_domain':
        return self::tambah_domain($parameter);
        break;
      case 'edit_domain':
        return self::edit_domain($parameter);
      case 'tambah_kuisioner':
        return self::tambah_kuisioner($parameter);
        break;
      case 'edit_kuisioner':
        return self::edit_kuisioner($parameter);
        break;


      case 'get_aspek_back_end':
        return self::get_aspek_back_end($parameter);
        break;
      case 'tambah_aspek':
        return self::tambah_aspek($parameter);
        break;
      case 'edit_aspek':
        return self::edit_aspek($parameter);
        break;

      case 'get_indikator_back_end':
        return self::get_indikator_back_end($parameter);
        break;
      case 'tambah_indikator':
        return self::tambah_indikator($parameter);
        break;
      case 'edit_indikator':
        return self::edit_indikator($parameter);
        break;
      case 'kuisioner_jawaban':
        return self::kuisioner_jawaban($parameter);
        break;
      default:
        return array();
    }
  }

  public function __GET__($parameter = array())
  {
    switch ($parameter[1]) {
      case 'domain_detail':
        return self::domain_detail($parameter);
        break;
      case 'get_select2_domain':
        return self::get_select2_domain($parameter);
        break;
      case 'kuisioner_detail':
        return self::kuisioner_detail($parameter);
        break;
      case 'aspek_detail':
        return self::aspek_detail($parameter);
        break;
      case 'get_select2_aspek':
        return self::get_select2_aspek($parameter);
        break;
      case 'indikator_detail':
        return self::indikator_detail($parameter);
        break;
      case 'get_select2_indikator':
        return self::get_select2_indikator($parameter);
        break;
      case 'list':
        return self::kuisioner_list($parameter);
        break;
    }
  }

  private function get_select2_domain($parameter)
  {
    $data = self::$query->select(
      'master_kuisioner_domain',
      array(
        'uid',
        'nama AS nama_domain'
      )
    )
      ->where(
        array(
          'master_kuisioner_domain.nama' => 'LIKE ' . '\'%' . $_GET['search'] . '%\'',
          'AND',
          'master_kuisioner_domain.deleted_at' => 'IS NULL'
        ),
        array()
      )
      ->limit(10)
      ->execute();

    return $data;
  }

  private function get_select2_aspek($parameter)
  {
    $data = self::$query->select(
      'master_kuisioner_aspek',
      array(
        'uid',
        'nama AS nama_aspek'
      )
    )
      ->where(
        array(
          'master_kuisioner_aspek.nama' => 'LIKE ' . '\'%' . $_GET['search'] . '%\'',
          'AND',
          'master_kuisioner_aspek.deleted_at' => 'IS NULL'
        ),
        array()
      )
      ->limit(10)
      ->execute();

    return $data;
  }

  private function get_select2_indikator($parameter)
  {
    $data = self::$query->select(
      'master_kuisioner_indikator',
      array(
        'uid',
        'nama AS nama_indikator'
      )
    )
      ->where(
        array(
          'master_kuisioner_indikator.nama' => 'LIKE ' . '\'%' . $_GET['search'] . '%\'',
          'AND',
          'master_kuisioner_indikator.deleted_at' => 'IS NULL'
        ),
        array()
      )
      ->limit(10)
      ->execute();

    return $data;
  }

  private function domain_detail($parameter)
  {
    $data = self::$query->select('master_kuisioner_domain', array(
      'uid', 'nama'
    ))
      ->where(array(
        'uid' => '= ?'
      ), array(
        $parameter[2]
      ))->execute();
    return $data;
  }

  private function kuisioner_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $data = self::$query->select('kuisioner_pertanyaan', array(
      'uid',
      'domain',
      'aspek',
      'indikator',
      'pertanyaan',
      'level_0',
      'level_1',
      'level_2',
      'level_3',
      'level_4',
      'level_5',
      'penjelasan_indikator',
      'user_uid',
      'created_at',
      'updated_at'
    ))
      ->where(array(
        'kuisioner_pertanyaan.deleted_at' => 'IS NULL'
      ), array())
      ->join('master_kuisioner_domain', array(
        'nama as nama_domain'
      ))
      ->join('master_kuisioner_aspek', array(
        'nama as nama_aspek'
      ))
      ->join('master_kuisioner_indikator', array(
        'nama as nama_indikator'
      ))
      ->on(array(
        array('kuisioner_pertanyaan.domain', '=', 'master_kuisioner_domain.uid'),
        array('kuisioner_pertanyaan.aspek', '=', 'master_kuisioner_aspek.uid'),
        array('kuisioner_pertanyaan.indikator', '=', 'master_kuisioner_indikator.uid')
      ))
      ->execute();

    foreach ($data['response_data'] as $key => $value) {
      //Check
      $check = self::$query->select('kuisioner_jawaban', array(
        '*'
      ))
        ->where(array(
          'kuisioner_jawaban.pertanyaan' => '= ?',
          'AND',
          'kuisioner_jawaban.user_uid' => '= ?'
        ), array(
          $value['uid'], $UserData['data']->uid
        ))
        ->execute();

      if (count($check['response_data']) > 0) {
        $data['response_data'][$key]['jawab'] = 'Y';
        $data['response_data'][$key]['jawaban'] = $check;
      }
    }

    return $data;
  }

  private function kuisioner_detail($parameter)
  {
    $data = self::$query->select('kuisioner_pertanyaan', array(
      'uid',
      'domain',
      'aspek',
      'indikator',
      'pertanyaan',
      'level_0',
      'level_1',
      'level_2',
      'level_3',
      'level_4',
      'level_5',
      'penjelasan_indikator',
      'user_uid',
      'created_at',
      'updated_at'
    ))
      ->join('pegawai', array(
        'nama as nama_pegawai'
      ))
      ->join('master_kuisioner_domain', array(
        'nama as nama_domain'
      ))
      ->join('master_kuisioner_aspek', array(
        'nama as nama_aspek'
      ))
      ->join('master_kuisioner_indikator', array(
        'nama as nama_indikator'
      ))
      ->on(array(
        array('kuisioner_pertanyaan.user_uid', '=', 'pegawai.uid'),
        array('kuisioner_pertanyaan.domain', '=', 'master_kuisioner_domain.uid'),
        array('kuisioner_pertanyaan.aspek', '=', 'master_kuisioner_aspek.uid'),
        array('kuisioner_pertanyaan.indikator', '=', 'master_kuisioner_indikator.uid')
      ))
      ->order(array(
        'updated_at' => 'DESC'
      ))
      ->where(array(
        'kuisioner_pertanyaan.uid' => '= ?'
      ), array(
        $parameter[2]
      ))
      ->execute();
    return $data;
  }

  private function aspek_detail($parameter)
  {
    $data = self::$query->select('master_kuisioner_aspek', array(
      'uid', 'nama as nama_aspek'
    ))
      ->where(array(
        'master_kuisioner_aspek.uid' => '= ?'
      ), array(
        $parameter[2]
      ))->execute();
    return $data;
  }

  private function indikator_detail($parameter)
  {
    $data = self::$query->select('master_kuisioner_indikator', array(
      'uid', 'nama'
    ))
      ->where(array(
        'master_kuisioner_indikator.uid' => '= ?'
      ), array(
        $parameter[2]
      ))->execute();
    return $data;
  }

  private function hapus_domain($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $proc = self::$query->delete('master_kuisioner_domain')
      ->where(array(
        'master_kuisioner_domain.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kuisioner_domain',
          'D',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function hapus_aspek($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $proc = self::$query->delete('master_kuisioner_aspek')
      ->where(array(
        'master_kuisioner_aspek.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kuisioner_aspek',
          'D',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function hapus_kuisioner($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $proc = self::$query->delete('kuisioner_pertanyaan')
      ->where(array(
        'kuisioner_pertanyaan.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'kuisioner_pertanyaan',
          'D',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function hapus_indikator($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $proc = self::$query->delete('master_kuisioner_indikator')
      ->where(array(
        'master_kuisioner_indikator.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kuisioner_indikator',
          'D',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function tambah_kuisioner($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $proc = self::$query->insert('kuisioner_pertanyaan', array(
      'uid' => $uid,
      'domain' => $parameter['domain'],
      'aspek' => $parameter['aspek'],
      'indikator' => $parameter['indikator'],
      'pertanyaan' => $parameter['pertanyaan'],
      'level_0' => $parameter['level0'],
      'level_1' => $parameter['level1'],
      'level_2' => $parameter['level2'],
      'level_3' => $parameter['level3'],
      'level_4' => $parameter['level4'],
      'level_5' => $parameter['level5'],
      'user_uid' => $UserData['data']->uid,
      'penjelasan_indikator' => $parameter['penjelasan'],
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->execute();

    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $uid,
          $UserData['data']->uid,
          'kuisioner_pertanyaan',
          'I',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function edit_kuisioner($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $old = self::kuisioner_detail($parameter['uid']);
    $proc = self::$query->update('kuisioner_pertanyaan', array(
      'domain' => $parameter['domain'],
      'aspek' => $parameter['aspek'],
      'indikator' => $parameter['indikator'],
      'pertanyaan' => $parameter['pertanyaan'],
      'level_0' => $parameter['level0'],
      'level_1' => $parameter['level1'],
      'level_2' => $parameter['level2'],
      'level_3' => $parameter['level3'],
      'level_4' => $parameter['level4'],
      'level_5' => $parameter['level5'],
      'user_uid' => $UserData['data']->uid,
      'penjelasan_indikator' => $parameter['penjelasan'],
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'kuisioner_pertanyaan.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();

    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'old_value',
          'new_value',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'kuisioner_pertanyaan',
          'U',
          json_encode($old['response_data'][0]),
          json_encode($parameter),
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function edit_domain($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $old = self::domain_detail($parameter['uid']);
    $proc = self::$query->update('master_kuisioner_domain', array(
      'nama' => $parameter['nama'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'master_kuisioner_domain.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();

    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'old_value',
          'new_value',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kuisioner_domain',
          'U',
          json_encode($old['response_data'][0]),
          json_encode($parameter),
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function kuisioner_jawaban($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $successUpload = array();

    //Check Apakah sudah jawab
    $check = self::$query->select('kuisioner_jawaban', array(
      'id'
    ))
      ->where(array(
        'kuisioner_jawaban.pertanyaan' => '= ?',
        'AND',
        'kuisioner_jawaban.user_uid' => '= ?'
      ), array(
        $parameter['pertanyaan'], $UserData['data']->uid
      ))
      ->execute();

    if (count($check['response_data']) === 0) {
      $folder_structure = '../document/data_pendukung/' . $UserData['data']->uid . '/' . $parameter['pertanyaan'];
      if (!is_dir($folder_structure)) {
        if (!mkdir($folder_structure, 0777, true)) {
          $result['dir_msg'] = 'Failed to create folders...';
        }
        //mkdir('../document/laboratorium/' . $parameter['uid_radiologi_order'], 0755);
      } else {
        $result['dir_msg'] = 'Dir available...';
      }

      if (is_writeable($folder_structure)) {
        for ($a = 0; $a < count($_FILES['fileList']); $a++) {
          if (!empty($_FILES['fileList']['tmp_name'][$a])) {
            $nama_lampiran = $_FILES['fileList']['name'][$a];

            if (
              move_uploaded_file($_FILES['fileList']['tmp_name'][$a], '../document/data_pendukung/' . $UserData['data']->uid . '/' . $parameter['pertanyaan'] . '/' . $nama_lampiran)
            ) {
              array_push($successUpload, '/document/data_pendukung/' . $UserData['data']->uid . '/' . $parameter['pertanyaan'] . '/' . $nama_lampiran);
            }
          }
        }
      }

      $levelChecker = array(
        'level_0' => 'N',
        'level_1' => 'N',
        'level_2' => 'N',
        'level_3' => 'N',
        'level_4' => 'N',
        'level_5' => 'N'
      );

      foreach ($levelChecker as $key => $value) {
        $split = explode('_', $key);
        if (intval($split[1]) === intval($parameter['level'])) {
          $levelChecker[$key] = 'Y';
        } else {
          $levelChecker[$key] = 'N';
        }
      }

      //Jawab Pertanyaan
      $proc = self::$query->insert('kuisioner_jawaban', array(
        'pertanyaan' => $parameter['pertanyaan'],
        'user_uid' => $UserData['data']->uid,
        'level_0' => $levelChecker['level_0'],
        'level_1' => $levelChecker['level_1'],
        'level_2' => $levelChecker['level_2'],
        'level_3' => $levelChecker['level_3'],
        'level_4' => $levelChecker['level_4'],
        'level_5' => $levelChecker['level_5'],
        'penjelasan' => $parameter['penjelasan'],
        'data_dukung' => implode(',', $successUpload),
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->returning('id')
        ->execute();

      if ($proc['response_result'] > 0) {
        $log = parent::log(array(
          'type' => 'activity',
          'column' => array(
            'unique_target',
            'user_uid',
            'table_name',
            'action',
            'logged_at',
            'status',
            'login_id'
          ),
          'value' => array(
            $proc['response_unique'],
            $UserData['data']->uid,
            'kuisioner_jawaban',
            'I',
            parent::format_date(),
            'N',
            $UserData['data']->log_id
          ),
          'class' => __CLASS__
        ));
      }
      return $proc;
    } else {
      //Sudah Dijawab, tidak bisa update
      $result['response_result'] = 1;
      $result['response_message'] = 'Sudah pernah dijawab';
    }

    return $result;
  }

  private function edit_indikator($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $old = self::indikator_detail($parameter['uid']);
    $proc = self::$query->update('master_kuisioner_indikator', array(
      'nama' => $parameter['nama'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'master_kuisioner_indikator.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();

    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'old_value',
          'new_value',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kuisioner_indikator',
          'U',
          json_encode($old['response_data'][0]),
          json_encode($parameter),
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function edit_aspek($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $old = self::aspek_detail($parameter['uid']);
    $proc = self::$query->update('master_kuisioner_aspek', array(
      'nama' => $parameter['nama'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'master_kuisioner_aspek.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();

    if ($proc['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'old_value',
          'new_value',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kuisioner_aspek',
          'U',
          json_encode($old['response_data'][0]),
          json_encode($parameter),
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $proc;
  }

  private function tambah_domain($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['nama']) && !empty($parameter['nama'])) {
      $uid = parent::gen_uuid();
      $proc = self::$query->insert('master_kuisioner_domain', array(
        'uid' => $uid,
        'nama' => $parameter['nama'],
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
      if ($proc['response_result'] > 0) {
        $log = parent::log(array(
          'type' => 'activity',
          'column' => array(
            'unique_target',
            'user_uid',
            'table_name',
            'action',
            'logged_at',
            'status',
            'login_id'
          ),
          'value' => array(
            $uid,
            $UserData['data']->uid,
            'master_kuisioner_domain',
            'I',
            parent::format_date(),
            'N',
            $UserData['data']->log_id
          ),
          'class' => __CLASS__
        ));
      }

      return $proc;
    } else {
      return array(
        'response_result' => 0,
        'response_message' => 'Nama domain tidak boleh kosong'
      );
    }
  }

  private function tambah_indikator($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['nama']) && !empty($parameter['nama'])) {
      $uid = parent::gen_uuid();
      $proc = self::$query->insert('master_kuisioner_indikator', array(
        'uid' => $uid,
        'nama' => $parameter['nama'],
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
      if ($proc['response_result'] > 0) {
        $log = parent::log(array(
          'type' => 'activity',
          'column' => array(
            'unique_target',
            'user_uid',
            'table_name',
            'action',
            'logged_at',
            'status',
            'login_id'
          ),
          'value' => array(
            $uid,
            $UserData['data']->uid,
            'master_kuisioner_indikator',
            'I',
            parent::format_date(),
            'N',
            $UserData['data']->log_id
          ),
          'class' => __CLASS__
        ));
      }

      return $proc;
    } else {
      return array(
        'response_result' => 0,
        'response_message' => 'Nama indikator tidak boleh kosong'
      );
    }
  }

  private function tambah_aspek($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['nama']) && !empty($parameter['nama'])) {
      $uid = parent::gen_uuid();
      $proc = self::$query->insert('master_kuisioner_aspek', array(
        'uid' => $uid,
        'nama' => $parameter['nama'],
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
      if ($proc['response_result'] > 0) {
        $log = parent::log(array(
          'type' => 'activity',
          'column' => array(
            'unique_target',
            'user_uid',
            'table_name',
            'action',
            'logged_at',
            'status',
            'login_id'
          ),
          'value' => array(
            $uid,
            $UserData['data']->uid,
            'master_kuisioner_aspek',
            'I',
            parent::format_date(),
            'N',
            $UserData['data']->log_id
          ),
          'class' => __CLASS__
        ));
      }

      return $proc;
    } else {
      return array(
        'response_result' => 0,
        'response_message' => 'Nama aspek tidak boleh kosong'
      );
    }
  }

  private function get_indikator_back_end($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'master_kuisioner_indikator.deleted_at' => 'IS NULL',
        'AND',
        'master_kuisioner_indikator.nama' => 'LIKE ' . '\'%' . $parameter['search']['value'] . '%\''
      );

      $paramValue = array();
    } else {
      $paramData = array(
        'master_kuisioner_indikator.deleted_at' => 'IS NULL'
      );

      $paramValue = array();
    }


    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_kuisioner_indikator', array(
        'uid',
        'nama',
        'created_at',
        'updated_at'
      ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_kuisioner_indikator', array(
        'uid',
        'nama',
        'created_at',
        'updated_at'
      ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }
    $itemTotal = self::$query->select('master_kuisioner_indikator', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }

  private function get_domain_back_end($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'master_kuisioner_domain.deleted_at' => 'IS NULL',
        'AND',
        'master_kuisioner_domain.nama' => 'LIKE ' . '\'%' . $parameter['search']['value'] . '%\''
      );

      $paramValue = array();
    } else {
      $paramData = array(
        'master_kuisioner_domain.deleted_at' => 'IS NULL'
      );

      $paramValue = array();
    }


    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_kuisioner_domain', array(
        'uid',
        'nama',
        'created_at',
        'updated_at'
      ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_kuisioner_domain', array(
        'uid',
        'nama',
        'created_at',
        'updated_at'
      ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }
    $itemTotal = self::$query->select('master_kuisioner_domain', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }

  private function get_aspek_back_end($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'master_kuisioner_aspek.deleted_at' => 'IS NULL',
        'AND',
        'master_kuisioner_aspek.nama' => 'LIKE ' . '\'%' . $parameter['search']['value'] . '%\''
      );

      $paramValue = array();
    } else {
      $paramData = array(
        'master_kuisioner_aspek.deleted_at' => 'IS NULL'
      );

      $paramValue = array();
    }


    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_kuisioner_aspek', array(
        'uid',
        'nama',
        'created_at',
        'updated_at'
      ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_kuisioner_aspek', array(
        'uid',
        'nama',
        'created_at',
        'updated_at'
      ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }
    $itemTotal = self::$query->select('master_kuisioner_aspek', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }

  private function get_kuisioner_back_end($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'kuisioner_pertanyaan.deleted_at' => 'IS NULL',
        'AND',
        'pegawai.deleted_at' => 'IS NULL',
        'AND',
        '(kuisioner_pertanyaan.indikator' => 'LIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'kuisioner_pertanyaan.pertanyaan' => 'LIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
      );

      $paramValue = array();
    } else {
      $paramData = array(
        'kuisioner_pertanyaan.deleted_at' => 'IS NULL'
      );

      $paramValue = array();
    }


    if ($parameter['length'] < 0) {
      $data = self::$query->select('kuisioner_pertanyaan', array(
        'uid',
        'domain',
        'aspek',
        'indikator',
        'pertanyaan',
        'level_0',
        'level_1',
        'level_2',
        'level_3',
        'level_4',
        'level_5',
        'penjelasan_indikator',
        'user_uid',
        'created_at',
        'updated_at'
      ))
        ->join('pegawai', array(
          'nama as nama_pegawai'
        ))
        ->join('master_kuisioner_domain', array(
          'nama as nama_domain'
        ))
        ->join('master_kuisioner_aspek', array(
          'nama as nama_aspek'
        ))
        ->join('master_kuisioner_indikator', array(
          'nama as nama_indikator'
        ))
        ->on(array(
          array('kuisioner_pertanyaan.user_uid', '=', 'pegawai.uid'),
          array('kuisioner_pertanyaan.domain', '=', 'master_kuisioner_domain.uid'),
          array('kuisioner_pertanyaan.aspek', '=', 'master_kuisioner_aspek.uid'),
          array('kuisioner_pertanyaan.indikator', '=', 'master_kuisioner_indikator.uid')
        ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('kuisioner_pertanyaan', array(
        'uid',
        'domain',
        'aspek',
        'indikator',
        'pertanyaan',
        'level_0',
        'level_1',
        'level_2',
        'level_3',
        'level_4',
        'level_5',
        'penjelasan_indikator',
        'user_uid',
        'created_at',
        'updated_at'
      ))
        ->join('pegawai', array(
          'nama as nama_pegawai'
        ))
        ->join('master_kuisioner_domain', array(
          'nama as nama_domain'
        ))
        ->join('master_kuisioner_aspek', array(
          'nama as nama_aspek'
        ))
        ->join('master_kuisioner_indikator', array(
          'nama as nama_indikator'
        ))
        ->on(array(
          array('kuisioner_pertanyaan.user_uid', '=', 'pegawai.uid'),
          array('kuisioner_pertanyaan.domain', '=', 'master_kuisioner_domain.uid'),
          array('kuisioner_pertanyaan.aspek', '=', 'master_kuisioner_aspek.uid'),
          array('kuisioner_pertanyaan.indikator', '=', 'master_kuisioner_indikator.uid')
        ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }
    $itemTotal = self::$query->select('kuisioner_pertanyaan', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }
}
