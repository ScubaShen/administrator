var indexPage = (function () {

	var urlArray = window.location.href.split('/')
        ,resultRowsFormat
        ,searchValidate

	    // paginate
        ,$currentPage = $('#current_page')
        ,$totalRows = $('#total_rows')
        ,$lastPage = $('#last_page')
        ,$rowsPerPage = $('#rows_per_page')
        ,$selectAll = $('#select-all')
        ,$deleteAll = $('#delete-all')
        ,$prePage = $('#pre_page')
        ,$nextPage = $('#next_page')
        ,$refresh = $('#refresh')

	    // results
        ,$loadingRows = $('.loading_rows')
        ,$resultsContainer = $('.results_container')
        ,$noResults = $('.no_results');

	$refresh.on('click', function() {
		getRows();
	});

	$rowsPerPage.on('change', function() {
		getRows();
	});
	$currentPage.on('blur', function() {
		getRows();
	});
	$prePage.on('click', function() {
		getRows(parseInt($currentPage.val())-1);
	});
	$nextPage.on('click', function() {
		getRows(parseInt($currentPage.val())+1);
	});

	$('#search').on('click', function() {
		if(searchValidate()) {
			getRowsBySearch();
		}
	});

	$('#cancel_search').on('click', function() {
		$('#search-form :input').val('');
		getRows(1);
	});

	$resultsContainer.on('click', '.results-checkbox', function() {
		if ($(this).prop('checked')) {
			$deleteAll.removeClass('disabled');
		} else {
			var delete_ids = [];
			$('.select-checkbox').each(function(){
				$(this).prop('checked') && delete_ids.push($(this).val());
			});
			if(delete_ids.length == 0) {
				$deleteAll.addClass('disabled');
			}
		}
	});

	$resultsContainer.on('click', '.results-name', function() {
		let _this = $(this)
				,url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + _this.data('id');
		$('#item_search_container').css('display', 'none');
		$('#item_show_container').css('display', 'block');
		$.ajax({
			url: url + '/view',
			type: 'GET',
			beforeSend: function() {
				history.replaceState('', '', url);
				$('.panel-right').scrollTop(0);
				$('.selected').removeClass('selected');
				_this.parents('.result_rows').addClass('selected');
				$('.loading').css('display', 'block');
			},
			success: function (data) {
				// ex: 把v iew-name 中的 name 取出，把 data.name 的值存入 view-name
				$.each($("#view").find("[id^='view-']"), function(index,element){
					let val = $(this).attr('id').split('-')[1];
					$(this).html(data[val]);
				});
				$('.loading').css('display', 'none');
			}
		})
	});

	$resultsContainer.on('click', '.results-delete', function() {
		deleteRows([String($(this).data('id'))]);
	});

	$selectAll.on('click', function() {
		if ($(this).prop('checked')){
			$deleteAll.removeClass('disabled');
			$('.select-checkbox').each(function(){
				$(this).prop('checked', true)
			})
		}else{
			$deleteAll.addClass('disabled');
			$('.select-checkbox').each(function() {
				$(this).prop('checked', false);
			})
		}
	});

	$deleteAll.on('click', function() {
		let delete_ids = [];
		$('.select-checkbox').each(function(){
			$(this).prop('checked') && delete_ids.push($(this).val());
		});
		if (delete_ids.length > 0) {
			deleteRows(delete_ids);
		}
	});

	$('#filter-btn').on('click', function (){
		$('#item_show_container').css('display', 'none');
		$('#item_search_container').css('display', 'block');
	});

	function deleteRows(ids) {
		let url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3];
		swal({
			title: "确认要删除该数据？",
			icon: "warning",
			buttons: ['取消', '确定'],
			dangerMode: true
		})
		.then(function (willDelete) {
			if (!willDelete) {
				return;
			}
			$.ajax({
				url: url + '/batch_delete',
				type: 'POST',
				data: {
					ids: ids,
					_method: 'delete'
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function () {
					if ($.inArray(window.location.href.split('/')[4], ids) !== -1) {
						history.replaceState('', '', url);
						$.each($("#view").find("[id^='view-']"), function (index, element) {
							$(this).html('');
						});
					}
				},
				success: function (data) {
					swal('条目已被删除', '', 'success');
					getRows();
				},
				error: function () {
					swal('权限不足', '', 'error');
				}
			})
		})
	}

	function getRows(page) {
		page = page || $currentPage.val();
		page = (/^\+?[1-9][0-9]*$/.test(page) && (page <= parseInt($lastPage.text()) && page || parseInt($lastPage.text())) || 1);

		// if on searching, use searching method , or use normal function
		if (searchValidate()) {
			getRowsBySearch(page);
		} else {
			$.ajax({
				url: urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/results',
				type: 'POST',
				data: {
					rows_per_page: $rowsPerPage.val(),
					page: page
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					$currentPage.val(page);
					$loadingRows.css('display', 'block');
				},
				success: function(data) {
					setResultRows(data);
				},
				error: function() {
					$loadingRows.css('display', 'none');
				}
			})
		}
	}

	function getRowsBySearch(page) {
		page = page || 1;

		$.ajax({
			url: urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/search',
			type: 'POST',
			data: $('#search-form').serialize()+'&'+'page='+page+'&'+'rows_per_page='+$rowsPerPage.val(),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				$loadingRows.css('display', 'block');
			},
			success: function (data) {
				setResultRows(data);
			}
		})
	}

	function setResultRows(data) {
		$selectAll.prop('checked', false);
		$deleteAll.addClass('disabled');

		data.lastpage == 1 && $prePage.prop('disabled', true)
				.next().prop('disabled', true) ||
		data.page     == 1 && $prePage.prop('disabled', true)
				.next().prop('disabled', false) ||
		data.page     == data.lastpage && $prePage.prop('disabled', false)
				.next().prop('disabled', true) ||
		$prePage.prop('disabled', false)
				.next().prop('disabled', false);

		$totalRows.text(data.total);
		$currentPage.val(data.page);
		$lastPage.text(data.lastpage);

		if (data.results.length == 0) {
			$resultsContainer.css('display', 'none');
			$noResults.css('display', 'block');
		} else {
			$noResults.css('display', 'none');
			$resultsContainer.html(resultRowsFormat(data.results, urlArray)).css('display', '');
		}
		$loadingRows.css('display', 'none');
	}
	return {
		setResultRowsFormat: function (val) {
			resultRowsFormat = val;
		},
		setSearchValidate: function (val) {
			searchValidate = val;
		}
	}
})();

//module.exports = indexPage;
//export default indexPage