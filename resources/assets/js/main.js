;(function(){
	
	'use strict';
	
	var indexPage = function ()
	{
		var urlArray = window.location.href.split('/');
		
		// search
		var $searchName = $('#search-name');
		var $searchStartAt = $('#search-start_at');
		var $searchEndAt = $('#search-end_at');
		
		// paginate
		var $currentPage = $('#current_page');
		var $totalRows = $('#total_rows');
		var $lastPage = $('#last_page');
		var $rowsPerPage = $('#rows_per_page');
		var $selectAll = $('#select-all');
		var $deleteAll = $('#delete-all');
		var $prePage = $('#pre_page');
		var $nextPage = $('#next_page');
		var $refresh = $('#refresh');
		
		// results
		var $loadingRows = $('.loading_rows');
		var $resultsContainer = $('.results_container');
		var $noResults = $('.no_results');
		
		$refresh.on('click', getRows);
		$rowsPerPage.on('change', getRows);
		$currentPage.on('blur', getRows);
		$prePage.on('click', getRows(parseInt($currentPage.val())-1));
		$nextPage.on('click', getRows(parseInt($currentPage.val())+1));
		
		$('#search').on('click', function() {
			if(searchValidate()) {
				getRowsBySearch();
			}
		});
		
		$('#cancel_search').on('click', function() {
			$searchName.val('');
			$searchStartAt.val('');
			$searchEndAt.val('');
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
			var url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + $(this).data('id');
			$('#item_search_container').css('display', 'none');
			$('#item_show_container').css('display', 'block');
			$.ajax({
				url: url + '/view',
				type: 'GET',
				beforeSend: function() {
					history.replaceState('', '', url);
					$('.panel-right').scrollTop(0);
					$('.selected').removeClass('selected');
					$(this).parents('.result_rows').addClass('selected');
					$('.loading').css('display', 'block');

				},
				success: function (data) {
					$('#view-name').text(data.name);
					$('#view-user_name').val(data.user_name);
					$('#view-created_at').val(data.created_at);
					$('#view-supervision_name').val(data.supervision_name);
					$('#view-technicians').text(data.technicians || null);
					$('#view-custodians').text(data.custodians || null);
					$('#view-safety_officers').text(data.safety_officers || null);
					$('#view-start_at').val(data.start_at);
					$('#view-finish_at').val(data.finish_at);
					$('#view-description').html(data.description);
					$('.loading').css('display', 'none');
				}
			})
		});
		
		$resultsContainer.on('click', '.results-delete', deleteRows);
		
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
			var delete_ids = [];
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
			var url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3];
			ids = ids || [].push($(this).data('id'));
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
					beforeSend: function() {
						if($.inArray(urlArray[4], ids) !== -1) {
							history.replaceState('', '', url);
							$('#view-name').text('');
							$('#view-user_name').val('');
							$('#view-created_at').val('');
							$('#view-supervision_name').val('');
							$('#view-technicians').text('');
							$('#view-custodians').text('');
							$('#view-safety_officers').text('');
							$('#view-start_at').val('');
							$('#view-finish_at').val('');
							$('#view-description').text('');
						}
					},
					success: function (data) {
						swal('条目已被删除', '', 'success');
						getRows();
					},
					error: function() {
						swal('权限不足', '', 'error');
					}
				})
			})
		}
		
		function getRows(page) {
			page = page || $currentPage.val();
			page = (/^\+?[1-9][0-9]*$/.test(page) && (page <= $lastPage.text() && page || $lastPage.text()) || 1);
			
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
	  
		function searchValidate() {
			if ($searchStartAt.val() && $searchEndAt.val() == '') {
				$searchName.attr('placeholder', '');
				$searchEndAt.focus();
				return false;
			}

			if ($searchEndAt.val() && searchStartAt.val() == '') {
				$searchName.attr('placeholder', '');
				$searchStartAt.focus();
				return false;
			}
			
			if ($searchName.val() == '' && $searchStartAt.val() == '' && $searchEndAt.val() == '') {
				$searchName.attr('placeholder', '输入工程名称').focus();
				return false;
			}
			return true;
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
				var html;
				$.each(data.results, function(index,element){
					
					//if this row is current page's parameter ,add class 'selected '
					var url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + element.id;
					html += url === window.location.href && "<tr class='result_rows selected'>" || "<tr class='result_rows'>";

					html +=
						"<td><label for='id'><input class='select-checkbox' type='checkbox' value='"+element.id+"' onclick='javascript:selectCheckbox($(this));'></label></td>"+
						"<td>"+element.id+"</td>"+
						"<td><div style='max-width:260px'><a href='javascript:void(0)'>"+element.name+"</a></div></td>"+
						"<td><a href='#'>"+element.supervision.name+"</a></td>"+
						"<td>"+element.start_at+"</td>"+
						"<td>"+element.finish_at+"</td>"+
						'<td><div><a href="' + url + '/edit"'+" class='btn btn-primary btn-sm' style='background-color: #18a689;border-color: #18a689;color: white;margin: 2px 0;'> <i class='glyphicon glyphicon-edit' aria-hidden='true'></i></a>"+' <form action="'+ url +'" method="post" style="display: inline-block;">'+"{{ csrf_field() }}{{ method_field('DELETE') }}"+'<button type="button" class="btn btn-danger btn-sm btn-del" style="background-color: #ed5565;border-color: #ed5565;color: white;margin: 2px 0;"><i class="glyphicon glyphicon-trash"></i></button></form></div></td>'+
						"</tr>";
						
					$noResults.css('display', 'none');
					$resultsContainer.html(html).css('display', '');
				});
			}
			$loadingRows.css('display', 'none');
		}
	};
	
	$(function(){
       indexPage();
    });
}());