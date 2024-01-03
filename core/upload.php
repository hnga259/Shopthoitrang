<?php

class Upload {

    protected $dir;
    public function __construct()
    {
        $this->dir = "../public/images/product/";
        if (!file_exists($this->dir)) {
            mkdir($this->dir);
        }
    }

    public function put($file)
    {
        $error = false;
        $type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name_file = uniqid() . '.' . $type;
        if ($type != "jpg" && $type != "png" && $type != "jpeg" && $type != "gif") {
            $error = true;
        }
        if (!$error) {
            move_uploaded_file($file["tmp_name"], $this->dir . $name_file);
            return $name_file;
        }
        return false;
    }

    public function put_multiple($files)
    {
        $arr = [];
        foreach ($files['error'] as $key => $error) {
            if ($error == 0) {
                $error_file = false;
                $type = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));
                $name_file = uniqid() . '.' . $type;
                if ($type != "jpg" && $type != "png" && $type != "jpeg" && $type != "gif") {
                    $error_file = true;
                }
                if (!$error_file) {
                    move_uploaded_file($files["tmp_name"][$key], $this->dir . $name_file);
                    $arr[] = $name_file;
                }
            }
        }
        return $arr;
    }

    public function delete($image)
    {
        $path = $this->dir.$image;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}