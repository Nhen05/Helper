<?php
class Helper {
    // Tạo nút (button)
    public static function cmsButton($name, $id, $link, $icon, $type = 'new') {
        $xhtml = '<li class="button" id="' . $id . '">';
        if ($type == 'new') {
            $xhtml .= '<a class="modal" href="' . $link . '"><span class="' . $icon . '"></span>' . $name . '</a>';
        } else if ($type == 'submit') {
            $xhtml .= '<a class="modal" href="#" onclick="javascript:submitForm();"><span class="' . $icon . '"></span>' . $name . '</a>';
        }
        $xhtml .= '</li>';
        return $xhtml;
    }

    // Tạo biểu tượng trạng thái
    public static function cmsStatus($statusValue, $link, $id) {
        $strStatus = ($statusValue == 0) ? 'unpublish' : 'publish';
        $xhtml = '<a class="jgrid" id="status-' . $id . '" href="javascript:changeStatus(\'' . $link . '\');">
                    <span class="state ' . $strStatus . '"></span>
                  </a>';
        return $xhtml;
    }

    // Tạo biểu tượng đặc biệt
    public static function cmsSpecial($statusValue, $link, $id) {
        $strStatus = ($statusValue == 0) ? 'unpublish' : 'publish';
        $xhtml = '<a class="jgrid" id="special-' . $id . '" href="javascript:changeSpecial(\'' . $link . '\');">
                    <span class="state ' . $strStatus . '"></span>
                  </a>';
        return $xhtml;
    }
}

class Validator {
    // Kiểm tra định dạng ngày tháng (date)
    public static function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    // Kiểm tra dữ liệu bắt buộc (required)
    public static function validateRequired($value) {
        return !empty(trim($value));  // Kiểm tra xem giá trị có rỗng không
    }

    // Kiểm tra số (numeric)
    public static function validateNumber($value) {
        return is_numeric($value);
    }

    // Kiểm tra tên chỉ được chứa chữ (không có ký tự đặc biệt)
    public static function validateName($name) {
        // Kiểm tra nếu tên chỉ chứa chữ cái và khoảng trắng
        return preg_match('/^[a-zA-Z\s]+$/', $name);
    }

    // Kiểm tra email hợp lệ (có @ và .com)
    public static function validateEmail($email) {
        // Kiểm tra nếu email có chứa @ và kết thúc bằng .com
        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.*\.com$/', $email);
    }
}

// Class xử lý thêm, xóa, sửa
class DatabaseHandler {
    private $pdo;

    public function __construct($host, $dbname, $user, $pass) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Thêm dữ liệu
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // Xóa dữ liệu
    public function delete($table, $condition, $params) {
        $sql = "DELETE FROM $table WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Cập nhật dữ liệu
    public function update($table, $data, $condition, $params) {
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE $table SET $setClause WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_merge($data, $params));
    }
}
?>
