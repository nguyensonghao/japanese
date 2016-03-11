<!DOCTYPE html>
<html>
<head>
	<title>Tìm kiếm hình ảnh</title>
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/bootstrap/css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/css/search.css') }}">
	<script type="text/javascript" src="{{ Asset('public/libs/bootstrap/js/jquery-2.1.3.min.js') }}"></script>
	<script type="text/javascript" src="{{ Asset('public/libs/bootstrap/js/bootstrap.min.js') }}"></script>
</head>
<body>
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Tìm kiếm</a>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="search-box col-md-12">
			<div class="col-md-7">
			    <div class="input-group">
			      <input type="text" class="form-control" id="searchInput" placeholder="Nhập từ khóa tìm kiếm">
			      <div class="input-group-addon btn-search">
			      	<span class="glyphicon glyphicon-search"></span>
			      </div>
			    </div>
			</div>

			<div class="col-md-5">
				<div class="col-md-4">
					<span>Số lượng:</span> 
					<input type="number" id="numberRecord" value="10" min="5" max="15">					
				</div>
				<div class="col-md-4 choice-language">
					<span>Ngôn ngữ</span>
					<select>
						<option>Tiếng Anh</option>
						<option>Tiếng Việt</option>
						<option>Tiếng Hàn</option>
						<option>Tiếng Trung</option>
					</select>
				</div>
				<div class="col-md-4">
					
				</div>
			</div>
		</div>
		
		<p class="searching">Đang tìm kiếm...</p>	

		<div class="search-result col-md-12">

		</div>
	</div>
</body>

<script type="text/javascript" src="{{ Asset('public/libs/js/search.js') }}"></script>
</html>