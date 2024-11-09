<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


const CMD_CONNECT = 1000;
const CMD_EXIT = 1001;
const CMD_ENABLEDEVICE = 1002;
const CMD_DISABLEDEVICE = 1003;
const CMD_RESTART = 1004;
const CMD_POWEROFF = 1005;
const CMD_SLEEP = 1006;
const CMD_RESUME = 1007;
const CMD_TEST_TEMP = 1011;
const CMD_TESTVOICE = 1017;
const CMD_VERSION = 1100;
const CMD_CHANGE_SPEED = 1101;

// Konstanta ACK
const CMD_ACK_OK = 2000;
const CMD_ACK_ERROR = 2001;
const CMD_ACK_DATA = 2002;
const CMD_PREPARE_DATA = 1500;
const CMD_DATA = 1501;

// Konstanta User Commands
const CMD_USER_WRQ = 8;
const CMD_USERTEMP_RRQ = 9;
const CMD_USERTEMP_WRQ = 10;
const CMD_OPTIONS_RRQ = 11;
const CMD_OPTIONS_WRQ = 12;
const CMD_ATTLOG_RRQ = 13;
const CMD_CLEAR_DATA = 14;
const CMD_CLEAR_ATTLOG = 15;
const CMD_DELETE_USER = 18;
const CMD_DELETE_USERTEMP = 19;
const CMD_CLEAR_ADMIN = 20;
const CMD_ENABLE_CLOCK = 57;
const CMD_STARTVERIFY = 60;
const CMD_STARTENROLL = 61;
const CMD_CANCELCAPTURE = 62;
const CMD_STATE_RRQ = 64;
const CMD_WRITE_LCD = 66;
const CMD_CLEAR_LCD = 67;

// Konstanta Time Commands
const CMD_GET_TIME = 201;
const CMD_SET_TIME = 202;

// Konstanta Maksimal Unsigned Short
const USHRT_MAX = 65535;

// Level Konstanta
const LEVEL_USER = 0;          // 0000 0000
const LEVEL_ENROLLER = 2;      // 0000 0010
const LEVEL_MANAGER = 12;      // 0000 1100
const LEVEL_SUPERMANAGER = 14; // 0000 1110


class ZKLibrary
{
    public $ip = null;
    public $port = null;
    public $socket = null;
    public $session_id = 0;
    public $received_data = '';
    public $user_data = array();
    public $attendance_data = array();
    public $timeout_sec = 5;
    public $timeout_usec = 5000000;

    public function __construct($ip = null, $port = null)
    {
        if ($ip != null) {
            $this->ip = $ip;
        }
        if ($port != null) {
            $this->port = $port;
        }
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        $this->setTimeout($this->timeout_sec, $this->timeout_usec);
    }

    public function __destruct()
    {
        unset($this->received_data);
        unset($this->user_data);
        unset($this->attendance_data);
    }

