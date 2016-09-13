var $ = jQuery;
$(function(){
	
$('#wc_filter_reset_but').click(function() {
	window.location = window.location.origin + window.location.pathname;
});


function get_select_param(param, type) {
	var min = $('#wc_filter select.range#filter_' + type + '_min').val();
	var min_index = $('#wc_filter select#filter_' + type + '_min')[0].selectedIndex;
	var max = $('#wc_filter select.range#filter_' + type + '_max').val();
	var max_index = $('#wc_filter select.range#filter_' + type + '_max')[0].selectedIndex;
	$('#wc_filter select.range#filter_' + type + '_min > option').each(function(index) {
		if (max_index == 0) {
			max_index = $('#wc_filter select#filter_' + type + '_max')[0].length;
		}
		if (index >= min_index && index <= max_index) {
			var s = $(this).val().split('=');
			if (s != 'От...') {
				if (!param[s[0]]) {
					param[s[0]] = [];
				}
				param[s[0]].push(s[1]);
				param[s[0]] = $.unique(param[s[0]]);
			}
		}
	});
	return param;
}


$('#wc_filter_but').click(function() {
	var param = {};
	$('#wc_filter input:checkbox:checked').each(function() {
		var s = $(this).val().split('=');
		if (!param[s[0]]) {
			param[s[0]] = [];
		}
		param[s[0]].push(s[1]);
	});
	
	$('#wc_filter select.select > option:selected').each(function() {
		var s = $(this).val().split('=');
		if (s != '') {
			if (!param[s[0]]) {
				param[s[0]] = [];
			}
			param[s[0]].push(s[1]);
		}
	});
	
	var type_ar = [];
	$('#wc_filter select.range').each(function() {
		if ($(this)[0].selectedIndex != 0) {
			type_ar.push($(this).attr('id'));
		}
	});
	
	if (type_ar.length > 0) {
		$.each(type_ar, function( key, value) {
			value = value.replace('filter_', '');
			value = value.replace('_max', '');
			value = value.replace('_min', '');
			param = get_select_param(param, value);
		});
	}
	
	var ret = '';
	$.each(param, function(name, val){
		ret += name + '=' + val.join(',') + '&';
	});
	if ($('#min_price').val() != undefined) {
		if (parseInt($('#min_price').attr('data-min')) != parseInt($('.price_label > .from').text())) {
			ret += 'min_price=' + $('#min_price').val() + '&';
		}
		if (parseInt($('#max_price').attr('data-max')) != parseInt($('.price_label > .to').text())) {
			ret += 'max_price=' + $('#max_price').val() + '&';
		}
	}
	
	if ($('.search-field').val() != '') {
		ret += $('.search-field').val() + '&post_type=product&';
	}
	ret = ret.substr(0, ret.length - 1);
	url = window.location.origin + window.location.pathname;
	url = url.replace("/page/", "?");
	url = url.replace(/\?(.+)\?/, "");
	window.location = url + '?' + ret;
});


$('#wc_filter .wc_filter_list select').change(function() {
	var name = $(this).parent().attr('data-list');
	$('#wc_filter input:checkbox[name="' + name + '_query"]').prop('checked', 'true');
});

$('#wc_filter .wc_filter_list input:checkbox').click(function() {
	var name = $(this).parent().parent().attr('data-list');
	if ($('#wc_filter .wc_filter_list[data-list="' + name + '"] input:checkbox:checked').length > 0) {
		$('#wc_filter input:checkbox[name="' + name + '_query"]').prop('checked', 'true');
	}
});


$('.filterToggle').click(function() {
	var filterList = $(this).parent();
	filterList.hasClass('expand') ? filterList.removeClass('expand') : filterList.addClass('expand');
});

});