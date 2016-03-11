<!DOCTYPE html>
<html>
<head>
	<title>Tìm kiếm hình ảnh</title>
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/bootstrap/css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/css/search.css') }}">
	<script type="text/javascript" 
	src="{{ Asset('public/libs/bootstrap/js/jquery-2.1.3.min.js') }}"></script>
	<script type="text/javascript" src="{{Asset('public/libs/bootstrap/js/bootstrap.min.js')}}"></script>
	<style type="text/css">
		.list-image {
			width: 100%;
		}

		.image {
			width: 15%;
			margin: 10px;
			padding: 10px;
			text-align: center;
			background: white;
			float: left;
		}

		.modal-content {
			width: 1000px;
    		margin-left: -200px;
		}

		.modal-list-image {
			float: left;
			margin: 10px;
			cursor: pointer;
		}

		.modal-body {
			overflow: auto;
		}

		.cover {
			width: 100vw;
			height: 100vh;
			background: rgba(0, 0, 0, 0.8);
			display: none;
			z-index: 1000000;
			position: fixed;
			padding-top: 200px;
		}

		.cover p {
			font-size: 20px;
			color: white;
			text-align: center;
		}

		.thumbnail {
			z-index: 1;
		}
	</style>
</head>
<body>
	<div class="list-image">	
		@foreach($list_image as $value)
		<div class="image">
		    <a class="thumbnail">
		      <img src="{{ $value->imageId }}.jpg" width="334" height="254" 
		      onclick="searchImage({{ $value->imageId }}, '{{ $value->imageMean }}')">
		    </a>
		    <p class="caption">
		    	{{ $value->imageWord }}
		    </caption>
	  	</div>
	  	@endforeach 
	</div>

	<div class="modal fade" id="modal-list-image">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">
						Danh sách tìm kiếm cho từ 
						<span class="imageMean"></span>
					</h4>
				</div>
				<div class="modal-body">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Đóng
					</button>
				</div>
			</div>
		</div>
	</div>

	{{ $list_image->links() }}

	<div class="cover">
		<p>Đang tìm kiếm...</p>
	</div>
</body>
<script>

	var LIST_IMAGE = [];
	
	var searchImage = function (id, mean) {
		showCover(true, 'Đang tìm kiếm...');
		$.ajax({
			url : 'search-image',
			data : {imageMean : mean},
			type : 'post',
			success : function (data) {
				var listImage = JSON.parse(data);
				if (listImage.status == 304) {
					alert('Không có kết quả tìm kiếm');
				} else {
					$('.imageMean').html(mean);			
					var result = listImage.result;
					var stringListImage = '';
					LIST_IMAGE = [];
					for (var i = 0; i < result.length; i++) {
						LIST_IMAGE.push({
							index : i,
							url : result[i]
						});
						stringListImage += "<div class='modal-list-image'>";
						stringListImage += "<img width='200px' height='150px' src='" + result[i] + "' onclick='saveImage("+ i +", "+ id +")'>";
						stringListImage += '</div>';
					}
					showCover(false, 'Loadding...');
					$('.modal-body').html(stringListImage);
					$('#modal-list-image').modal('show');
				}
			}
		})
	}

	var saveImage = function (index, id) {
		var size = LIST_IMAGE.length;
		$('#modal-list-image').modal('hide');
		showCover(true, 'Đang xử lí tải ảnh...');
		for (var i = 0; i < size; i++) {
			if (index == LIST_IMAGE[i].index) {
				$.ajax({
					url : 'save-url-image',
					data : {url : LIST_IMAGE[i].url, id : id},
					type : 'post',
					success : function (data) {
						location.reload();
					}
				})
			}
		}
	}

	var showCover = function (type, text)	 {
		if (type) {
			$('.cover').css('display', 'block');
			$('.cover p').html(text);
		} else $('.cover').css('display', 'none');
	}

</script>
</html>