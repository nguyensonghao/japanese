var listResult = [];

var search = function (query, numberRecord) {
	$('.searching').fadeIn(100);
	$.ajax({
		url : 'http://103.253.145.165:8787/search/'+ query +'/' + numberRecord,
		type : 'get',
		success : function (data) {
			console.log(data);
			$('.searching').fadeOut(100);
			var listUrl = JSON.parse(data);
			listResult = [];
			var str = '';
			for (var i = 0; i < listUrl.length; i++) {
				var image = {id : i, url : listUrl[i]};
				str += '<div class="col-md-3 img" onclick="saveImage('+ i +', 2)"><img src="'+ listUrl[i] +'" class="img-thumbnail"></div>';
				listResult.push(image);
			}

			$('.search-result').html(str);
		}
	})
}

var getUrlbyId = function (id) {
	var size = listResult.length;
	for (var i = 0; i < size; i++) {
		var e = listResult[i];
		if (e.id == id) {
			return e.url;
		}
	}

	return null;
}

var saveImage = function (urlId, id) {		
	var url = getUrlbyId(urlId);
	if (url == null) {
		alert('Có lỗi xảy ra');
	} else {
		$.ajax({
			url : 'save-url-image',
			data : {url : url, id : id},
			type : 'post',
			success : function (data) {
				console.log(data);
			}
		})
	}
}

$('.btn-search').click(function () {
	var query = $('#searchInput').val();
	var numberRecord = $('#numberRecord').val();
	if (query == '') {
		alert('Hãy nhập từ khóa tìm kiếm');
	} else if (numberRecord == null || numberRecord == '') {
		alert('Hãy nhập số lượng kết quả cần tìm')
	} else search(query, numberRecord);
})

$('#searchInput').bind('keypress', function(e) {
	var code = e.keyCode || e.which;
	if(code == 13) {
		var query = $('#searchInput').val();
		var numberRecord = $('#numberRecord').val();
		if (query == '') {
			alert('Hãy nhập từ khóa tìm kiếm');
		} else if (numberRecord == null || numberRecord == '') {
			alert('Hãy nhập số lượng kết quả cần tìm')
		} else search(query, numberRecord);
	}
});