    public function connect($ip = null, $port = 4370)
    {
        if ($ip != null) {
            $this->ip = $ip;
        }
        if ($port != null) {
            $this->port = $port;
        }
        if ($this->ip == null || $this->port == null) {
            return false;
        }
        $command = CMD_CONNECT;
        $command_string = '';
        $chksum = 0;
        $session_id = 0;
        $reply_id = -1 + USHRT_MAX;
        $buf = $this->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        socket_sendto($this->socket, $buf, strlen($buf), 0, $this->ip, $this->port);
        try {
            socket_recvfrom($this->socket, $this->received_data, 1024, 0, $this->ip, $this->port);
            if (strlen($this->received_data) > 0) {
                $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr($this->received_data, 0, 8));
                $this->session_id = hexdec($u['h6'] . $u['h5']);
                return $this->checkValid($this->received_data);
            } else {
                return false;
            }
        } catch (ErrorException $e) {
            return false;
        } catch (exception $e) {
            return false;
        }
    }

    public function disconnect()
    {
        if ($this->ip == null || $this->port == null) {
            return false;
        }

        $command = CMD_EXIT;
        $command_string = '';
        $chksum = 0;
        $session_id = $this->session_id;

        if ($this->received_data === null) {
            return false;
        }

        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($this->received_data, 0, 8));
        $reply_id = hexdec($u['h8'] . $u['h7']);
        $buf = $this->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        socket_sendto($this->socket, $buf, strlen($buf), 0, $this->ip, $this->port);

        try {
            socket_recvfrom($this->socket, $this->received_data, 1024, 0, $this->ip, $this->port);
            return $this->checkValid($this->received_data);
        } catch (ErrorException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }


    public function setTimeout($sec = 0, $usec = 0)
    {
        if ($sec != 0) {
            $this->timeout_sec = $sec;
        }
        if ($usec != 0) {
            $this->timeout_usec = $usec;
        }
        $timeout = array('sec' => $this->timeout_sec, 'usec' => $this->timeout_usec);
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, $timeout);
    }

    private function reverseHex($input)
    {
        $output = '';
        for ($i = strlen($input); $i >= 0; $i--) {
            $output .= substr($input, $i, 2);
            $i--;
        }
        return $output;
    }

    private function encodeTime($time)
    {
        $str = str_replace(array(":", " "), array("-", "-"), $time);
        $arr = explode("-", $str);
        $year = isset($arr[0]) ? $arr[0] * 1 : 0;
        $month = isset($arr[1]) ? ltrim($arr[1], '0') * 1 : 0;
        $day = isset($arr[2]) ? ltrim($arr[2], '0') * 1 : 0;
        $hour = isset($arr[3]) ? ltrim($arr[3], '0') * 1 : 0;
        $minute = isset($arr[4]) ? ltrim($arr[4], '0') * 1 : 0;
        $second = isset($arr[5]) ? ltrim($arr[5], '0') * 1 : 0;
        $data = (($year % 100) * 12 * 31 + (($month - 1) * 31) + $day - 1) * (24 * 60 * 60) + ($hour * 60 + $minute) * 60 + $second;
        return $data;
    }

    private function decodeTime($data)
    {
        $second = $data % 60;
        $data = (int)($data / 60);  // Explicitly cast to int
        $minute = $data % 60;
        $data = (int)($data / 60);  // Explicitly cast to int
        $hour = $data % 24;
        $data = (int)($data / 24);  // Explicitly cast to int
        $day = ($data % 31) + 1;
        $data = (int)($data / 31);  // Explicitly cast to int
        $month = ($data % 12) + 1;
        $data = (int)($data / 12);  // Explicitly cast to int
        $year = floor($data + 2000);

        $d = date("Y-m-d H:i:s", strtotime($year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':' . $second));
        return $d;
    }


    private function checkSum($p)
    {
        /* This function calculates the chksum of the packet to be sent to the time clock */
        $l = count($p);
        $chksum = 0;
        $i = $l;
        $j = 1;
        while ($i > 1) {
            $u = unpack('S', pack('C2', $p['c' . $j], $p['c' . ($j + 1)]));
            $chksum += $u[1];
            if ($chksum > USHRT_MAX) {
                $chksum -= USHRT_MAX;
            }
            $i -= 2;
            $j += 2;
        }
        if ($i) {
            $chksum = $chksum + $p['c' . strval(count($p))];
        }
        while ($chksum > USHRT_MAX) {
            $chksum -= USHRT_MAX;
        }
        if ($chksum > 0) {
            $chksum = - ($chksum);
        } else {
            $chksum = abs($chksum);
        }
        $chksum -= 1;
        while ($chksum < 0) {
            $chksum += USHRT_MAX;
        }
        return pack('S', $chksum);
    }

    public function createHeader($command, $chksum, $session_id, $reply_id, $command_string)
    {
        $buf = pack('SSSS', $command, $chksum, $session_id, $reply_id) . $command_string;
        $buf = unpack('C' . (8 + strlen($command_string)) . 'c', $buf);
        $u = unpack('S', $this->checkSum($buf));

        if (is_array($u)) {
            // Ganti penggunaan each() dengan foreach
            foreach ($u as $key => $value) {
                $u = $value;
                break;
            }
        }

        $chksum = $u;
        $reply_id += 1;
        if ($reply_id >= USHRT_MAX) {
            $reply_id -= USHRT_MAX;
        }
        $buf = pack('SSSS', $command, $chksum, $session_id, $reply_id);
        return $buf . $command_string;
    }


    private function checkValid($reply)
    {
        $u = unpack('H2h1/H2h2', substr($reply, 0, 8));
        $command = hexdec($u['h2'] . $u['h1']);
        if ($command == CMD_ACK_OK) {
            return true;
        } else {
            return false;
        }
    }

    private function getSizeAttendance()
    {
        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($this->received_data, 0, 8));
        $command = hexdec($u['h2'] . $u['h1']);
        if ($command == CMD_PREPARE_DATA) {
            $u = unpack('H2h1/H2h2/H2h3/H2h4', substr($this->received_data, 8, 4));
            $size = hexdec($u['h4'] . $u['h3'] . $u['h2'] . $u['h1']);
            return $size;
        } else {
            return false;
        }
    }






    // public function getUserData()
    //     {
    //         $uid = 1;
    //         $command = CMD_USERTEMP_RRQ;
    //         $command_string = chr(5);
    //         $chksum = 0;
    //         $session_id = $this->session_id;
    //         $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($this->received_data, 0, 8));
    //         $reply_id = hexdec($u['h8'] . $u['h7']);
    //         $buf = $this->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
    //         socket_sendto($this->socket, $buf, strlen($buf), 0, $this->ip, $this->port);
    //         try {
    //             socket_recvfrom($this->socket, $this->received_data, 1024, 0, $this->ip, $this->port);
    //             $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr($this->received_data, 0, 8));
    //             $bytes = $this->getSizeUser();
    //             if ($bytes) {
    //                 while ($bytes > 0) {
    //                     socket_recvfrom($this->socket, $received_data, 1032, 0, $this->ip, $this->port);
    //                     array_push($this->user_data, $received_data);
    //                     $bytes -= 1024;
    //                 }
    //                 $this->session_id =  hexdec($u['h6'] . $u['h5']);
    //                 socket_recvfrom($this->socket, $received_data, 1024, 0, $this->ip, $this->port);
    //             }
    //             $users = array();
    //             $retdata = "";
    //             if (count($this->user_data) > 0) {
    //                 for ($x = 0; $x < count($this->user_data); $x++) {
    //                     if ($x > 0) {
    //                         $this->user_data[$x] = substr($this->user_data[$x], 8);
    //                     }
    //                     if ($x > 0) {
    //                         $retdata .= substr($this->user_data[$x], 0);
    //                     } else {
    //                         $retdata .= substr($this->user_data[$x], 12);
    //                     }
    //                 }
    //             }
    //             return $retdata;
    //         } catch (ErrorException $e) {
    //             return false;
    //         } catch (exception $e) {
    //             return false;
    //         }
    //     }

    public function getAttendance()
    {
        // Pastikan session_id dan received_data sudah dideklarasikan
        $command = CMD_ATTLOG_RRQ;
        $command_string = '';
        $chksum = 0;
        $session_id = $this->session_id;

        // Memastikan $this->received_data sudah ada dan valid
        if (empty($this->received_data)) {
            return false;  // atau penanganan error lainnya
        }

        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($this->received_data, 0, 8));
        $reply_id = hexdec($u['h8'] . $u['h7']);

        // Buat header
        $buf = $this->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        socket_sendto($this->socket, $buf, strlen($buf), 0, $this->ip, $this->port);

        try {
            // Terima data dari socket
            socket_recvfrom($this->socket, $this->received_data, 1024, 0, $this->ip, $this->port);
            $bytes = $this->getSizeAttendance();

            if ($bytes) {
                while ($bytes > 0) {
                    socket_recvfrom($this->socket, $received_data, 1032, 0, $this->ip, $this->port);
                    array_push($this->attendance_data, $received_data);
                    $bytes -= 1024;
                }
                $this->session_id = hexdec($u['h6'] . $u['h5']);
                socket_recvfrom($this->socket, $received_data, 1024, 0, $this->ip, $this->port);
            }

            $attendance = array();

            // Jika ada data kehadiran
            if (count($this->attendance_data) > 0) {
                // Proses setiap data yang diterima
                for ($x = 0; $x < count($this->attendance_data); $x++) {
                    if ($x > 0) {
                        $this->attendance_data[$x] = substr($this->attendance_data[$x], 8);  // Menghilangkan header
                    }
                }

                // Gabungkan seluruh data kehadiran
                $attendance_data = implode('', $this->attendance_data);
                $attendance_data = substr($attendance_data, 10);  // Buang header tambahan

                while (strlen($attendance_data) > 40) {
                    $u = unpack('H78', substr($attendance_data, 0, 39));
                    $u1 = hexdec(substr($u[1], 4, 2));
                    $u2 = hexdec(substr($u[1], 6, 2));
                    $uid = $u1 + ($u2 * 256);
                    $id = str_replace("\0", '', hex2bin(substr($u[1], 8, 16)));
                    $state = hexdec(substr($u[1], 56, 2));
                    $timestamp = $this->decodeTime(hexdec($this->reverseHex(substr($u[1], 58, 8))));

                    // Masukkan data ke dalam array
                    array_push($attendance, array($uid, $id, $state, $timestamp));

                    // Potong data yang sudah diproses
                    $attendance_data = substr($attendance_data, 40);
                }
            }

            return $attendance;  // Kembalikan data kehadiran

        } catch (Exception $e) {
            // Tangani exception dengan log atau error handling
            return false;
        }
    }
}
