<?php
	session_start();
	$_SESSION['message'] = '';
	$mysqli=new mysqli('localhost','root','Hsx@528#','accounts');
	if($mysqli){
		echo 'Connected';
	}
	else{
		echo 'Not connected';
	}
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if($_POST['password']== $_POST['confirmpassword']) {		

			$username = $mysqli->real_escape_string($_POST['username']);
			$password = md5($mysqli->real_escape_string($_POST['password']));
			$email    = $mysqli->real_escape_string($_POST['email']);
			$avatar   = $mysqli->real_escape_string('image/'.$_FILES['avatar']['name']);

			if(preg_match("!image!", $_FILES['avatar']['type'])){
				if(copy($_FILES['avatar']['tmp_name'],$avatar)){
					$_SESSION['username']=$username;
					$_SESSION['avatar']=$avatar;

					$sql="INSERT INTO Users(username,email,password,avatar)"
						."VALUES('$username','$email','$password','$avatar')";

					if($mysqli->query($sql)===true){
						$_SESSION['message']="Registration successful";
						header("location: welcome.php");
					}
					else{
						$_SESSION['message']="User can't be registered";
					}	
				}
				else{
					$_SESSION['message']="File upload failed";
				}
			}
			else{
				$_SESSION['message']="Please upload only PNG JPG or GIF";
			}	
		}
		else{
			$_SESSION['message']="Password does't match";
		}
	}
?>


<link rel="stylesheet" href="form.css" type="text/css">
<div class="body-content">
  <div class="module">
    <h1>Create an account</h1>
    <form class="form" action="form.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="alert alert-error"><?= $_SESSION['message'] ?></div>
      <input type="text" placeholder="User Name" name="username" required />
      <input type="email" placeholder="Email" name="email" required />
      <input type="password" placeholder="Password" name="password" autocomplete="new-password" required />
      <input type="password" placeholder="Confirm Password" name="confirmpassword" autocomplete="new-password" required />
      <div class="avatar"><label>Select your avatar: </label><input type="file" name="avatar" accept="image/*" required /></div>
      <input type="submit" value="Register" name="register" class="btn btn-block btn-primary" />
    </form>
  </div>
</div>
