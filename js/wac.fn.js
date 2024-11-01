jQuery(function($){
// Configrations

var pluginDir = $("#pluginDir").html();

function escapeHtml(text) {
	  var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	  };
	
	  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
	}

$("#wac-check-btn").click(function(){
	if(!$(this).hasClass('active'))
	{
		return false;
	}
	var wac_nonce_security = $("#wac_nonce_security").val();
	$(".wac-results-sec").slideDown();
	$("#wac-check-btn").removeClass('active');
	$(".wac-value").html('<img src="' + pluginDir + 'imgs/loading.gif" />');
	$("#wac-url").html(escapeHtml($("#wac-input-url").val()));
	$.ajax({
		url : "",
		type: "post",
		data : {"url" : $("#wac-input-url").val(), wac_check_authority : 1, "nonce_security" : wac_nonce_security},
		dataType:"json",
		success: function(resp)
		{
			$("#wac-da").html(resp.domain_auth);
			$("#wac-pa").html(resp.page_auth);
			$("#wac-mr").html(resp.m_rank);
			$("#wac-le").html(resp.linking_domains);
			$("#wac-ip").html(resp.ip);
			$("#wac-check-btn").addClass('active');
		}
	});
});



});