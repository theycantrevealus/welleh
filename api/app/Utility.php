<?php
namespace PondokCoder;

use DateTime;

abstract class Utility {
	protected abstract static function getConn();
	const appRoot = './';

	public function gen_uuid() {
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0x0fff) | 0x4000,
			mt_rand(0, 0x3fff) | 0x8000,
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}

	public static function getClassesInNamespace($namespace) {
		if(self::getNamespaceDirectory($namespace) == 203) {
			return 'lock file not found : ' . self::appRoot;
		} else {
			$files = scandir(self::getNamespaceDirectory($namespace));
			$classes = array_map(function($file) use ($namespace) {
				return $namespace . '\\' . str_replace('.php', '', $file);
			}, $files);
			
			return array_filter($classes, function($possibleClass){
				return class_exists($possibleClass);
			});
		}
	}

    public function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    public function humanTiming ($time) {

        $time = time() - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text.(($numberOfUnits>1) ? 's' : '');
        }
    }

    public function numberToRoman($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function saveBase64ImagePng($data, $target, $name) {
        //Check
        if(is_writable('../images')) {
            if(!file_exists('../images/documentation') || !is_dir('../images/documentation')) {
                mkdir('../images/documentation');
            }

            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);

            return file_put_contents($target . '/' . $name, $data);
        } else {
            return 'Cant write directory';
        }
    }

    public function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = self::penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = self::penyebut($nilai/10)." puluh". self::penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . self::penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = self::penyebut($nilai/100) . " ratus" . self::penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . self::penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = self::penyebut($nilai/1000) . " ribu" . self::penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = self::penyebut($nilai/1000000) . " juta" . self::penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = self::penyebut($nilai/1000000000) . " milyar" . self::penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = self::penyebut($nilai/1000000000000) . " trilyun" . self::penyebut(fmod($nilai,1000000000000));
        }
        return $temp;
    }

    public function terbilang($nilai) {
        $x = stristr($nilai, '.');

        if($nilai < 0) {
            $hasil = "minus ". trim(self::penyebut($nilai));
        } else {
            $hasil = trim(self::penyebut($nilai));
        }
        $sen = explode('.', $x);
        $sen = str_pad($sen[count($sen) - 1], 2, "0", STR_PAD_RIGHT);

        if(floatval($sen) > 0) {
            return $hasil . ' '. trim(self::penyebut(floatval($sen))) . ' sen';
        } else {
            return $hasil;
        }
    }

	private static function getDefinedNamespaces() {
		$composerJsonPath = self::appRoot . 'composer.json';
		if(file_exists($composerJsonPath)) {
			$composerConfig = json_decode(file_get_contents($composerJsonPath));

			$psr4 = "psr-4";
			return (array) $composerConfig->autoload->$psr4;
		} else {
			return 203;
		}
	}

	private static function getNamespaceDirectory($namespace) {
		$composerNamespaces = self::getDefinedNamespaces();
		if($composerNamespaces == 203) {
			return $composerNamespaces;
		} else {
			$namespaceFragments = explode('\\', $namespace);
			$undefinedNamespaceFragments = [];

			while($namespaceFragments) {
				$possibleNamespace = implode('\\', $namespaceFragments) . '\\';

				if(array_key_exists($possibleNamespace, $composerNamespaces)){
					return realpath(self::appRoot . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments));
				}
				array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));            
			}
			return false;
		}
	}

	public function date_sort($a, $b) {
        return strtotime($a) - strtotime($b);
    }

    public function license_manager() {
        if(!isset($_SESSION['license']) || $_SESSION['license'] !== true) {
            //Get Client IP
            $ip = '';

            //Get Client Harddisk number
            $harddisk = '';

            //Package Information
            $package = '';

            //Check to License Server
            //


        } else {
            return $_SESSION['license'];
        }
    }

	public function format_date() {
		$micro_date = microtime();
		$date_array = explode(" ",$micro_date);
		$date = date("Y-m-d H:i:s",$date_array[1]);

		return $date;
	}

	public static function log($parameter = array()) {
		/*
			type,
			column,
			value,
			class
		*/
		if(count($parameter['column']) != count($parameter['value'])) {
			return 0;
		} else {
			$columnBuilder = array();
			foreach ($parameter['column'] as $key => $value) {
				array_push($columnBuilder, "?");
			}

			$query = static::getConn()->prepare('INSERT INTO log_' . $parameter['type'] . '(' . implode(",", $parameter['column']) . ') VALUES (' . implode(",", $columnBuilder) . ')');
			$query->execute($parameter['value']);
			if($query->rowCount() > 0) {
				return static::getConn()->lastInsertId();
			} else {
				return 0;
				$error_log = static::getConn()->prepare('INSERT INTO log_error (type_err, class_err, messages, logged_at) VALUES (?, ?, ?, NOW())');
				$error_log->execute(array(
					$parameter['type'],
					$parameter['class'],
                    $parameter['message']
				));
			}
		}
	}

    public function array2csv(array &$array) {
       if (count($array) == 0) {
         return null;
       }
       ob_start();
       $df = fopen("php://output", 'w');
       fputcsv($df, array_keys(reset($array)));
       foreach ($array as $row) {
          fputcsv($df, $row);
       }
       fclose($df);
       return ob_get_clean();
    }

    public function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
    
        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

	public static function hitungUsia($date) {
		$biday = new DateTime($date);		
		$today = new DateTime();
	
		$diff = $today->diff($biday);
		
		$result=$diff->y." thn ".$diff->m." bln ".$diff->d." hari";
		return $result;
	}

	public static function dateToIndo($date) {
	    $HariIndo = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu');
		$BulanIndo = array("Januari", "Februari", "Maret",
						   "April", "Mei", "Juni",
						   "Juli", "Agustus", "September",
						   "Oktober", "November", "Desember");
	
		$tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
		$bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
		$tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
		
		$result = $HariIndo[intval(date('w', strtotime($date)))] . ', ' .$tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		return $result;
	}

	public static function dateToIndoSlash($date) {
		// fungsi atau method untuk mengubah tanggal ke format indonesia
		// variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
			 
		$tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
		$bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
		$tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
		
		$result = $tgl . "/" .$bulan. "/". $tahun;
		return $result;
	}

	public function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
