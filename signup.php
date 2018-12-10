<?php
include 'haudeptrai.php';
date_default_timezone_set('UTC');
//var_dump($_POST);

if(!isset($_POST['next'])){
	header("Location: .");
}
$user 		= urldecode($_POST['username']);
$password	= hash('sha256', urldecode($_POST['password']));
$email		= urldecode($_POST['email']);
$name		= urldecode($_POST['fullName']);
$ns 		= urldecode($_POST['birthday']);
$user = strip_tags($user);
$name = strip_tags($name);

$imageLink = uploadImage();

//In ra noi dung html cua trang chu
echo file_get_contents('Home.html');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	jsalert("Đăng ký thất bại. Định dạng email không hợp lệ!");
  exit(); 
}
if(strtotime($ns) > time()){
	jsalert("Đăng ký thất bại. Ngày sinh không hợp lệ!");
	exit();
}
if($imageLink == ""){
	jsalert("Đăng ký thất bại. Lỗi tải ảnh!");
	exit();
}

$db = getDbConnect();

$stmt = $db->prepare('INSERT INTO _user (username, hoten, ngaysinh, password, email, image, ngaytao) 
						VALUES (:user, :name, :ns, :password, :email, :img, :ngaytao)');
//$stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$data = array(	'user'		=> $user,
				'password'	=> $password,
				'email'		=> $email,
				'name'		=> $name,
				'ns'		=> $ns,
				'img'		=> $imageLink,
				'ngaytao'	=> date('Y-m-d'));
try {
	$stmt->execute($data);
	jsalert('Chúc mừng bạn đăng kí thành công với tài khoản: ' . $user);
} catch (PDOException $e) {
	jsalert('Đăng ký thất bại. Đăng ký lỗi!');
	$e->getMessage( ) ;
}

?>